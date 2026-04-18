<?php

namespace Tests\Feature;

use App\Mail\ContactMessageMail;
use App\Models\User;
use App\Notifications\ResetPasswordNotificationSerbian;
use App\Notifications\VerifyEmailNotificationSerbian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailTemplateTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_message_uses_autoiq_branded_template(): void
    {
        $mail = new ContactMessageMail([
            'name' => 'Milan Petrović',
            'email' => 'milan@example.com',
            'phone' => '+381 64 123 4567',
            'topic' => 'Pitanje o oglasu',
            'message' => 'Zanima me da li oglas može dodatno da se proveri pre kupovine.',
            'submitted_at' => '18.04.2026. 22:45',
            'ip' => '127.0.0.1',
            'user_agent' => 'Feature test browser',
        ]);

        $html = (string) $mail->render();

        $this->assertStringContainsString('Auto<span style="color: #f59e0b;">IQ</span>', $html);
        $this->assertStringContainsString('Nova kontakt poruka', $html);
        $this->assertStringContainsString('Kontakt podaci', $html);
        $this->assertStringContainsString('Odgovori pošiljaocu', $html);
        $this->assertStringContainsString('background: #020617', $html);
    }

    public function test_verification_email_uses_autoiq_branded_template(): void
    {
        $user = User::factory()->unverified()->create([
            'email' => 'milan@example.com',
        ]);

        $mail = (new VerifyEmailNotificationSerbian)->toMail($user);
        $html = (string) $mail->render();

        $this->assertStringContainsString('Potvrdite email adresu', $html);
        $this->assertStringContainsString('Potvrdi email adresu', $html);
        $this->assertStringContainsString('Auto<span style="color: #f59e0b;">IQ</span>', $html);
        $this->assertStringContainsString('background: #020617', $html);
        $this->assertSame('Potvrdite svoju AutoIQ email adresu', $mail->subject);
    }

    public function test_password_reset_email_uses_autoiq_branded_template(): void
    {
        $user = User::factory()->create([
            'email' => 'milan@example.com',
        ]);

        $mail = (new ResetPasswordNotificationSerbian('reset-token'))->toMail($user);
        $html = (string) $mail->render();

        $this->assertStringContainsString('Reset lozinke', $html);
        $this->assertStringContainsString('Postavi novu lozinku', $html);
        $this->assertStringContainsString('Auto<span style="color: #f59e0b;">IQ</span>', $html);
        $this->assertStringContainsString('background: #020617', $html);
        $this->assertSame('Reset lozinke za AutoIQ', $mail->subject);
    }
}
