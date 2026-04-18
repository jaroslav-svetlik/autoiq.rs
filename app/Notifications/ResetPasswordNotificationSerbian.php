<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotificationSerbian extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('Reset lozinke za AutoIQ')
            ->view('emails.branded-action', [
                'title' => 'Reset lozinke',
                'preheader' => 'Primili smo zahtev za promenu lozinke na vašem AutoIQ nalogu.',
                'introLines' => [
                    'Primili smo zahtev za promenu lozinke na vašem AutoIQ nalogu.',
                    'Kliknite na dugme ispod i postavite novu lozinku. Link važi ograničeno vreme iz bezbednosnih razloga.',
                ],
                'ctaLabel' => 'Postavi novu lozinku',
                'ctaUrl' => $url,
                'outroLines' => [
                    'Ako niste poslali zahtev, nije potrebno da preduzimate ništa.',
                ],
            ]);
    }
}
