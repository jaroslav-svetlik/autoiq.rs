<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class OAuthController extends Controller
{
    public function redirect(string $provider): RedirectResponse
    {
        abort_unless($this->isSupportedProvider($provider), 404);

        if (! $this->isConfigured($provider)) {
            return redirect()
                ->route('login')
                ->with('status', $this->providerLabel($provider).' prijava trenutno nije dostupna.');
        }

        return Socialite::driver($provider)
            ->scopes(['email'])
            ->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        abort_unless($this->isSupportedProvider($provider), 404);

        if (! $this->isConfigured($provider)) {
            return redirect()
                ->route('login')
                ->with('status', $this->providerLabel($provider).' prijava trenutno nije dostupna.');
        }

        try {
            $providerUser = Socialite::driver($provider)->user();
        } catch (Throwable) {
            return redirect()
                ->route('login')
                ->with('status', 'Prijava nije završena. Pokušajte ponovo.');
        }

        $email = mb_strtolower(trim((string) $providerUser->getEmail()));
        $providerId = trim((string) $providerUser->getId());

        if ($email === '' || $providerId === '') {
            return redirect()
                ->route('login')
                ->with('status', $this->providerLabel($provider).' nalog nije vratio potrebne podatke za prijavu.');
        }

        $user = DB::transaction(function () use ($provider, $providerUser, $providerId, $email): User {
            $socialAccount = SocialAccount::query()
                ->where('provider', $provider)
                ->where('provider_id', $providerId)
                ->with('user')
                ->first();

            if ($socialAccount) {
                $socialAccount->update($this->socialAccountAttributes($providerUser, $email));

                if (! $socialAccount->user->hasVerifiedEmail()) {
                    $socialAccount->user->forceFill(['email_verified_at' => now()])->save();
                }

                return $socialAccount->user;
            }

            $user = User::query()->where('email', $email)->first();

            if (! $user) {
                $user = User::query()->create([
                    'name' => $providerUser->getName() ?: Str::before($email, '@'),
                    'email' => $email,
                    'email_verified_at' => now(),
                    'password' => Str::random(48),
                    'role' => UserRole::User,
                ]);

                $user->syncPlatformRole(UserRole::User);
            } elseif (! $user->hasVerifiedEmail()) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }

            $user->socialAccounts()->updateOrCreate(
                ['provider' => $provider],
                [
                    'provider_id' => $providerId,
                    ...$this->socialAccountAttributes($providerUser, $email),
                ],
            );

            return $user;
        });

        if ($user->is_banned) {
            return redirect()
                ->route('login')
                ->with('status', 'Vaš nalog je suspendovan. Kontaktirajte podršku.');
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        $user->forceFill(['last_seen_at' => now()])->saveQuietly();

        return redirect()->intended(route('account.dashboard'));
    }

    protected function socialAccountAttributes(SocialiteUser $providerUser, string $email): array
    {
        return [
            'email' => $email,
            'name' => $providerUser->getName(),
            'avatar_url' => $providerUser->getAvatar(),
        ];
    }

    protected function isSupportedProvider(string $provider): bool
    {
        return in_array($provider, ['google', 'facebook'], true);
    }

    protected function isConfigured(string $provider): bool
    {
        return filled(config("services.{$provider}.client_id"))
            && filled(config("services.{$provider}.client_secret"))
            && filled(config("services.{$provider}.redirect"));
    }

    protected function providerLabel(string $provider): string
    {
        return match ($provider) {
            'facebook' => 'Facebook',
            default => 'Google',
        };
    }
}
