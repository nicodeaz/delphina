<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Sent to the studio admin whenever a client books online, so Delfi knows to
 * expect the Revolut deposit. (No Reply-To to the client: a free-mail Reply-To
 * that differs from the sender is a strong spam signal.)
 */
class NewBookingNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Appointment $appointment) {}

    public function build(): self
    {
        $appointments = $this->appointment->group_id
            ? Appointment::with('service')->where('group_id', $this->appointment->group_id)->orderBy('id')->get()
            : collect([$this->appointment->loadMissing('service')]);

        $mail = $this->subject(sprintf(
            'New booking: %s · %s %s',
            $this->appointment->name,
            $this->appointment->date->format('D j M'),
            substr($this->appointment->time, 0, 5)
        ))->view('emails.new-booking')
            ->text('emails.text.new-booking')
            ->with([
                'appointment' => $this->appointment,
                'appointments' => $appointments,
            ]);

        return $mail;
    }
}
