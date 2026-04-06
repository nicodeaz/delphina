<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AvailableDate;
use App\Models\Payment;
use App\Models\Service;
use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function create()
    {
        $services = Service::all();
        return view('booking.create', compact('services'));
    }

    public function index()
    {
        $services = Service::all();
        return view('booking.index', compact('services'));
    }

    public function store(Request $request)
    {
        $rules = [
            'services' => 'required|string', // JSON string of selected services
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|date_format:H:i',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'preferred_contact' => 'nullable|string|in:email,phone,whatsapp',
            'notes' => 'nullable|string|max:1000',
            'terms' => 'required|accepted',
        ];

        $request->validate($rules);

        // Parse services from JSON
        $servicesData = json_decode($request->services, true);
        if (!$servicesData || !is_array($servicesData)) {
            return back()->withErrors(['services' => 'Invalid services data.']);
        }

        // Check availability for the time slot
        if (!$this->isTimeSlotAvailable($request->appointment_date, $request->appointment_time, $servicesData)) {
            return back()->withErrors(['appointment_time' => 'Time slot not available. Please select another time or date.']);
        }

        // Generate a group ID for related appointments
        $groupId = uniqid('booking_', true);

        $appointments = [];
        $totalAmount = 0;

        // Create appointments for each service
        foreach ($servicesData as $serviceName => $serviceData) {
            $service = Service::where('name', $serviceName)->first();
            if (!$service) {
                continue; // Skip if service not found
            }

            $appointmentData = [
                'service_id' => $service->id,
                'date' => $request->appointment_date,
                'time' => $request->appointment_time,
                'status' => 'confirmed', // AUTO-CONFIRMED as per user requirement
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'preferred_contact' => $request->preferred_contact,
                'notes' => $request->notes,
                'group_id' => $groupId,
            ];

            $appointment = Appointment::create($appointmentData);
            $appointments[] = $appointment;
            $totalAmount += $service->price;

            // Try to sync with Google Calendar (only for the first appointment to avoid duplicates)
            if (count($appointments) === 1) {
                try {
                    $calendarService = app(GoogleCalendarService::class);
                    $calendarService->createEvent($appointment, $service);
                } catch (\Exception $e) {
                    \Log::warning('Could not sync appointment to calendar: ' . $e->getMessage());
                }
            }
        }

        if (empty($appointments)) {
            return back()->withErrors(['services' => 'No valid services found.']);
        }

        // Create a single payment for the group
        $payment = Payment::create([
            'appointment_id' => $appointments[0]->id, // Link to first appointment
            'amount' => 15.00, // Fixed deposit
            'status' => 'pending',
            'group_id' => $groupId,
        ]);

        return redirect()->route('payments.show', $appointments[0]->id)
            ->with('success', 'Appointment confirmed! Please complete your €15 deposit to finalize.');
    }

    /**
     * Check if a time slot is available for multiple services
     */
    private function isTimeSlotAvailable($date, $time, $servicesData)
    {
        // Check if the date/time is within available periods
        $dateConfig = AvailableDate::where('date', $date)
            ->where('is_active', true)
            ->get();

        if ($dateConfig->isEmpty()) {
            return false;
        }

        $timeObj = \DateTime::createFromFormat('H:i', $time);

        // Check if time falls within any configured period
        $isWithinPeriod = false;
        foreach ($dateConfig as $config) {
            $startTime = \DateTime::createFromFormat('H:i', $config->start_time);
            $endTime = \DateTime::createFromFormat('H:i', $config->end_time);

            if ($timeObj >= $startTime && $timeObj < $endTime) {
                $isWithinPeriod = true;
                break;
            }
        }

        if (!$isWithinPeriod) {
            return false;
        }

        // Calculate total duration of all services
        $totalDuration = 0;
        foreach ($servicesData as $serviceName => $serviceData) {
            $service = Service::where('name', $serviceName)->first();
            if ($service) {
                $totalDuration += $service->duration;
            }
        }

        // Check if slot is not already booked (considering duration overlap)
        $startTime = strtotime($time);
        $endTime = $startTime + $totalDuration * 60;

        $conflicting = Appointment::where('date', $date)
            ->whereIn('status', ['confirmed'])
            ->get()
            ->filter(function ($appointment) use ($startTime, $endTime) {
                $appStart = strtotime($appointment->time);
                $appEnd = $appStart + $appointment->service->duration * 60;
                return ($startTime < $appEnd && $endTime > $appStart);
            });

        return $conflicting->isEmpty();
    }
}

