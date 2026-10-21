<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $url, public int $minutes) {}

    public function build(): self
    {
        return $this->subject('Choose a new password for Delphina Studio')
            ->view('emails.admin-password-reset')
            ->text('emails.text.admin-password-reset')
            ->with([
                'url' => $this->url,
                'minutes' => $this->minutes,
            ]);
    }
}
