<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function show($appointmentId)
    {
        $appointment = Appointment::with('service', 'payment')->findOrFail($appointmentId);
        return view('payment', compact('appointment'));
    }

    public function process(Request $request, $appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        $payment = $appointment->payment;

        // Simulate payment success
        $payment->update([
            'status' => 'paid',
            'transaction_id' => 'sim_' . uniqid(),
        ]);

        $appointment->update(['status' => 'approved']);

        return redirect()->route('home')->with('success', 'Turno confirmado!');
    }
}
