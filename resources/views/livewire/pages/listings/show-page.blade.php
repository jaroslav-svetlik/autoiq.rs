<div class="space-y-10">
    @php
        $equipmentGroups = $listing->selectedEquipmentGroups();
    @endphp

    <div class="grid items-start gap-8 xl:grid-cols-[minmax(0,1fr)_340px]">
        <section class="space-y-6">
            @php
                $galleryImages = $listing->images
                    ->map(fn ($image) => [
                        'url' => $image->url(),
                        'alt' => $image->alt_text ?: $listing->title,
                    ])
                    ->values();

                if ($galleryImages->isEmpty()) {
                    $galleryImages = collect([[
                        'url' => $listing->primaryImageUrl(),
                        'alt' => $listing->title,
                    ]]);
                }
            @endphp

            <div
                class="panel overflow-hidden"
                data-gallery-carousel
                x-data="{
                    active: 0,
                    lightboxOpen: false,
                    zoom: 1,
                    images: @js($galleryImages->all()),
                    previous() {
                        this.active = (this.active - 1 + this.images.length) % this.images.length;
                        this.resetZoom();
                    },
                    next() {
                        this.active = (this.active + 1) % this.images.length;
                        this.resetZoom();
                    },
                    setActive(index) {
                        this.active = index;
                        this.resetZoom();
                    },
                    openLightbox(index = this.active) {
                        this.active = index;
                        this.lightboxOpen = true;
                        this.zoom = 1;
                        document.body.classList.add('overflow-hidden');
                    },
                    closeLightbox() {
                        this.lightboxOpen = false;
                        this.resetZoom();
                        document.body.classList.remove('overflow-hidden');
                    },
                    zoomIn() {
                        this.zoom = Math.min(this.zoom + 0.25, 3);
                    },
                    zoomOut() {
                        this.zoom = Math.max(this.zoom - 0.25, 1);
                    },
                    resetZoom() {
                        this.zoom = 1;
                    },
                    handleWheel(event) {
                        if (! this.lightboxOpen) {
                            return;
                        }

                        event.preventDefault();

                        if (event.deltaY < 0) {
                            this.zoomIn();
                        } else {
                            this.zoomOut();
                        }
                    },
                }"
                x-on:keydown.left.prevent="previous()"
                x-on:keydown.right.prevent="next()"
                x-on:keydown.left.window.prevent="if (lightboxOpen) previous()"
                x-on:keydown.right.window.prevent="if (lightboxOpen) next()"
                x-on:keydown.escape.window="closeLightbox()"
                x-on:livewire:navigating.window="closeLightbox()"
                tabindex="0"
            >
                <div class="relative aspect-[16/10] overflow-hidden bg-slate-900">
                    <button
                        type="button"
                        x-on:click="openLightbox()"
                        class="block h-full w-full text-left"
                        aria-label="Otvori fotografiju u uvećanom prikazu"
                    >
                        <img
                            src="{{ $galleryImages->first()['url'] }}"
                            alt="{{ $galleryImages->first()['alt'] }}"
                            x-bind:src="images[active].url"
                            x-bind:alt="images[active].alt"
                            class="h-full w-full object-cover transition duration-300 hover:scale-[1.02]"
                        >
                    </button>

                    <div class="pointer-events-none absolute inset-x-0 top-0 flex items-start justify-between gap-4 p-4">
                        <div class="rounded-full border border-white/10 bg-slate-950/70 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-white/80">
                            Galerija vozila
                        </div>
                        <div class="rounded-full border border-white/10 bg-slate-950/70 px-4 py-2 text-sm text-white">
                            <span x-text="active + 1"></span> / {{ $galleryImages->count() }}
                        </div>
                    </div>

                    @if($galleryImages->count() > 1)
                        <div class="absolute inset-x-0 top-1/2 flex -translate-y-1/2 items-center justify-between px-4">
                            <button
                                type="button"
                                x-on:click="previous()"
                                class="flex h-12 w-12 items-center justify-center rounded-full border border-white/10 bg-slate-950/75 text-xl text-white transition hover:border-cyan-400/40 hover:text-cyan-200"
                                aria-label="Prethodna fotografija"
                            >
                                ‹
                            </button>
                            <button
                                type="button"
                                x-on:click="next()"
                                class="flex h-12 w-12 items-center justify-center rounded-full border border-white/10 bg-slate-950/75 text-xl text-white transition hover:border-cyan-400/40 hover:text-cyan-200"
                                aria-label="Sledeća fotografija"
                            >
                                ›
                            </button>
                        </div>
                    @endif

                    <div class="pointer-events-none absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-transparent p-5">
                        <div class="text-sm text-slate-300">Kliknite na fotografiju za lightbox i zum, ili koristite strelice na tastaturi.</div>
                    </div>
                </div>

                @if($galleryImages->count() > 1)
                    <div class="overflow-x-auto p-3">
                        <div class="flex gap-3">
                            @foreach($galleryImages as $index => $image)
                                <button
                                    type="button"
                                    x-on:click="setActive({{ $index }})"
                                    x-bind:class="active === {{ $index }} ? 'border-cyan-400/70 ring-2 ring-cyan-400/20' : 'border-white/10 opacity-70 hover:opacity-100'"
                                    class="group relative h-24 w-28 shrink-0 overflow-hidden rounded-2xl border bg-slate-950 transition"
                                    aria-label="Prikaži fotografiju {{ $index + 1 }}"
                                >
                                    <img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/65 via-transparent to-transparent"></div>
                                    <div class="absolute bottom-2 left-2 rounded-full bg-slate-950/75 px-2 py-1 text-[11px] font-semibold text-white">{{ $index + 1 }}</div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div
                    x-cloak
                    x-show="lightboxOpen"
                    x-transition.opacity
                    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/96 p-4 sm:p-6"
                    role="dialog"
                    aria-modal="true"
                    aria-label="Galerija fotografija vozila"
                >
                    <button
                        type="button"
                        x-on:click="closeLightbox()"
                        class="absolute inset-0 cursor-zoom-out"
                        aria-label="Zatvori lightbox"
                    ></button>

                    <div class="relative z-10 flex h-full w-full max-w-7xl flex-col gap-4">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-white">
                                <span x-text="active + 1"></span> / {{ $galleryImages->count() }}
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <button
                                    type="button"
                                    x-on:click="zoomOut()"
                                    class="btn-secondary"
                                    aria-label="Umanji fotografiju"
                                >
                                    -
                                </button>
                                <button
                                    type="button"
                                    x-on:click="resetZoom()"
                                    class="btn-secondary"
                                    aria-label="Vrati podrazumevani zum"
                                >
                                    <span x-text="Math.round(zoom * 100) + '%'"></span>
                                </button>
                                <button
                                    type="button"
                                    x-on:click="zoomIn()"
                                    class="btn-secondary"
                                    aria-label="Uvećaj fotografiju"
                                >
                                    +
                                </button>
                                <button
                                    type="button"
                                    x-on:click="closeLightbox()"
                                    class="btn-primary"
                                    aria-label="Zatvori uvećani prikaz"
                                >
                                    Zatvori
                                </button>
                            </div>
                        </div>

                        <div class="grid min-h-0 flex-1 gap-4 lg:grid-cols-[88px_1fr]">
                            @if($galleryImages->count() > 1)
                                <div class="order-2 flex gap-3 overflow-x-auto lg:order-1 lg:flex-col">
                                    @foreach($galleryImages as $index => $image)
                                        <button
                                            type="button"
                                            x-on:click="setActive({{ $index }})"
                                            x-bind:class="active === {{ $index }} ? 'border-cyan-400/70 ring-2 ring-cyan-400/25' : 'border-white/10 opacity-70 hover:opacity-100'"
                                            class="relative h-20 w-20 shrink-0 overflow-hidden rounded-2xl border bg-slate-900 transition"
                                            aria-label="Otvori fotografiju {{ $index + 1 }} u lightboxu"
                                        >
                                            <img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}" class="h-full w-full object-cover">
                                        </button>
                                    @endforeach
                                </div>
                            @endif

                            <div
                                class="order-1 flex min-h-[55vh] flex-1 items-center justify-center overflow-hidden rounded-[2rem] border border-white/10 bg-black/40 lg:order-2"
                                x-on:wheel.prevent="handleWheel($event)"
                            >
                                @if($galleryImages->count() > 1)
                                    <button
                                        type="button"
                                        x-on:click.stop="previous()"
                                        class="absolute left-6 top-1/2 z-10 hidden h-14 w-14 -translate-y-1/2 items-center justify-center rounded-full border border-white/10 bg-slate-950/80 text-2xl text-white transition hover:border-cyan-400/40 hover:text-cyan-200 md:flex"
                                        aria-label="Prethodna fotografija u lightboxu"
                                    >
                                        ‹
                                    </button>
                                    <button
                                        type="button"
                                        x-on:click.stop="next()"
                                        class="absolute right-6 top-1/2 z-10 hidden h-14 w-14 -translate-y-1/2 items-center justify-center rounded-full border border-white/10 bg-slate-950/80 text-2xl text-white transition hover:border-cyan-400/40 hover:text-cyan-200 md:flex"
                                        aria-label="Sledeća fotografija u lightboxu"
                                    >
                                        ›
                                    </button>
                                @endif

                                <img
                                    src="{{ $galleryImages->first()['url'] }}"
                                    alt="{{ $galleryImages->first()['alt'] }}"
                                    x-bind:src="images[active].url"
                                    x-bind:alt="images[active].alt"
                                    x-bind:style="`transform: scale(${zoom});`"
                                    class="max-h-full max-w-full object-contain transition duration-200 ease-out"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel p-6 sm:p-8">
                <div class="flex flex-col gap-6">
                    <div class="flex flex-col gap-6 xl:flex-row xl:items-start xl:justify-between">
                        <div class="min-w-0">
                            <div class="data-kicker">Pregled vozila</div>
                            <div class="mt-3 text-sm font-semibold uppercase tracking-[0.24em] text-cyan-300/80">{{ $listing->city }} · {{ $listing->seller_type?->label() }}</div>
                            <h1 class="font-display mt-4 text-4xl font-bold text-white sm:text-5xl">{{ $listing->title }}</h1>
                            <div class="mt-5 flex flex-wrap items-center gap-3 text-sm text-slate-300">
                                <span class="chip">{{ $listing->year }}</span>
                                <span class="chip">{{ number_format($listing->mileage, 0, ',', '.') }} km</span>
                                <span class="chip">{{ $listing->fuel_type?->label() }}</span>
                                <span class="chip">{{ $listing->transmission?->label() }}</span>
                            </div>
                        </div>

                        <x-listing-purchase-card :listing="$listing" class="panel-soft p-5 xl:hidden" />
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <div class="stat-card">
                            <div class="metric-value">{{ $listing->autoiq_score }}</div>
                            <div class="metric-label">AutoIQ score</div>
                        </div>
                        <div class="stat-card">
                            <div class="metric-value">{{ number_format($listing->market_average_price ?: $listing->price, 0, ',', '.') }} €</div>
                            <div class="metric-label">Prosek tržišta</div>
                        </div>
                        <div class="stat-card">
                            <div class="metric-value">{{ number_format($listing->views_count, 0, ',', '.') }}</div>
                            <div class="metric-label">Pregleda oglasa</div>
                        </div>
                        <div class="stat-card">
                            <div class="metric-value">{{ $listing->priceHistories->count() }}</div>
                            <div class="metric-label">Promena cene</div>
                        </div>
                    </div>

                    <div class="grid gap-6">
                        <div class="panel-soft p-6">
                            <div class="data-kicker">Opis vozila</div>
                            <h2 class="font-display mt-2 text-2xl font-bold text-white">Najvažnije informacije</h2>
                            <div class="mt-4 whitespace-pre-line text-sm leading-8 text-slate-300">{{ $listing->description }}</div>
                        </div>

                        @if($equipmentGroups->isNotEmpty())
                            <div class="panel-soft p-6">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                                    <div>
                                        <div class="data-kicker">Oprema</div>
                                        <h2 class="font-display mt-2 text-2xl font-bold text-white">Izdvojene stavke</h2>
                                    </div>
                                    <div class="text-sm text-slate-400">{{ $listing->equipmentKeys()->count() }} stavki opreme</div>
                                </div>

                                <div class="mt-5 grid gap-4 md:grid-cols-2">
                                    @foreach($equipmentGroups as $group)
                                        <div class="rounded-2xl border border-white/8 bg-slate-950/35 p-5">
                                            <div class="data-kicker">{{ $group['label'] }}</div>
                                            <div class="mt-4 flex flex-wrap gap-2">
                                                @foreach($group['options'] as $option)
                                                    <span class="chip">{{ $option['label'] }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="panel p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="data-kicker">Istorija cene</div>
                        <h2 class="font-display mt-2 text-3xl font-bold text-white">Mali trend grafikon</h2>
                    </div>
                    <div class="text-sm text-slate-400">Poslednje zabeležene promene</div>
                </div>

                <div class="mt-6 panel-soft p-4">
                    <svg viewBox="0 0 112 28" class="h-16 w-full">
                        <polyline
                            fill="none"
                            stroke="rgba(56,189,248,0.85)"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            points="{{ $listing->sparklinePoints(112, 28) }}"
                        />
                    </svg>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    @foreach($listing->priceHistories->sortByDesc('recorded_at')->take(6) as $history)
                        <div class="panel-soft flex items-center justify-between p-4 text-sm">
                            <div>
                                <div class="font-semibold text-white">{{ number_format($history->price, 0, ',', '.') }} €</div>
                                <div class="text-slate-400">{{ $history->note ?: 'Promena cene' }}</div>
                            </div>
                            <div class="text-slate-500">{{ $history->recorded_at->format('d.m.Y.') }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <aside class="space-y-6 xl:sticky xl:top-28 xl:self-start">
            <x-listing-purchase-card :listing="$listing" class="panel hidden p-6 xl:block" />

            @if($listing->dealerProfile)
                <div class="panel p-6">
                    <div class="data-kicker">Profil prodavca</div>
                    <div class="mt-4 flex items-center gap-4">
                        <img src="{{ $listing->dealerProfile->logoUrl() }}" alt="{{ $listing->dealerProfile->company_name }}" class="h-16 w-16 rounded-2xl object-cover">
                        <div>
                            <a href="{{ route('dealers.show', $listing->dealerProfile) }}" wire:navigate class="font-display text-2xl font-bold text-white hover:text-amber-300">{{ $listing->dealerProfile->company_name }}</a>
                            <p class="text-sm text-slate-400">{{ $listing->dealerProfile->city }}</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3 text-sm text-slate-300">
                        @if($listing->dealerProfile->phone)
                            <div>Telefon: {{ $listing->dealerProfile->phone }}</div>
                        @endif
                        @if($listing->dealerProfile->email)
                            <div>Email: {{ $listing->dealerProfile->email }}</div>
                        @endif
                        @if($listing->dealerProfile->website)
                            <div>Sajt: <a href="{{ $listing->dealerProfile->website }}" target="_blank" rel="noreferrer" class="text-cyan-300 hover:text-cyan-200">{{ $listing->dealerProfile->website }}</a></div>
                        @endif
                    </div>
                </div>
            @endif

            <div class="panel p-6">
                <div class="data-kicker">Tržišni signal</div>
                <h2 class="font-display mt-2 text-3xl font-bold text-white">{{ $listing->scoreLabel() }}</h2>
                <p class="mt-4 text-sm leading-7 text-slate-300">
                    Cena oglasa je {{ $listing->marketDifferenceLabel() }} u odnosu na prosečnu vrednost za {{ $listing->brand }} {{ $listing->model }} {{ $listing->year }}.
                </p>
            </div>
        </aside>
    </div>

    <section class="space-y-6">
        <div class="flex items-end justify-between gap-4">
            <div>
                <div class="data-kicker">Slični oglasi</div>
                <h2 class="section-title mt-2">Još ponuda za isti model</h2>
            </div>
            <a href="{{ route('listings.index', ['brand' => $listing->brand, 'model' => $listing->model]) }}" wire:navigate class="btn-secondary">Vidi sve</a>
        </div>

        <div class="grid gap-6 lg:grid-cols-4">
            @forelse($similarListings as $item)
                <x-listing-card :listing="$item" />
            @empty
                <div class="panel p-8 text-slate-300 lg:col-span-4">Nema dovoljno sličnih oglasa za preporuku.</div>
            @endforelse
        </div>
    </section>
</div>
