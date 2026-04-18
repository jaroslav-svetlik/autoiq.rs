<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Notifications\ResetPasswordNotificationSerbian;
use App\Notifications\VerifyEmailNotificationSerbian;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasRoles;
    use Notifiable;

    protected string $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'role',
        'phone',
        'city',
        'avatar_path',
        'bio',
        'is_banned',
        'last_seen_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_banned' => 'boolean',
            'last_seen_at' => 'datetime',
        ];
    }

    public function dealerProfile(): HasOne
    {
        return $this->hasOne(DealerProfile::class);
    }

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    public function favoriteListings(): BelongsToMany
    {
        return $this->belongsToMany(Listing::class, 'favorites')->withTimestamps();
    }

    public function savedSearches(): HasMany
    {
        return $this->hasMany(SavedSearch::class);
    }

    public function socialAccounts(): HasMany
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin || $this->hasRole(UserRole::Admin->value);
    }

    public function isDealer(): bool
    {
        return $this->role === UserRole::Dealer || $this->hasRole(UserRole::Dealer->value);
    }

    public function roleLabel(): string
    {
        return $this->role->label();
    }

    public function hasFavorited(Listing|int $listing): bool
    {
        $listingId = $listing instanceof Model ? $listing->getKey() : $listing;

        return $this->favoriteListings()->whereKey($listingId)->exists();
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotificationSerbian);
    }

    public function sendPasswordResetNotification(#[\SensitiveParameter] $token): void
    {
        $this->notify(new ResetPasswordNotificationSerbian($token));
    }

    public function syncPlatformRole(UserRole|string $role): void
    {
        $enum = UserRole::fromMixed($role);

        if ($this->role !== $enum) {
            $this->forceFill(['role' => $enum])->saveQuietly();
        }

        if (! $this->exists || ! Schema::hasTable(config('permission.table_names.roles', 'roles'))) {
            return;
        }

        $roleExists = Role::query()
            ->where('name', $enum->value)
            ->where('guard_name', $this->guard_name)
            ->exists();

        if ($roleExists) {
            $this->syncRoles([$enum->value]);
        }
    }
}
