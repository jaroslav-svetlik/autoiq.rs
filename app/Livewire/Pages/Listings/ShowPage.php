<?php

namespace App\Livewire\Pages\Listings;

use App\Enums\ListingStatus;
use App\Livewire\Pages\PageComponent;
use App\Models\Listing;
use Illuminate\Contracts\View\View;

class ShowPage extends PageComponent
{
    public Listing $listing;

    public function mount(Listing $listing): void
    {
        if (
            $listing->status !== ListingStatus::Published
            && auth()->id() !== $listing->user_id
            && ! auth()->user()?->isAdmin()
        ) {
            abort(404);
        }

        $this->listing = $listing->load([
            'images',
            'priceHistories',
            'equipmentItems',
            'dealerProfile',
            'user',
        ]);

        $this->recordView();
    }

    public function toggleFavorite(): void
    {
        abort_unless(auth()->check(), 403);

        $user = auth()->user();

        if ($user->hasFavorited($this->listing)) {
            $user->favoriteListings()->detach($this->listing->id);
        } else {
            $user->favoriteListings()->syncWithoutDetaching([$this->listing->id]);
        }
    }

    protected function recordView(): void
    {
        $key = 'listing-viewed-'.$this->listing->id;

        if (session()->has($key)) {
            return;
        }

        session([$key => now()->timestamp]);
        $this->listing->increment('views_count');
        $this->listing->refresh();
    }

    protected function title(): string
    {
        return "{$this->listing->brand} {$this->listing->model} {$this->listing->year} | AutoIQ";
    }

    protected function meta(): array
    {
        return [
            ...parent::meta(),
            'description' => str($this->listing->description)->limit(155)->toString(),
            'canonical' => route('listings.show', $this->listing),
            'type' => 'article',
            'image' => $this->listing->primaryImageUrl(),
        ];
    }

    protected function jsonLd(): array
    {
        return [[
            '@context' => 'https://schema.org',
            '@type' => 'Vehicle',
            'name' => $this->listing->title,
            'brand' => [
                '@type' => 'Brand',
                'name' => $this->listing->brand,
            ],
            'model' => $this->listing->model,
            'vehicleModelDate' => (string) $this->listing->year,
            'mileageFromOdometer' => [
                '@type' => 'QuantitativeValue',
                'value' => $this->listing->mileage,
                'unitCode' => 'KMT',
            ],
            'fuelType' => $this->listing->fuel_type?->label(),
            'vehicleTransmission' => $this->listing->transmission?->label(),
            'vehicleConfiguration' => $this->listing->equipmentLabels()->implode(', '),
            'offers' => [
                '@type' => 'Offer',
                'priceCurrency' => 'EUR',
                'price' => $this->listing->price,
                'availability' => 'https://schema.org/InStock',
                'url' => route('listings.show', $this->listing),
            ],
            'description' => $this->listing->description,
            'image' => $this->listing->images->map->url()->all(),
        ]];
    }

    public function render(): View
    {
        $similarListings = Listing::query()
            ->published()
            ->whereKeyNot($this->listing->id)
            ->where('brand', $this->listing->brand)
            ->where('model', $this->listing->model)
            ->with(['images', 'priceHistories'])
            ->limit(4)
            ->get();

        return $this->page(view('livewire.pages.listings.show-page', [
            'similarListings' => $similarListings,
        ]));
    }
}
