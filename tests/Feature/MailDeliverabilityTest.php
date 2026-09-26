<?php

namespace Tests\Feature;

use App\Mail\AdminLoginCodeMail;
use App\Mail\AppointmentConfirmationMail;
use App\Mail\NewBookingNotificationMail;
use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Guards the fixes for mails landing in spam: every mail has a plain-text
 * part, no free-mail Reply-To and no hidden preheader text.
 */
class MailDeliverabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_mail_has_a_plain_text_part_and_no_reply_to(): void
    {
        config(['mail.from' => ['address' => 'no-reply@okto.ie', 'name' => 'Nails by Delphina']]);

        $service = Service::create(['name' => 'BIAB - With Colour', 'description' => 'x', 'price' => 35, 'duration' => 60]);
        $appointment = Appointment::create([
            'service_id' => $service->id, 'date' => now()->addDays(2)->toDateString(), 'time' => '10:00',
            'status' => 'approved', 'name' => "Aoife O'Brien", 'email' => 'aoife@gmail.com', 'phone' => '0871234567',
        ]);

        $mails = [
            new AdminLoginCodeMail('123456', 10),
            new AppointmentConfirmationMail($appointment),
            new NewBookingNotificationMail($appointment),
        ];

        foreach ($mails as $mail) {
            $mail->to('someone@example.com');
            Mail::mailer('array')->send($mail);
            $sent = Mail::mailer('array')->getSymfonyTransport()->messages()->last()->getOriginalMessage();

            $this->assertNotEmpty($sent->getTextBody(), get_class($mail).' has no plain-text part');
            $this->assertNotEmpty($sent->getHtmlBody());
            $this->assertEmpty($sent->getReplyTo(), get_class($mail).' should not set Reply-To');
            $this->assertStringNotContainsString('display:none', $sent->getHtmlBody());
            $this->assertStringNotContainsString('&#039;', $sent->getTextBody(), 'plain text must not contain HTML entities');
        }
    }
}
