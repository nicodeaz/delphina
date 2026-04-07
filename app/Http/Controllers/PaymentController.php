<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function show(Appointment $appointment)
    {
        if (Auth::check() && $appointment->user_id !== auth()->id()) {
            abort(403);
        }

        if ($appointment->payment && $appointment->payment->status === 'paid') {
            return redirect()->route('home')
                ->with('success', 'This appointment has already been paid.');
        }

        $payment = $appointment->payment ?? Payment::create([
            'appointment_id' => $appointment->id,
            'amount' => Payment::AMOUNT,
            'status' => 'pending',
        ]);

        return view('payments.show', [
            'appointment' => $appointment,
            'payment' => $payment,
        ]);
    }

    public function process(Request $request, Appointment $appointment)
    {
        if (Auth::check() && $appointment->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'payment_method' => 'required|in:card,transfer',
        ]);

        $payment = $appointment->payment ?? Payment::create([
            'appointment_id' => $appointment->id,
            'amount' => Payment::AMOUNT,
            'status' => 'pending',
        ]);

        // Simulate payment processing
        $payment->update([
            'status' => 'paid',
            'payment_method' => $request->payment_method,
            'transaction_id' => 'TXN-' . strtoupper(uniqid()),
        ]);

        $appointment->update(['status' => 'approved']);

        // Send confirmation email after successful payment
        $this->sendConfirmationEmail($appointment);

        return redirect()->route('home')
            ->with('success', 'Payment completed successfully! Your appointment is confirmed.');
    }

    private function sendConfirmationEmail(Appointment $appointment)
    {
        try {
            Mail::send('emails.appointment-confirmation', [
                'appointment' => $appointment,
            ], function ($message) use ($appointment) {
                $message->to($appointment->email, $appointment->name)
                        ->subject('Appointment Confirmed - Delfi Nail Technician');
            });

        } catch (\Exception $e) {
            Log::error('Failed to send confirmation email: ' . $e->getMessage());
        }
    }
}
