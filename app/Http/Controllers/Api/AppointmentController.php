<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AvailableDate;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Auth::user()->appointments()->with('service', 'payment')->get();
        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date|after:today',
            'time' => 'required|date_format:H:i',
        ]);

        if (!$this->isAvailable($request->service_id, $request->date, $request->time)) {
            return response()->json(['error' => 'Horario no disponible'], 422);
        }

        $appointment = Appointment::create([
            'user_id' => Auth::id(),
            'service_id' => $request->service_id,
            'date' => $request->date,
            'time' => $request->time,
            'status' => 'confirmed', // Auto-approved per user request
        ]);

        Payment::create([
            'appointment_id' => $appointment->id,
            'amount' => 15.00,
            'status' => 'pending',
        ]);

        return response()->json($appointment->load('service', 'payment'), 201);
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        return response()->json($appointment->load('service', 'payment', 'user'));
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        $appointment->delete();
        return response()->json(['message' => 'Appointment cancelled']);
    }

    /**
     * Get available time slots for a specific date and service
     * Uses backend-configured available dates (NOT mock data)
     */
    public function availableSlots(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date|after:today',
        ]);

        $service = Service::find($request->service_id);
        
        // Get slots from AvailableDate model (backend-configured)
        $availableSlots = AvailableDate::slotsForDate($request->date, $service->duration);

        // Filter out already booked slots
        $bookedTimes = Appointment::where('date', $request->date)
            ->where('service_id', $request->service_id)
            ->where('status', '!=', 'cancelled')
            ->pluck('time')
            ->toArray();

        $availableSlots = array_filter($availableSlots, function($slot) use ($bookedTimes) {
            return !in_array($slot, $bookedTimes);
        });

        return response()->json([
            'available_slots' => array_values($availableSlots),
            'date' => $request->date,
            'service_id' => $request->service_id,
        ]);
    }

    /**
     * Get next available dates (for UI dropdown/calendar view)
     */
    public function nextAvailableDates(Request $request)
    {
        $request->validate([
            'days' => 'nullable|integer|min:1|max:90',
        ]);

        $days = $request->get('days', 30);
        $dates = AvailableDate::nextAvailableDates($days);

        return response()->json([
            'available_dates' => $dates,
            'count' => count($dates),
        ]);
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate(['status' => 'required|in:cancelled']);
        $appointment->update(['status' => $request->status]);
        return response()->json($appointment);
    }

    public function adminIndex()
    {
        $appointments = Appointment::with('user', 'service', 'payment')->get();
        return response()->json($appointments);
    }

    public function adminUpdateStatus(Request $request, Appointment $appointment)
    {
        $request->validate(['status' => 'required|in:approved,rejected,cancelled']);
        $appointment->update(['status' => $request->status]);
        return response()->json($appointment);
    }

    /**
     * Check if a specific time is available
     */
    private function isAvailable($serviceId, $date, $time)
    {
        // Check if time is within available dates/times
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

        // Check if slot is not already booked
        $isBooked = Appointment::where('date', $date)
            ->where('time', $time)
            ->where('status', '!=', 'cancelled')
            ->exists();

        return !$isBooked;
    }
}
