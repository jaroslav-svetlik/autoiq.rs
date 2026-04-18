<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotificationSerbian extends VerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Potvrdite svoju AutoIQ email adresu')
            ->view('emails.branded-action', [
                'title' => 'Potvrdite email adresu',
                'preheader' => 'Još jedan korak do aktivnog AutoIQ naloga.',
                'introLines' => [
                    'Dobro došli na AutoIQ.rs.',
                    'Kliknite na dugme ispod kako biste potvrdili email adresu i aktivirali sve funkcionalnosti naloga.',
                ],
                'ctaLabel' => 'Potvrdi email adresu',
                'ctaUrl' => $verificationUrl,
                'outroLines' => [
                    'Ako niste kreirali nalog, slobodno ignorišite ovu poruku.',
                ],
            ]);
    }
}
