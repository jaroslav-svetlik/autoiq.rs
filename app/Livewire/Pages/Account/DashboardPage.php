<?php

namespace App\Livewire\Pages\Account;

use App\Enums\UserRole;
use App\Livewire\Pages\PageComponent;
use App\Models\DealerProfile;
use App\Models\Listing;
use App\Models\SavedSearch;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;

class DashboardPage extends PageComponent
{
    #[Url(as: 'tab')]
    public string $tab = 'profil';

    public string $name = '';
    public string $email = '';
    public ?string $phone = null;
    public ?string $city = null;
    public ?string $bio = null;
    public ?string $dealerCompanyName = null;
    public ?string $dealerWebsite = null;
    public ?string $dealerDescription = null;

    public function mount(): void
    {
        $user = auth()->user()->load('dealerProfile');

        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->city = $user->city;
        $this->bio = $user->bio;
        $this->dealerCompanyName = $user->dealerProfile?->company_name;
        $this->dealerWebsite = $user->dealerProfile?->website;
        $this->dealerDescription = $user->dealerProfile?->description;
    }

    public function saveProfile(): void
    {
        $user = auth()->user()->load('dealerProfile');
        $originalEmail = $user->email;

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'string', 'max:30'],
            'city' => ['nullable', 'string', 'max:80'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'dealerCompanyName' => ['nullable', 'string', 'max:120'],
            'dealerWebsite' => ['nullable', 'url', 'max:255'],
            'dealerDescription' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.required' => 'Ime je obavezno.',
            'email.unique' => 'Ovaj email je već zauzet.',
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'city' => $validated['city'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ]);

        if ($originalEmail !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->role === UserRole::Dealer && $validated['dealerCompanyName']) {
            $dealer = $user->dealerProfile ?: new DealerProfile(['user_id' => $user->id]);
            $dealer->fill([
                'company_name' => $validated['dealerCompanyName'],
                'slug' => $dealer->slug ?: $this->uniqueDealerSlug($validated['dealerCompanyName']),
                'description' => $validated['dealerDescription'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'],
                'website' => $validated['dealerWebsite'] ?? null,
                'city' => $validated['city'] ?? null,
            ]);
            $dealer->save();
        }

        if ($originalEmail !== $validated['email']) {
            $user->sendEmailVerificationNotification();
        }

        session()->flash('status', 'Profil je uspešno sačuvan.');
    }

    public function removeFavorite(int $listingId): void
    {
        auth()->user()->favoriteListings()->detach($listingId);
    }

    public function deleteSavedSearch(int $searchId): void
    {
        auth()->user()->savedSearches()->whereKey($searchId)->delete();
    }

    public function toggleSavedSearchFlag(int $searchId, string $field): void
    {
        abort_unless(in_array($field, ['notify_new_matches', 'notify_price_drops'], true), 422);

        $search = auth()->user()->savedSearches()->findOrFail($searchId);
        $search->{$field} = ! $search->{$field};
        $search->save();
    }

    public function deleteListing(int $listingId): void
    {
        $listing = auth()->user()->listings()->findOrFail($listingId);
        $listing->delete();
    }

    public function markNotificationRead(string $notificationId): void
    {
        auth()->user()->notifications()->whereKey($notificationId)->update(['read_at' => now()]);
    }

    public function markAllNotificationsRead(): void
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
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
        return 'Moj nalog | AutoIQ';
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
        $user = auth()->user()->load([
            'dealerProfile',
            'listings.images',
            'listings.priceHistories',
            'favoriteListings.images',
            'favoriteListings.priceHistories',
            'savedSearches',
        ]);

        return $this->page(view('livewire.pages.account.dashboard-page', [
            'user' => $user,
            'notifications' => $user->notifications()->latest()->limit(20)->get(),
            'cities' => config('autoiq.cities'),
        ]));
    }
}
