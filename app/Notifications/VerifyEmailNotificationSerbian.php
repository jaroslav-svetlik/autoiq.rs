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
            ->greeting('Dobro došli na AutoIQ')
            ->line('Kliknite na dugme ispod kako biste potvrdili email adresu i aktivirali sve funkcionalnosti naloga.')
            ->action('Potvrdi email adresu', $verificationUrl)
            ->line('Ako niste kreirali nalog, slobodno ignorišite ovu poruku.');
    }
}
