<?php

namespace App\Livewire\Pages\Auth;

use App\Livewire\Concerns\ThrottlesRequests;
use App\Livewire\Pages\PageComponent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Password;

class ForgotPasswordPage extends PageComponent
{
    use ThrottlesRequests;

    public string $email = '';

    public function sendResetLink(): void
    {
        $this->throttle('forgot-password', 4, 120);

        $validated = $this->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Unesite email adresu.',
            'email.email' => 'Email adresa nije ispravna.',
        ]);

        Password::sendResetLink($validated);

        session()->flash('status', 'Ako nalog postoji, poslali smo link za reset lozinke.');
    }

    protected function title(): string
    {
        return 'Reset lozinke | AutoIQ';
    }

    protected function meta(): array
    {
        return [
            ...parent::meta(),
            'robots' => 'noindex,nofollow',
        ];
    }

    public function render(): View
    {
        return $this->page(view('livewire.pages.auth.forgot-password-page'));
    }
}
