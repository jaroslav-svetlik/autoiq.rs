<div x-data="catalogBrowser" class="catalog-page">
    <section class="catalog-hero {{ !empty($landingHighlights) ? 'catalog-hero-model' : '' }}">
        <img class="hero-image" src="{{ asset('images/alpine-drive.webp') }}" alt="" fetchpriority="high" width="2172" height="724">
        <div class="container-frame hero-content">
            <div class="hero-copy">
                <p class="hero-eyebrow">PRONAĐI. UPOREDI. VOZI.</p>
                <h1>{{ $pageHeading }}</h1>
                <p class="hero-intro">{{ $pageIntro }}</p>
                <div class="hero-benefits">
                    <div><span class="feature-icon"><x-icon name="shield" /></span><span><b>Pametnija kupovina</b><small>AutoIQ procena</small></span></div>
                    <div><span class="feature-icon"><x-icon name="tag" /></span><span><b>Uporedite ponude</b><small>Jasan pregled cena</small></span></div>
                    <div><span class="feature-icon"><x-icon name="users" /></span><span><b>Sve na jednom mestu</b><small>Vaša sledeća vožnja</small></span></div>
                </div>
            </div>
        </div>
    </section>

    <div class="container-frame">
        <form wire:submit="applyFilters" class="quick-search" id="pretraga">
            <label class="quick-field"><x-icon name="car" /><span><b>Marka</b><x-select wire:model.live="brand" aria-label="Marka automobila" variant="compact"><option value="">Sve marke</option>@foreach($brands as $item)<option value="{{ $item }}">{{ $item }}</option>@endforeach</x-select></span></label>
            <label class="quick-field"><x-icon name="layers" /><span><b>Model</b><x-select wire:model.live="model" aria-label="Model automobila" variant="compact"><option value="">Svi modeli</option>@foreach($models as $item)<option value="{{ $item }}">{{ $item }}</option>@endforeach</x-select></span></label>
            <details class="quick-field quick-range" wire:ignore.self>
                <summary><x-icon name="calendar" /><span><b>Godište</b><small>{{ $minYear ?: 'Od' }} – {{ $maxYear ?: 'Do' }}</small></span><x-icon name="chevron" /></summary>
                <div class="quick-popover"><label>Od<input type="number" min="1900" max="{{ date('Y') + 1 }}" wire:model.live.debounce.400ms="minYear" class="input-shell" aria-label="Godište od"></label><label>Do<input type="number" min="1900" max="{{ date('Y') + 1 }}" wire:model.live.debounce.400ms="maxYear" class="input-shell" aria-label="Godište do"></label></div>
            </details>
            <details class="quick-field quick-range" wire:ignore.self>
                <summary><x-icon name="tag" /><span><b>Cena (€)</b><small>{{ $minPrice ?: 'Od' }} – {{ $maxPrice ?: 'Do' }}</small></span><x-icon name="chevron" /></summary>
                <div class="quick-popover"><label>Od<input type="number" min="0" wire:model.live.debounce.400ms="minPrice" class="input-shell" aria-label="Cena od u evrima"></label><label>Do<input type="number" min="0" wire:model.live.debounce.400ms="maxPrice" class="input-shell" aria-label="Cena do u evrima"></label></div>
            </details>
            <button type="submit" class="btn-primary quick-submit" x-on:click="scrollToResults()"><x-icon name="search" />Pronađi automobile<x-icon name="arrow" /></button>
        </form>

        @if(!empty($landingHighlights))
            <section class="grid gap-4 md:grid-cols-3 mb-6">@foreach($landingHighlights as $highlight)<div class="panel-soft p-4 text-sm leading-7">{{ $highlight }}</div>@endforeach</section>
        @endif

        <div class="catalog-layout" id="rezultati">
            <button type="button" class="btn-secondary mobile-filter-toggle" x-on:click="filtersOpen = !filtersOpen" x-bind:aria-expanded="filtersOpen" aria-controls="catalog-filters"><x-icon name="filter" />Filteri i pretraga<x-icon name="chevron" /></button>
            <aside id="catalog-filters" class="catalog-filters" x-bind:class="filterPanelClass">
                <div class="panel filter-panel">
                    <div class="filter-heading"><h2><x-icon name="filter" />Filteri</h2><button type="button" wire:click="clearFilters">Obriši sve</button></div>
                    <label class="filter-search"><x-icon name="search" /><input type="search" wire:model.live.debounce.350ms="search" placeholder="Pretraži oglase…" aria-label="Pretraži oglase"></label>
                    <details class="filter-section" open id="marke">
                        <summary><x-icon name="car" />Marka<x-icon name="chevron" /></summary>
                        <div class="filter-options brand-options">
                            <label><input type="radio" wire:model.live="brand" value="" name="brand">Sve marke</label>
                            @foreach($brands as $item)
                                <label><input type="radio" wire:model.live="brand" value="{{ $item }}" name="brand"><span>{{ $item }} <small>({{ number_format($brandCounts[$item] ?? 0, 0, ',', '.') }})</small></span></label>
                            @endforeach
                        </div>
                    </details>
                    <details class="filter-section" @if($model) open @endif>
                        <summary><x-icon name="layers" />Model<x-icon name="chevron" /></summary>
                        <x-select wire:model.live="model" class="input-shell w-full" aria-label="Filter po modelu"><option value="">Svi modeli</option>@foreach($models as $item)<option value="{{ $item }}">{{ $item }}</option>@endforeach</x-select>
                    </details>
                    <details class="filter-section" open>
                        <summary><x-icon name="calendar" />Godište<x-icon name="chevron" /></summary>
                        <div class="filter-range"><input type="number" wire:model.live.debounce.400ms="minYear" class="input-shell" placeholder="Od" aria-label="Filter godište od" min="1900" max="{{ date('Y') + 1 }}"><input type="number" wire:model.live.debounce.400ms="maxYear" class="input-shell" placeholder="Do" aria-label="Filter godište do" min="1900" max="{{ date('Y') + 1 }}"></div>
                    </details>
                    <details class="filter-section" open>
                        <summary><x-icon name="tag" />Cena (€)<x-icon name="chevron" /></summary>
                        <div class="filter-range"><input type="number" wire:model.live.debounce.400ms="minPrice" class="input-shell" placeholder="Od" aria-label="Filter cena od" min="0"><input type="number" wire:model.live.debounce.400ms="maxPrice" class="input-shell" placeholder="Do" aria-label="Filter cena do" min="0"></div>
                    </details>
                    <details class="filter-section" open>
                        <summary><x-icon name="gauge" />Kilometraža<x-icon name="chevron" /></summary>
                        <div class="filter-range"><input type="number" wire:model.live.debounce.400ms="minMileage" class="input-shell" placeholder="Od" aria-label="Kilometraža od" min="0"><input type="number" wire:model.live.debounce.400ms="maxMileage" class="input-shell" placeholder="Do" aria-label="Kilometraža do" min="0"></div>
                    </details>
                    <details class="filter-section" open>
                        <summary><x-icon name="fuel" />Gorivo<x-icon name="chevron" /></summary>
                        <div class="filter-options">
                            <label><input type="radio" wire:model.live="fuelType" value="" name="fuel">Sva goriva</label>
                            @foreach($fuelTypes as $value => $label)<label><input type="radio" wire:model.live="fuelType" value="{{ $value }}" name="fuel"><span>{{ $label }} <small>({{ number_format($fuelCounts[$value] ?? 0, 0, ',', '.') }})</small></span></label>@endforeach
                        </div>
                    </details>
                    <details class="filter-section" @if($transmission) open @endif>
                        <summary><x-icon name="gear" />Menjač<x-icon name="chevron" /></summary>
                        <x-select wire:model.live="transmission" class="input-shell w-full" aria-label="Menjač"><option value="">Svi menjači</option>@foreach($transmissionTypes as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</x-select>
                    </details>
                    <details class="filter-section" @if($city) open @endif>
                        <summary><x-icon name="pin" />Lokacija<x-icon name="chevron" /></summary>
                        <x-select wire:model.live="city" class="input-shell w-full" aria-label="Lokacija"><option value="">Cela Srbija</option>@foreach($cities as $option)<option value="{{ $option }}">{{ $option }}</option>@endforeach</x-select>
                    </details>
                    <details class="filter-section" @if(count($equipment)) open @endif>
                        <summary><x-icon name="filter" />Oprema @if(count($equipment)) ({{ count($equipment) }}) @endif<x-icon name="chevron" /></summary>
                        <p class="text-xs text-muted mb-3">Oglasi sa svim odabranim stavkama.</p>
                        @if(count($equipment))<p class="text-xs text-brand mb-3">{{ count($equipment) }} odabrane stavke</p>@endif
                        @foreach($equipmentCatalog as $group)
                            <details class="equipment-group"><summary>{{ $group['label'] }}<x-icon name="chevron" /></summary><div class="filter-options">@foreach($group['options'] as $option)<label><input type="checkbox" value="{{ $option['key'] }}" wire:model.live="equipment"><span>{{ $option['label'] }}</span></label>@endforeach</div></details>
                        @endforeach
                    </details>
                    <button type="button" wire:click="applyFilters" x-on:click="closeFilters()" class="btn-primary filter-submit"><x-icon name="search" /><span>Prikaži rezultate<small>({{ number_format($listings->total(), 0, ',', '.') }} oglasa)</small></span></button>
                </div>
                @auth
                    <div class="panel save-search-panel"><h3>Sačuvaj pretragu</h3><p>Obaveštenja o novim oglasima i promenama cena.</p><label class="sr-only" for="save-search-name">Naziv pretrage</label><input id="save-search-name" type="text" wire:model="saveSearchName" class="input-shell w-full" placeholder="Naziv pretrage"><button type="button" wire:click="saveCurrentSearch" class="btn-secondary w-full">Sačuvaj i uključi alarme</button></div>
                @endauth
            </aside>

            <section class="catalog-results" aria-label="Rezultati pretrage">
                <div class="results-toolbar">
                    <div aria-live="polite"><h2>{{ number_format($listings->total(), 0, ',', '.') }} oglasa</h2><p>{{ $search || $brand || $model || $city || $fuelType || $transmission || $minPrice || $maxPrice || $minYear || $maxYear || $minMileage || $maxMileage || count($equipment) ? 'Oglasi prema odabranim filterima' : 'Prikazani su svi dostupni oglasi' }}</p></div>
                    <div class="results-controls"><label for="listing-sort">Sortiraj po</label><x-select id="listing-sort" wire:model.live="sort" class="input-shell"><option value="newest">Najnoviji</option><option value="price_asc">Cena rastuće</option><option value="price_desc">Cena opadajuće</option><option value="best">Najbolja ponuda</option><option value="relevance">Relevantnost</option></x-select>
                        <div class="view-switch" aria-label="Prikaz oglasa"><button type="button" x-on:click="listMode = false" x-bind:class="gridButtonClass" x-bind:aria-pressed="gridMode" aria-label="Prikaz u mreži"><x-icon name="grid" /><span>Mreža</span></button><button type="button" x-on:click="listMode = true" x-bind:class="listButtonClass" x-bind:aria-pressed="listMode" aria-label="Prikaz u listi"><x-icon name="list" /><span>Lista</span></button></div>
                    </div>
                </div>
                @if($selectedEquipmentLabels->isNotEmpty())<div class="flex flex-wrap gap-2 mb-4">@foreach($selectedEquipmentLabels as $label)<span class="chip">{{ $label }}</span>@endforeach</div>@endif
                <div wire:loading.delay role="status" class="text-brand text-sm mb-3">Osvežavanje oglasa…</div>
                @if($listings->count())
                    <div class="listing-grid" x-bind:class="resultLayout" wire:loading.class="opacity-60">
                        @foreach($listings as $listing)<div wire:key="listing-{{ $listing->id }}"><x-listing-card :listing="$listing" :favorite="in_array($listing->id, $favoriteIds, true)" favouritable /></div>@endforeach
                    </div>
                    <div class="catalog-pagination">{{ $listings->links(data: ['scrollTo' => '#rezultati']) }}@unless($listings->hasPages())<p>Prikazano {{ $listings->count() }} od {{ number_format($listings->total(), 0, ',', '.') }} oglasa</p>@endunless</div>
                @else
                    <div class="panel empty-results"><span class="feature-icon"><x-icon name="search" /></span><h2>Nema rezultata</h2><p>Probajte širi budžet, drugačiji grad ili uklonite deo filtera.</p><button type="button" wire:click="clearFilters" class="btn-primary">Obriši filtere</button></div>
                @endif
                <x-drive-banner />
            </section>
        </div>
    </div>
</div>
