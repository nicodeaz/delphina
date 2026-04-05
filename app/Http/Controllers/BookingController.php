<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function create()
    {
        $services = Service::all();
        return view('booking', compact('services'));
    }

    public function index()
    {
        $services = Service::all();
        return view('booking.index', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date|after:today',
            'time' => 'required|date_format:H:i',
        ]);

        // Check availability
        if (!$this->isAvailable($request->service_id, $request->date, $request->time)) {
            return back()->withErrors(['time' => 'Horario no disponible']);
        }

        $appointment = Appointment::create([
            'user_id' => Auth::id(),
            'service_id' => $request->service_id,
            'date' => $request->date,
            'time' => $request->time,
            'status' => 'pending',
        ]);

        // Create payment
        Payment::create([
            'appointment_id' => $appointment->id,
            'amount' => 15.00,
            'status' => 'pending',
        ]);

        return redirect()->route('payment', $appointment->id);
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
