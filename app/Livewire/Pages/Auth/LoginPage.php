<?php

namespace App\Livewire\Pages\Auth;

use App\Livewire\Concerns\ThrottlesRequests;
use App\Livewire\Pages\PageComponent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginPage extends PageComponent
{
    use ThrottlesRequests;

    public string $email = '';
    public string $password = '';
    public bool $remember = true;

    public function mount(): void
    {
        if (Auth::check()) {
            $this->redirectRoute('account.dashboard', navigate: true);
        }
    }

    public function login(): void
    {
        $key = $this->throttle('login', 6, 60);

        $credentials = $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Unesite email adresu.',
            'email.email' => 'Email adresa nije u ispravnom formatu.',
            'password.required' => 'Unesite lozinku.',
        ]);

        if (! Auth::attempt($credentials, $this->remember)) {
            throw ValidationException::withMessages([
                'email' => 'Pogrešan email ili lozinka.',
            ]);
        }

        request()->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->is_banned) {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Vaš nalog je suspendovan. Kontaktirajte podršku.',
            ]);
        }

        $user->forceFill(['last_seen_at' => now()])->saveQuietly();

        $this->clearThrottle($key);

        if (! $user->hasVerifiedEmail()) {
            $this->redirectRoute('verification.notice', navigate: true);

            return;
        }

        $this->redirectRoute('account.dashboard', navigate: true);
    }

    protected function title(): string
    {
        return 'Prijava | AutoIQ';
    }

    protected function meta(): array
    {
        return [
            ...parent::meta(),
            'description' => 'Prijavite se na AutoIQ i upravljajte svojim oglasima, favoritima i alarmima.',
            'robots' => 'noindex,nofollow',
        ];
    }

    public function render(): View
    {
        return $this->page(view('livewire.pages.auth.login-page'));
    }
}
