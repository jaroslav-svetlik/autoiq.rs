<?php

namespace App\Livewire\Concerns;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

trait ThrottlesRequests
{
    protected function throttle(string $action, int $maxAttempts = 5, int $decaySeconds = 60): string
    {
        $email = property_exists($this, 'email') ? mb_strtolower((string) ($this->email ?? '')) : 'guest';
        $key = implode('|', [$action, Auth::id() ?? $email, request()->ip()]);

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            throw ValidationException::withMessages([
                'rate_limit' => 'Previše pokušaja. Sačekajte minut i pokušajte ponovo.',
            ]);
        }

        RateLimiter::hit($key, $decaySeconds);

        return $key;
    }

    protected function clearThrottle(string $key): void
    {
        RateLimiter::clear($key);
    }
}
