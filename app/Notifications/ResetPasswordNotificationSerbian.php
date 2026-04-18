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
            ->greeting('Zahtev za reset lozinke')
            ->line('Primili smo zahtev za promenu lozinke na vašem AutoIQ nalogu.')
            ->action('Postavi novu lozinku', $url)
            ->line('Ako niste poslali zahtev, nije potrebno da preduzimate ništa.');
    }
}
