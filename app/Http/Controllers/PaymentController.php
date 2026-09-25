<?php

namespace App\Http\Controllers;

use App\Mail\AppointmentConfirmationMail;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function show(Appointment $appointment)
    {
        $this->authorizeBooking($appointment);

        $payment = $this->paymentFor($appointment);

        if ($payment->status === 'paid') {
            return redirect()->route('home')
                ->with('success', 'Your deposit is received and your appointment is confirmed. See you soon!');
        }

        $bookingAppointments = $appointment->group_id
            ? Appointment::with('service')->where('group_id', $appointment->group_id)->get()
            : collect([$appointment->load('service')]);

        return view('payments.show', [
            'appointment' => $appointment,
            'payment' => $payment,
            'bookingAppointments' => $bookingAppointments,
            'paymentConfigured' => filter_var(config('services.revolut.payment_link'), FILTER_VALIDATE_URL) !== false,
        ]);
    }

    public function process(Request $request, Appointment $appointment)
    {
        $this->authorizeBooking($appointment);

        $payment = $this->paymentFor($appointment);

        if ($payment->status === 'paid') {
            return redirect()->route('home')
                ->with('success', 'This appointment has already been paid.');
        }

        $paymentLink = config('services.revolut.payment_link');

        if (! is_string($paymentLink) || ! filter_var($paymentLink, FILTER_VALIDATE_URL)) {
            return back()->with(
                'error',
                'Online payments are not configured yet. Please contact Delfi to complete your deposit.'
            );
        }

        $payment->update([
            'payment_method' => 'revolut',
        ]);

        return redirect()->away($this->revolutCheckoutUrl($paymentLink, $payment, $appointment));
    }

    public function confirm(Request $request, Payment $payment)
    {
        if ($payment->status === 'paid') {
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'already_confirmed' => true]);
            }

            return back()->with('success', 'This deposit has already been confirmed.');
        }

        $payment->update([
            'status' => 'paid',
            'payment_method' => 'revolut',
        ]);

        $appointments = $payment->group_id
            ? Appointment::where('group_id', $payment->group_id)->get()
            : collect([$payment->appointment]);

        $appointments->each->update(['status' => 'approved']);
        $this->sendConfirmationEmail($payment->appointment);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Deposit confirmed and appointment approved.');
    }

    /**
     * A booking is made by a guest, so there is no account to check against.
     * Access is granted to whoever created it in this session, to the account
     * that owns it, or to an admin. Anyone else is a stranger guessing IDs.
     */
    private function authorizeBooking(Appointment $appointment): void
    {
        $user = auth()->user();

        if ($user && ($user->isAdmin() || ($appointment->user_id && $appointment->user_id === $user->id))) {
            return;
        }

        if (in_array($appointment->id, session('booking_access', []))) {
            return;
        }

        abort(403);
    }

    /**
     * Several appointment rows can share a group_id but they carry a single
     * €15 deposit, so always resolve the one payment for the whole booking
     * instead of creating a fresh one per row.
     */
    private function paymentFor(Appointment $appointment): Payment
    {
        if ($appointment->group_id) {
            $payment = Payment::where('group_id', $appointment->group_id)->first();

            if ($payment) {
                return $payment;
            }
        }

        return $appointment->payment ?? Payment::create([
            'appointment_id' => $appointment->id,
            'amount' => Payment::AMOUNT,
            'status' => 'pending',
            'group_id' => $appointment->group_id,
        ]);
    }

    private function revolutCheckoutUrl(string $paymentLink, Payment $payment, Appointment $appointment): string
    {
        $query = http_build_query([
            'currency' => 'EUR',
            'amount' => (int) round($payment->amount * 100),
            'note' => sprintf('Deposit - Delfi - %s - %s', $appointment->name, $appointment->date->format('Y-m-d')),
        ]);

        return Str::finish($paymentLink, Str::contains($paymentLink, '?') ? '&' : '?').$query;
    }

    private function sendConfirmationEmail(Appointment $appointment)
    {
        try {
            Mail::to($appointment->email, $appointment->name)
                ->send(new AppointmentConfirmationMail($appointment));
        } catch (\Exception $e) {
            Log::error('Failed to send confirmation email: '.$e->getMessage());
        }
    }
}
