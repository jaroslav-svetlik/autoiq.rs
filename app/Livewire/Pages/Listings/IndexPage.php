<?php

namespace App\Livewire\Pages\Listings;

use App\Livewire\Pages\PageComponent;
use App\Models\Listing;
use App\Models\SavedSearch;
use App\Services\ListingSearchService;
use App\Services\MarketInsightsService;
use App\Support\Seo\VehicleLandingPages;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class IndexPage extends PageComponent
{
    use WithPagination;

    #[Url(as: 'search')]
    public string $search = '';

    #[Url(as: 'brand')]
    public ?string $brand = null;

    #[Url(as: 'model')]
    public ?string $model = null;

    #[Url(as: 'city')]
    public ?string $city = null;

    #[Url(as: 'fuel_type')]
    public ?string $fuelType = null;

    #[Url(as: 'transmission')]
    public ?string $transmission = null;

    #[Url(as: 'min_price')]
    public ?string $minPrice = null;

    #[Url(as: 'max_price')]
    public ?string $maxPrice = null;

    #[Url(as: 'min_year')]
    public ?string $minYear = null;

    #[Url(as: 'max_mileage')]
    public ?string $maxMileage = null;

    #[Url(as: 'equipment')]
    public array $equipment = [];

    #[Url(as: 'sort', except: 'newest')]
    public string $sort = 'newest';

    public ?string $saveSearchName = null;

    public ?string $landingBrand = null;

    public ?string $landingModel = null;

    protected ListingSearchService $searchService;

    protected MarketInsightsService $marketInsights;

    public function boot(ListingSearchService $searchService, MarketInsightsService $marketInsights): void
    {
        $this->searchService = $searchService;
        $this->marketInsights = $marketInsights;
    }

    public function mount(?string $brandSlug = null, ?string $modelSlug = null): void
    {
        if ($brandSlug && $modelSlug) {
            $landingPage = VehicleLandingPages::fromSlugs($brandSlug, $modelSlug);

            abort_unless($landingPage, 404);

            $this->landingBrand = $landingPage['brand'];
            $this->landingModel = $landingPage['model'];
            $this->brand = $landingPage['brand'];
            $this->model = $landingPage['model'];
            $this->sort = 'best';
        }

        $this->equipment = $this->sanitizeEquipment($this->equipment);
    }

    public function updated(string $property): void
    {
        if ($property === 'saveSearchName') {
            return;
        }

        if ($property === 'brand' && $this->brand && ! in_array($this->model, $this->searchService->models($this->brand), true)) {
            $this->model = null;
        }

        if ($property === 'equipment' || str_starts_with($property, 'equipment.')) {
            $this->equipment = $this->sanitizeEquipment($this->equipment);
        }

        $this->resetPage();
        $this->recordSearch();
    }

    public function clearFilters(): void
    {
        if ($this->modelLandingPage()) {
            $this->redirectRoute('listings.index', navigate: true);

            return;
        }

        $this->reset([
            'search',
            'brand',
            'model',
            'city',
            'fuelType',
            'transmission',
            'minPrice',
            'maxPrice',
            'minYear',
            'maxMileage',
            'equipment',
            'saveSearchName',
        ]);

        $this->sort = 'newest';
        $this->resetPage();
    }

    public function toggleFavorite(int $listingId): void
    {
        abort_unless(auth()->check(), 403);

        $user = auth()->user();

        if ($user->hasFavorited($listingId)) {
            $user->favoriteListings()->detach($listingId);
        } else {
            $user->favoriteListings()->syncWithoutDetaching([$listingId]);
        }
    }

    public function saveCurrentSearch(): void
    {
        abort_unless(auth()->check(), 403);

        $filters = $this->filters();
        $name = trim((string) ($this->saveSearchName ?: implode(' ', array_filter([
            $this->brand,
            $this->model,
            $this->search !== '' ? $this->search : null,
        ]))));

        SavedSearch::query()->create([
            'user_id' => auth()->id(),
            'name' => $name !== '' ? $name : 'Moja pretraga',
            'query' => $this->search ?: null,
            'filters' => $filters,
        ]);

        $this->saveSearchName = null;

        session()->flash('status', 'Pretraga je sačuvana i alarmi su aktivni.');
    }

    protected function filters(): array
    {
        return [
            'search' => $this->search,
            'brand' => $this->brand,
            'model' => $this->model,
            'city' => $this->city,
            'fuel_type' => $this->fuelType,
            'transmission' => $this->transmission,
            'min_price' => $this->minPrice,
            'max_price' => $this->maxPrice,
            'min_year' => $this->minYear,
            'max_mileage' => $this->maxMileage,
            'equipment' => $this->equipment,
            'sort' => $this->sort,
        ];
    }

    protected function recordSearch(): void
    {
        $signature = md5(json_encode($this->filters()));

        if (session('search_signature') === $signature) {
            return;
        }

        session(['search_signature' => $signature]);

        $this->marketInsights->recordSearch(auth()->user(), $this->filters());
    }

    protected function title(): string
    {
        if ($landingPage = $this->modelLandingPage()) {
            return VehicleLandingPages::title($landingPage);
        }

        $headline = trim(implode(' ', array_filter([$this->brand, $this->model])));

        return $headline !== ''
            ? "{$headline} oglasi i analiza | AutoIQ"
            : 'Auto oglasi i analiza tržišta | AutoIQ';
    }

    protected function meta(): array
    {
        $landingPage = $this->modelLandingPage();

        return [
            ...parent::meta(),
            'description' => $landingPage
                ? VehicleLandingPages::description($landingPage)
                : 'Pretražite auto oglase u Srbiji uz AutoIQ procenu, analizu cena i filtere koji odmah osvežavaju rezultate.',
            'canonical' => $landingPage
                ? route('listings.model', VehicleLandingPages::routeParameters($landingPage['brand'], $landingPage['model']))
                : route('listings.index'),
            'robots' => $this->shouldIndexCurrentView() ? 'index,follow' : 'noindex,follow',
        ];
    }

    protected function shouldIndexCurrentView(): bool
    {
        if ($this->modelLandingPage()) {
            return $this->isCanonicalModelLandingView();
        }

        if (request()->query() !== []) {
            return false;
        }

        return ! collect($this->filters())
            ->reject(fn (mixed $value, string $key) => $key === 'sort' && $value === 'newest')
            ->contains(fn (mixed $value) => $this->hasFilterValue($value));
    }

    protected function isCanonicalModelLandingView(): bool
    {
        $landingPage = $this->modelLandingPage();

        if (! $landingPage || request()->query() !== []) {
            return false;
        }

        return $this->brand === $landingPage['brand']
            && $this->model === $landingPage['model']
            && $this->sort === 'best'
            && ! collect($this->filters())
                ->except(['brand', 'model', 'sort'])
                ->contains(fn (mixed $value) => $this->hasFilterValue($value));
    }

    protected function hasFilterValue(mixed $value): bool
    {
        if (is_array($value)) {
            return $value !== [];
        }

        return $value !== null && $value !== '';
    }

    protected function sanitizeEquipment(array $equipment): array
    {
        $allowed = array_keys(Listing::equipmentKeyMap());

        return collect($equipment)
            ->map(fn (mixed $key) => (string) $key)
            ->filter(fn (string $key) => in_array($key, $allowed, true))
            ->unique()
            ->values()
            ->all();
    }

    protected function jsonLd(): array
    {
        $landingPage = $this->modelLandingPage();

        if (! $landingPage) {
            return [];
        }

        $url = route('listings.model', VehicleLandingPages::routeParameters($landingPage['brand'], $landingPage['model']));

        return [[
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => VehicleLandingPages::heading($landingPage),
            'description' => VehicleLandingPages::description($landingPage),
            'url' => $url,
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => 'AutoIQ',
                'url' => route('home'),
            ],
            'about' => [
                '@type' => 'Car',
                'brand' => $landingPage['brand'],
                'model' => $landingPage['model'],
            ],
        ], [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'AutoIQ',
                    'item' => route('home'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Oglasi',
                    'item' => route('listings.index'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => "{$landingPage['brand']} {$landingPage['model']}",
                    'item' => $url,
                ],
            ],
        ]];
    }

    /**
     * @return array{brand: string, model: string, segment: string, intent: string, checks: array<int, string>}|null
     */
    protected function modelLandingPage(): ?array
    {
        if (! $this->landingBrand || ! $this->landingModel) {
            return null;
        }

        return VehicleLandingPages::for($this->landingBrand, $this->landingModel);
    }

    public function render(): View
    {
        $equipmentLabelMap = Listing::equipmentKeyMap();
        $landingPage = $this->modelLandingPage();

        return $this->page(view('livewire.pages.listings.index-page', [
            'listings' => $this->searchService->search($this->filters()),
            'brands' => $this->searchService->brands(),
            'models' => $this->searchService->models($this->brand),
            'cities' => config('autoiq.cities'),
            'fuelTypes' => config('autoiq.fuel_types'),
            'transmissionTypes' => config('autoiq.transmission_types'),
            'equipmentCatalog' => Listing::equipmentCatalog(),
            'selectedEquipmentLabels' => collect($this->equipment)->map(fn (string $key) => $equipmentLabelMap[$key] ?? $key)->values(),
            'pageHeading' => $landingPage ? VehicleLandingPages::heading($landingPage) : 'Auto oglasi i analiza tržišta',
            'pageIntro' => $landingPage
                ? VehicleLandingPages::intro($landingPage)
                : 'Filtrirajte po budžetu, godištu, kilometraži, lokaciji i opremi, pa odmah vidite gde cena odstupa od proseka.',
            'landingHighlights' => $landingPage ? VehicleLandingPages::highlights($landingPage) : [],
        ]));
    }
}
