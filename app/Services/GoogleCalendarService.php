<?php

namespace App\Services;

use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use Illuminate\Support\Facades\Log;
use App\Models\Appointment;
use App\Models\Service;

class GoogleCalendarService
{
    protected $client;
    protected $calendarService;
    protected $adminCalendarId;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName('Delfi Nail Technician');
        $this->client->setScopes([Calendar::CALENDAR_EVENTS]);
        $this->client->setAuthConfig(config('google-calendar.credentials_path'));
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');

        $this->calendarService = new Calendar($this->client);
        $this->adminCalendarId = config('google-calendar.admin_calendar_id', 'primary');
    }

    /**
     * Create events for both admin and client calendars
     */
    public function createAppointmentEvents(Appointment $appointment): array
    {
        try {
            $service = $appointment->service;
            $startDateTime = $appointment->date->format('Y-m-d') . 'T' . $appointment->time . ':00';
            $endDateTime = date('Y-m-d\TH:i:s', strtotime($startDateTime) + ($service->duration * 60));

            $eventTitle = $service->name . ' - ' . $appointment->name;
            $description = "Appointment Details:\n" .
                          "Client: {$appointment->name}\n" .
                          "Email: {$appointment->email}\n" .
                          "Phone: {$appointment->phone}\n" .
                          "Service: {$service->name}\n" .
                          "Duration: {$service->duration} minutes\n" .
                          "Location: Dublin 24, Tallaght";

            // Create event for admin calendar
            $adminEvent = $this->createEvent($this->adminCalendarId, $eventTitle, $description, $startDateTime, $endDateTime);

            // Create event for client calendar (if email provided)
            $clientEvent = null;
            if ($appointment->email) {
                try {
                    $clientEvent = $this->createEvent($appointment->email, $eventTitle, $description, $startDateTime, $endDateTime);
                } catch (\Exception $e) {
                    Log::warning('Could not create client calendar event: ' . $e->getMessage());
                }
            }

            return [
                'admin_event_id' => $adminEvent->getId(),
                'client_event_id' => $clientEvent ? $clientEvent->getId() : null,
            ];

        } catch (\Exception $e) {
            Log::error('Failed to create Google Calendar events: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a single event
     */
    protected function createEvent(string $calendarId, string $title, string $description, string $startDateTime, string $endDateTime): Event
    {
        $event = new Event();
        $event->setSummary($title);
        $event->setDescription($description);
        $event->setLocation('Dublin 24, Tallaght');

        $start = new EventDateTime();
        $start->setDateTime($startDateTime);
        $start->setTimeZone('Europe/Dublin');
        $event->setStart($start);

        $end = new EventDateTime();
        $end->setDateTime($endDateTime);
        $end->setTimeZone('Europe/Dublin');
        $event->setEnd($end);

        // Set reminders
        $reminders = new \Google\Service\Calendar\EventReminders();
        $reminders->setUseDefault(false);
        $reminders->setOverrides([
            ['method' => 'email', 'minutes' => 24 * 60], // 24 hours before
            ['method' => 'popup', 'minutes' => 30], // 30 minutes before
        ]);
        $event->setReminders($reminders);

        return $this->calendarService->events->insert($calendarId, $event);
    }

    /**
     * Update appointment events
     */
    public function updateAppointmentEvents(Appointment $appointment): void
    {
        try {
            $service = $appointment->service;
            $startDateTime = $appointment->date->format('Y-m-d') . 'T' . $appointment->time . ':00';
            $endDateTime = date('Y-m-d\TH:i:s', strtotime($startDateTime) + ($service->duration * 60));

            $eventTitle = $service->name . ' - ' . $appointment->name;
            $description = "Appointment Details:\n" .
                          "Client: {$appointment->name}\n" .
                          "Email: {$appointment->email}\n" .
                          "Phone: {$appointment->phone}\n" .
                          "Service: {$service->name}\n" .
                          "Duration: {$service->duration} minutes\n" .
                          "Location: Dublin 24, Tallaght";

            // Update admin event
            if ($appointment->google_event_id) {
                $this->updateEvent($this->adminCalendarId, $appointment->google_event_id, $eventTitle, $description, $startDateTime, $endDateTime);
            }

            // Update client event if exists
            if ($appointment->google_client_event_id) {
                try {
                    $this->updateEvent($appointment->email, $appointment->google_client_event_id, $eventTitle, $description, $startDateTime, $endDateTime);
                } catch (\Exception $e) {
                    Log::warning('Could not update client calendar event: ' . $e->getMessage());
                }
            }

        } catch (\Exception $e) {
            Log::error('Failed to update Google Calendar events: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update a single event
     */
    protected function updateEvent(string $calendarId, string $eventId, string $title, string $description, string $startDateTime, string $endDateTime): Event
    {
        $event = $this->calendarService->events->get($calendarId, $eventId);

        $event->setSummary($title);
        $event->setDescription($description);

        $start = new EventDateTime();
        $start->setDateTime($startDateTime);
        $start->setTimeZone('Europe/Dublin');
        $event->setStart($start);

        $end = new EventDateTime();
        $end->setDateTime($endDateTime);
        $end->setTimeZone('Europe/Dublin');
        $event->setEnd($end);

        return $this->calendarService->events->update($calendarId, $eventId, $event);
    }

    /**
     * Delete appointment events
     */
    public function deleteAppointmentEvents(Appointment $appointment): void
    {
        try {
            // Delete admin event
            if ($appointment->google_event_id) {
                $this->calendarService->events->delete($this->adminCalendarId, $appointment->google_event_id);
            }

            // Delete client event if exists
            if ($appointment->google_client_event_id && $appointment->email) {
                try {
                    $this->calendarService->events->delete($appointment->email, $appointment->google_client_event_id);
                } catch (\Exception $e) {
                    Log::warning('Could not delete client calendar event: ' . $e->getMessage());
                }
            }

        } catch (\Exception $e) {
            Log::error('Failed to delete Google Calendar events: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Check availability for a specific date and time
     */
    public function isTimeSlotAvailable(string $date, string $time, int $duration): bool
    {
        try {
            $startDateTime = $date . 'T' . $time . ':00';
            $endDateTime = date('Y-m-d\TH:i:s', strtotime($startDateTime) + ($duration * 60));

            // Query admin calendar for conflicting events
            $events = $this->calendarService->events->listEvents($this->adminCalendarId, [
                'timeMin' => $startDateTime,
                'timeMax' => $endDateTime,
                'singleEvents' => true,
                'orderBy' => 'startTime',
            ]);

            return $events->getItems() ? false : true;

        } catch (\Exception $e) {
            Log::error('Failed to check availability: ' . $e->getMessage());
            // If API fails, assume available to not block bookings
            return true;
        }
    }

    /**
     * Get busy time slots for a date
     */
    public function getBusySlots(string $date): array
    {
        try {
            $startOfDay = $date . 'T00:00:00';
            $endOfDay = $date . 'T23:59:59';

            $events = $this->calendarService->events->listEvents($this->adminCalendarId, [
                'timeMin' => $startOfDay,
                'timeMax' => $endOfDay,
                'singleEvents' => true,
                'orderBy' => 'startTime',
            ]);

            $busySlots = [];
            foreach ($events->getItems() as $event) {
                $start = $event->getStart()->getDateTime();
                $end = $event->getEnd()->getDateTime();

                if ($start && $end) {
                    $busySlots[] = [
                        'start' => date('H:i', strtotime($start)),
                        'end' => date('H:i', strtotime($end)),
                    ];
                }
            }

            return $busySlots;

        } catch (\Exception $e) {
            Log::error('Failed to get busy slots: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Sync changes from Google Calendar (for bidirectional sync)
     */
    public function syncCalendarChanges(): void
    {
        try {
            // Get recent events from admin calendar
            $now = date('c');
            $future = date('c', strtotime('+30 days'));

            $events = $this->calendarService->events->listEvents($this->adminCalendarId, [
                'timeMin' => $now,
                'timeMax' => $future,
                'singleEvents' => true,
                'orderBy' => 'startTime',
            ]);

            foreach ($events->getItems() as $event) {
                // Check if this event corresponds to an appointment
                $appointment = Appointment::where('google_event_id', $event->getId())->first();

                if ($appointment) {
                    // Check if event was cancelled/deleted in Google Calendar
                    if ($event->getStatus() === 'cancelled') {
                        $appointment->update(['status' => 'cancelled']);
                        Log::info("Appointment {$appointment->id} cancelled via Google Calendar sync");
                    }
                    // Could also check for time changes, but keeping simple for now
                }
            }

        } catch (\Exception $e) {
            Log::error('Failed to sync calendar changes: ' . $e->getMessage());
        }
    }
}