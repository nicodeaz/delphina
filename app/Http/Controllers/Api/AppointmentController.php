<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
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
            'status' => 'pending',
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

    public function availableSlots(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
        ]);

        $service = Service::find($request->service_id);
        $availableSlots = [];

        for ($hour = 9; $hour <= 18; $hour++) {
            for ($min = 0; $min < 60; $min += 30) {
                $time = sprintf('%02d:%02d', $hour, $min);
                if ($this->isAvailable($request->service_id, $request->date, $time)) {
                    $availableSlots[] = $time;
                }
            }
        }

        return response()->json(['available_slots' => $availableSlots]);
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

    private function isAvailable($serviceId, $date, $time)
    {
        $service = Service::find($serviceId);
        $start = strtotime($time);
        $end = $start + $service->duration * 60;

        $conflicting = Appointment::where('date', $date)
            ->whereIn('status', ['pending', 'approved'])
            ->get()
            ->filter(function ($appointment) use ($start, $end) {
                $appStart = strtotime($appointment->time);
                $appEnd = $appStart + $appointment->service->duration * 60;
                return ($start < $appEnd && $end > $appStart);
            });

        return $conflicting->isEmpty();
    }
}
