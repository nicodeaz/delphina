<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AvailableDate;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create()
    {
        $services = Service::all();
        $availableDates = AvailableDate::nextAvailableDates(90);

        return view('booking.create', compact('services', 'availableDates'));
    }

    public function store(Request $request)
    {
        $rules = [
            'services' => 'required|string|max:5000', // JSON object keyed by service name
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

        // Parse services from JSON; resolve them in one query (bounded list).
        $servicesData = json_decode($request->services, true);
        if (! $servicesData || ! is_array($servicesData) || count($servicesData) > 10) {
            return $this->bookingError($request, 'services', 'Please choose at least one service.');
        }

        $services = Service::whereIn('name', array_map('strval', array_keys($servicesData)))->get();
        if ($services->isEmpty()) {
            return $this->bookingError($request, 'services', 'Please choose at least one service.');
        }

        // Check availability for the time slot
        if (! $this->isTimeSlotAvailable($request->appointment_date, $request->appointment_time, (int) $services->sum('duration'))) {
            return $this->bookingError($request, 'appointment_time', 'Sorry, that time was just taken. Please pick another time or day.');
        }

        // Generate a group ID for related appointments
        $groupId = uniqid('booking_', true);

        $appointments = [];
        $totalAmount = 0;

        // Create appointments for each service
        foreach ($services as $service) {
            $appointmentData = [
                'service_id' => $service->id,
                'date' => $request->appointment_date,
                'time' => $request->appointment_time,
                'status' => 'pending',
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
        }

        if (empty($appointments)) {
            return $this->bookingError($request, 'services', 'Please choose at least one service.');
        }

        // Create a single payment for the group
        $payment = Payment::create([
            'appointment_id' => $appointments[0]->id, // Link to first appointment
            'amount' => Payment::AMOUNT, // Fixed deposit
            'status' => 'pending',
            'group_id' => $groupId,
        ]);

        // Bookings are made by guests, so remember in this session which ones
        // this visitor is allowed to view and pay for.
        $request->session()->put('booking_access', array_merge(
            $request->session()->get('booking_access', []),
            collect($appointments)->pluck('id')->all()
        ));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('payments.show', $appointments[0]->id),
            ]);
        }

        return redirect()->route('payments.show', $appointments[0]->id)
            ->with('success', 'Appointment confirmed! Please complete your €15 deposit to finalize.');
    }

    private function bookingError(Request $request, string $field, string $message)
    {
        if ($request->wantsJson()) {
            return response()->json(['message' => $message, 'errors' => [$field => [$message]]], 422);
        }

        return back()->withErrors([$field => $message])->withInput();
    }

    /**
     * Check if a time slot is available for multiple services
     */
    private function isTimeSlotAvailable($date, $time, int $totalDuration)
    {
        // The whole appointment has to fit inside one of the opened windows.
        $fitsInWindow = AvailableDate::whereDate('date', $date)
            ->where('is_active', true)
            ->get()
            ->contains(function ($config) use ($time, $totalDuration) {
                $start = strtotime(substr($config->start_time, 0, 5));
                $end = strtotime(substr($config->end_time, 0, 5));
                $bookingStart = strtotime($time);

                return $bookingStart >= $start && $bookingStart + $totalDuration * 60 <= $end;
            });

        if (! $fitsInWindow) {
            return false;
        }

        $startTime = strtotime($time);
        $endTime = $startTime + $totalDuration * 60;

        return collect($this->busyBlocks($date))->every(
            fn ($block) => ! ($startTime < $block['end'] && $endTime > $block['start'])
        );
    }

    /**
     * Occupied [start, end) ranges on a date. A multi-service booking is
     * several rows sharing a group_id and a start time, so the rows are
     * collapsed and their durations summed into one continuous block.
     */
    private function busyBlocks($date): array
    {
        return Appointment::with('service')
            ->whereDate('date', $date)
            ->whereIn('status', ['pending', 'approved', 'confirmed', 'completed'])
            ->get()
            ->groupBy(fn ($appointment) => $appointment->group_id ?: "appointment-{$appointment->id}")
            ->map(function ($rows) {
                $start = strtotime(substr($rows->first()->time, 0, 5));

                return [
                    'start' => $start,
                    'end' => $start + $rows->sum(fn ($a) => $a->service->duration ?? 0) * 60,
                ];
            })
            ->values()
            ->all();
    }

    public function getAvailableSlots(Request $request)
    {
        $date = $request->query('date');
        $totalDuration = max(15, (int) $request->query('duration', 60)); // Default 60 minutes

        if (! $date) {
            return response()->json(['error' => 'Date is required'], 400);
        }

        try {
            $slots = AvailableDate::slotsForDate($date, $totalDuration);
            $busy = $this->busyBlocks($date);

            return response()->json([
                'slots' => array_map(function ($slot) use ($busy, $totalDuration) {
                    $slotStart = strtotime($slot['time']);
                    $slotEnd = $slotStart + ($totalDuration * 60);

                    $available = collect($busy)->every(
                        fn ($block) => ! ($slotStart < $block['end'] && $slotEnd > $block['start'])
                    );

                    return [
                        'time' => $slot['time'],
                        'available' => $available,
                    ];
                }, $slots),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting available slots: '.$e->getMessage());

            return response()->json(['error' => 'Unable to load available slots'], 500);
        }
    }
}
