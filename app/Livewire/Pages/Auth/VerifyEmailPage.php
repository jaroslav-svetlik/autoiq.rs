<?php

namespace App\Livewire\Pages\Auth;

use App\Livewire\Concerns\ThrottlesRequests;
use App\Livewire\Pages\PageComponent;
use Illuminate\Contracts\View\View;

class VerifyEmailPage extends PageComponent
{
    use ThrottlesRequests;

    public function mount(): void
    {
        if (auth()->user()?->hasVerifiedEmail()) {
            $this->redirectRoute('account.dashboard', navigate: true);
        }
    }

    public function resend(): void
    {
        $this->throttle('verify-email', 3, 120);

        auth()->user()?->sendEmailVerificationNotification();

        session()->flash('status', 'Poslali smo novi verifikacioni email.');
    }

    protected function title(): string
    {
        return 'Potvrda email adrese | AutoIQ';
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
        return $this->page(view('livewire.pages.auth.verify-email-page'));
    }
}
