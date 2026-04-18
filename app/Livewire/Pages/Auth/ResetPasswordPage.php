<?php

namespace App\Livewire\Pages\Auth;

use App\Livewire\Concerns\ThrottlesRequests;
use App\Livewire\Pages\PageComponent;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordPage extends PageComponent
{
    use ThrottlesRequests;

    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $passwordConfirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = (string) request()->query('email', '');
    }

    public function resetPassword(): void
    {
        $this->throttle('reset-password', 5, 120);

        $validated = $this->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'same:passwordConfirmation'],
            'passwordConfirmation' => ['required'],
        ], [
            'email.required' => 'Email je obavezan.',
            'password.required' => 'Unesite novu lozinku.',
            'password.min' => 'Lozinka mora imati najmanje 8 karaktera.',
            'password.same' => 'Lozinke se ne poklapaju.',
        ]);

        $status = Password::reset(
            [
                'email' => $validated['email'],
                'password' => $validated['password'],
                'password_confirmation' => $validated['passwordConfirmation'],
                'token' => $validated['token'],
            ],
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            },
        );

        if ($status !== Password::PASSWORD_RESET) {
            $this->addError('email', 'Link za reset je nevažeći ili je istekao.');

            return;
        }

        session()->flash('status', 'Lozinka je uspešno promenjena. Prijavite se novim podacima.');
        $this->redirectRoute('login', navigate: true);
    }

    protected function title(): string
    {
        return 'Nova lozinka | AutoIQ';
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
        return $this->page(view('livewire.pages.auth.reset-password-page'));
    }
}
