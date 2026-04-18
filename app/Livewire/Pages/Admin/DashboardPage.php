<?php

namespace App\Livewire\Pages\Admin;

use App\Enums\ListingStatus;
use App\Enums\UserRole;
use App\Livewire\Pages\PageComponent;
use App\Models\DealerProfile;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Spatie\Permission\Models\Role;

class DashboardPage extends PageComponent
{
    #[Url(as: 'section')]
    public string $section = 'overview';

    #[Url(as: 'q')]
    public string $query = '';

    #[Url(as: 'user_role')]
    public string $userRoleFilter = '';

    #[Url(as: 'user_status')]
    public string $userStatusFilter = 'all';

    #[Url(as: 'listing_status')]
    public string $listingStatusFilter = '';

    #[Url(as: 'dealer_status')]
    public string $dealerVerificationFilter = 'all';

    public array $listingNotes = [];

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('view admin dashboard'), 403);
    }

    public function updated(string $property): void
    {
        if (in_array($property, [
            'query',
            'userRoleFilter',
            'userStatusFilter',
            'listingStatusFilter',
            'dealerVerificationFilter',
            'section',
        ], true)) {
            $this->resetErrorBag();
        }
    }

    public function setUserRole(int $userId, string $role): void
    {
        $this->ensurePermission('manage users');

        abort_unless(in_array($role, UserRole::values(), true), 422);

        $user = User::query()->findOrFail($userId);

        if ($user->id === auth()->id() && $role !== UserRole::Admin->value) {
            session()->flash('status', 'Ne možete sebi skinuti administratorsku rolu.');

            return;
        }

        if ($user->isAdmin() && $role !== UserRole::Admin->value && $this->adminCount() <= 1) {
            session()->flash('status', 'Sistem mora imati makar jednog administratora.');

            return;
        }

        $user->syncPlatformRole($role);

        session()->flash('status', "Rola za korisnika {$user->name} je ažurirana.");
    }

    public function toggleBan(int $userId): void
    {
        $this->ensurePermission('ban users');

        $user = User::query()->findOrFail($userId);

        if ($user->id === auth()->id()) {
            session()->flash('status', 'Ne možete banovati sopstveni nalog.');

            return;
        }

        if ($user->isAdmin() && ! $user->is_banned && $this->adminCount() <= 1) {
            session()->flash('status', 'Ne možete suspendovati poslednjeg administratora.');

            return;
        }

        $user->is_banned = ! $user->is_banned;
        $user->save();

        session()->flash('status', $user->is_banned ? 'Korisnik je suspendovan.' : 'Korisniku je vraćen pristup.');
    }

    public function toggleFeatured(int $listingId): void
    {
        $this->ensurePermission('feature listings');

        $listing = Listing::query()->findOrFail($listingId);
        $listing->is_featured = ! $listing->is_featured;
        $listing->featured_until = $listing->is_featured ? now()->addDays(14) : null;
        $listing->save();

        session()->flash('status', $listing->is_featured ? 'Oglas je istaknut na 14 dana.' : 'Isticanje oglasa je uklonjeno.');
    }

    public function setStatus(int $listingId, string $status): void
    {
        $this->ensurePermission('moderate listings');

        abort_unless(in_array($status, array_column(ListingStatus::cases(), 'value'), true), 422);

        $listing = Listing::query()->findOrFail($listingId);
        $listing->status = $status;

        if ($status === ListingStatus::Rejected->value) {
            $listing->rejected_reason = trim($this->listingNotes[$listingId] ?? '') ?: 'Potrebne su dodatne informacije ili korekcije u oglasu.';
        } else {
            $listing->rejected_reason = null;
        }

        if ($status === ListingStatus::Published->value && ! $listing->published_at) {
            $listing->published_at = now();
        }

        $listing->save();

        session()->flash('status', "Status oglasa \"{$listing->title}\" je postavljen na {$listing->status->label()}.");
    }

    public function toggleDealerVerification(int $dealerProfileId): void
    {
        $this->ensurePermission('manage dealers');

        $dealer = DealerProfile::query()->findOrFail($dealerProfileId);
        $dealer->verified_at = $dealer->verified_at ? null : now();
        $dealer->save();

        session()->flash('status', $dealer->verified_at ? 'Diler je verifikovan.' : 'Verifikacija dilera je uklonjena.');
    }

    public function toggleRolePermission(string $roleName, string $permission): void
    {
        $this->ensurePermission('manage roles');

        abort_unless(array_key_exists($permission, config('autoiq.permissions')), 422);
        abort_unless(in_array($roleName, UserRole::values(), true), 422);

        $role = Role::query()->where('name', $roleName)->where('guard_name', 'web')->firstOrFail();

        if (
            $roleName === UserRole::Admin->value
            && in_array($permission, ['view admin dashboard', 'manage roles'], true)
            && $role->hasPermissionTo($permission)
        ) {
            session()->flash('status', 'Kritične admin dozvole ne mogu biti uklonjene sa admin role.');

            return;
        }

        if ($role->hasPermissionTo($permission)) {
            $role->revokePermissionTo($permission);
            session()->flash('status', 'Dozvola je uklonjena sa role.');
        } else {
            $role->givePermissionTo($permission);
            session()->flash('status', 'Dozvola je dodeljena roli.');
        }
    }

    protected function ensurePermission(string $permission): void
    {
        abort_unless(auth()->user()?->can($permission), 403, 'Nemate dozvolu za ovu akciju.');
    }

    protected function adminCount(): int
    {
        return User::query()->role(UserRole::Admin->value)->count();
    }

    protected function title(): string
    {
        return 'Admin panel | AutoIQ';
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
        $users = User::query()
            ->with(['roles', 'permissions'])
            ->withCount(['listings', 'favoriteListings', 'savedSearches'])
            ->when($this->query !== '', function ($query) {
                $query->where(function ($inner) {
                    $inner
                        ->where('name', 'like', '%'.$this->query.'%')
                        ->orWhere('email', 'like', '%'.$this->query.'%')
                        ->orWhere('city', 'like', '%'.$this->query.'%');
                });
            })
            ->when($this->userRoleFilter !== '', fn ($query) => $query->where('role', $this->userRoleFilter))
            ->when($this->userStatusFilter === 'banned', fn ($query) => $query->where('is_banned', true))
            ->when($this->userStatusFilter === 'active', fn ($query) => $query->where('is_banned', false))
            ->latest()
            ->limit(20)
            ->get();

        $listings = Listing::query()
            ->with(['user.roles', 'images', 'dealerProfile'])
            ->when($this->query !== '', function ($query) {
                $query->where(function ($inner) {
                    $inner
                        ->where('title', 'like', '%'.$this->query.'%')
                        ->orWhere('brand', 'like', '%'.$this->query.'%')
                        ->orWhere('model', 'like', '%'.$this->query.'%')
                        ->orWhere('city', 'like', '%'.$this->query.'%');
                });
            })
            ->when($this->listingStatusFilter !== '', fn ($query) => $query->where('status', $this->listingStatusFilter))
            ->latest()
            ->limit(20)
            ->get();

        $dealers = DealerProfile::query()
            ->with('user.roles')
            ->withCount(['listings' => fn ($query) => $query->published()])
            ->when($this->query !== '', function ($query) {
                $query->where(function ($inner) {
                    $inner
                        ->where('company_name', 'like', '%'.$this->query.'%')
                        ->orWhere('city', 'like', '%'.$this->query.'%')
                        ->orWhere('email', 'like', '%'.$this->query.'%');
                });
            })
            ->when($this->dealerVerificationFilter === 'verified', fn ($query) => $query->whereNotNull('verified_at'))
            ->when($this->dealerVerificationFilter === 'unverified', fn ($query) => $query->whereNull('verified_at'))
            ->latest()
            ->limit(20)
            ->get();

        $roles = Role::query()
            ->with('permissions')
            ->where('guard_name', 'web')
            ->orderBy('name')
            ->get();

        return $this->page(view('livewire.pages.admin.dashboard-page', [
            'users' => $users,
            'listings' => $listings,
            'dealers' => $dealers,
            'roles' => $roles,
            'statuses' => ListingStatus::cases(),
            'roleOptions' => config('autoiq.roles'),
            'permissionLabels' => config('autoiq.permissions'),
            'summary' => [
                'users' => User::count(),
                'admins' => User::query()->role(UserRole::Admin->value)->count(),
                'dealers' => User::query()->role(UserRole::Dealer->value)->count(),
                'activeListings' => Listing::query()->where('status', ListingStatus::Published)->count(),
                'featuredListings' => Listing::query()->where('is_featured', true)->count(),
                'rejectedListings' => Listing::query()->where('status', ListingStatus::Rejected)->count(),
                'verifiedDealers' => DealerProfile::query()->whereNotNull('verified_at')->count(),
            ],
        ]));
    }
}
