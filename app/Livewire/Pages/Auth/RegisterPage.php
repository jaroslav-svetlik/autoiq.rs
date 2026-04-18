<?php

namespace App\Livewire\Pages\Auth;

use App\Enums\UserRole;
use App\Livewire\Concerns\ThrottlesRequests;
use App\Livewire\Pages\PageComponent;
use App\Models\DealerProfile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterPage extends PageComponent
{
    use ThrottlesRequests;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $passwordConfirmation = '';
    public string $role = 'user';
    public ?string $phone = null;
    public ?string $city = null;
    public ?string $dealerCompanyName = null;
    public ?string $dealerWebsite = null;
    public ?string $dealerDescription = null;

    public function mount(): void
    {
        if (Auth::check()) {
            $this->redirectRoute('account.dashboard', navigate: true);
        }
    }

    public function register(): void
    {
        $this->throttle('register', 4, 120);

        $validated = $this->validate($this->rules(), $this->messages());

        DB::transaction(function () use ($validated) {
            $user = User::query()->create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => $validated['role'],
                'phone' => $validated['phone'] ?? null,
                'city' => $validated['city'] ?? null,
            ]);

            $user->syncPlatformRole($validated['role']);

            if ($user->role === UserRole::Dealer) {
                DealerProfile::query()->create([
                    'user_id' => $user->id,
                    'company_name' => $validated['dealerCompanyName'],
                    'slug' => $this->uniqueDealerSlug($validated['dealerCompanyName']),
                    'description' => $validated['dealerDescription'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                    'email' => $validated['email'],
                    'website' => $validated['dealerWebsite'] ?? null,
                    'city' => $validated['city'] ?? null,
                ]);
            }

            event(new Registered($user));

            Auth::login($user);
        });

        $this->redirectRoute('verification.notice', navigate: true);
    }

    protected function rules(): array
    {
        $dealerRules = $this->role === UserRole::Dealer->value
            ? [
                'dealerCompanyName' => ['required', 'string', 'min:3', 'max:120'],
                'dealerWebsite' => ['nullable', 'url', 'max:255'],
                'dealerDescription' => ['nullable', 'string', 'max:1000'],
            ]
            : [
                'dealerCompanyName' => ['nullable'],
                'dealerWebsite' => ['nullable'],
                'dealerDescription' => ['nullable'],
            ];

        return [
            'name' => ['required', 'string', 'min:2', 'max:80'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'same:passwordConfirmation'],
            'passwordConfirmation' => ['required'],
            'role' => ['required', 'in:user,dealer'],
            'phone' => ['nullable', 'string', 'max:30'],
            'city' => ['nullable', 'string', 'max:80'],
            ...$dealerRules,
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Unesite ime i prezime.',
            'email.required' => 'Unesite email adresu.',
            'email.unique' => 'Ovaj email je već zauzet.',
            'password.required' => 'Unesite lozinku.',
            'password.min' => 'Lozinka mora imati najmanje 8 karaktera.',
            'password.same' => 'Lozinke se ne poklapaju.',
            'passwordConfirmation.required' => 'Potvrdite lozinku.',
            'dealerCompanyName.required' => 'Naziv dilera je obavezan za dilerski nalog.',
            'dealerWebsite.url' => 'Unesite ispravan URL sajta.',
        ];
    }

    protected function uniqueDealerSlug(string $companyName): string
    {
        $base = Str::slug($companyName);
        $slug = $base;
        $counter = 1;

        while (DealerProfile::query()->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    protected function title(): string
    {
        return 'Registracija | AutoIQ';
    }

    protected function meta(): array
    {
        return [
            ...parent::meta(),
            'description' => 'Kreirajte AutoIQ nalog i koristite pametne alate za kupovinu automobila u Srbiji.',
            'robots' => 'noindex,nofollow',
        ];
    }

    public function render(): View
    {
        return $this->page(view('livewire.pages.auth.register-page', [
            'roles' => collect(config('autoiq.roles'))->except(UserRole::Admin->value)->all(),
            'cities' => config('autoiq.cities'),
        ]));
    }
}
