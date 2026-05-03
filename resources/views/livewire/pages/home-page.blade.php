<div class="space-y-16">
    <section class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
        <div class="space-y-8">
            <span class="chip">Analiziraj tržište · Srbija</span>

            <div class="space-y-5">
                <h1 class="font-display max-w-4xl text-5xl font-bold leading-none tracking-tight text-white sm:text-6xl lg:text-7xl">
                    Pametniji auto oglasi. Manje nagađanja. Više sigurnosti u cenu.
                </h1>
                <p class="max-w-2xl text-lg leading-8 text-slate-300">
                    AutoIQ kombinuje oglase, tržišne proseke i AutoIQ procenu da biste odmah videli da li je automobil dobra kupovina, realna cena ili precenjen.
                </p>
            </div>

            <form wire:submit="search" class="panel flex flex-col gap-4 p-4 sm:flex-row sm:items-center">
                <div class="flex-1">
                    <label for="heroSearch" class="mb-2 block text-sm font-semibold text-slate-200">Šta tražite?</label>
                    <input
                        id="heroSearch"
                        type="text"
                        wire:model.live.debounce.300ms="heroSearch"
                        class="input-shell w-full"
                        placeholder="BMW 320d, Golf 7, SUV do 10.000 €..."
                    >
                </div>
                <button type="submit" class="btn-primary mt-6 sm:mt-7">Analiziraj tržište</button>
            </form>

            <div class="grid gap-4 sm:grid-cols-3">
                <div class="stat-card">
                    <div class="metric-value">{{ number_format(collect($insights['bestDeals'] ?? [])->count(), 0, ',', '.') }}+</div>
                    <div class="metric-label">Najboljih ponuda izdvojeno po AutoIQ proceni</div>
                </div>
                <div class="stat-card">
                    <div class="metric-value">{{ number_format(collect($insights['fallingPrices'] ?? [])->count(), 0, ',', '.') }}</div>
                    <div class="metric-label">Vozila sa svežim padom cene u fokusu</div>
                </div>
                <div class="stat-card">
                    <div class="metric-value">{{ number_format(collect($insights['popularModels'] ?? [])->count(), 0, ',', '.') }}</div>
                    <div class="metric-label">Najtraženijih modela sa tržišnim signalima</div>
                </div>
            </div>
        </div>

        <div class="panel overflow-hidden p-6 sm:p-8">
            <div class="flex items-center justify-between">
                <div>
                    <div class="data-kicker">Puls tržišta</div>
                    <h2 class="mt-2 font-display text-3xl font-bold text-white">Šta je sada vruće</h2>
                </div>
                <a href="{{ route('listings.index') }}" wire:navigate class="btn-secondary">Svi oglasi</a>
            </div>

            <div class="mt-8 space-y-4">
                @forelse(($insights['popularModels'] ?? []) as $trend)
                    @php
                        $brand = data_get($trend, 'brand');
                        $model = data_get($trend, 'model');
                        $total = (int) data_get($trend, 'total', 0);
                    @endphp

                    @if($brand && $model)
                        <button
                            type="button"
                            wire:click="exploreModel('{{ $brand }}', '{{ $model }}')"
                            class="panel-soft flex w-full items-center justify-between p-4 text-left transition hover:bg-white/10"
                        >
                            <div>
                                <div class="text-lg font-semibold text-white">{{ $brand }} {{ $model }}</div>
                                <div class="text-sm text-slate-400">Najtraženiji modeli na tržištu</div>
                            </div>
                            <div class="rounded-full border border-cyan-300/30 bg-cyan-400/10 px-3 py-1 text-sm font-semibold text-cyan-100">
                                {{ number_format($total, 0, ',', '.') }} upita
                            </div>
                        </button>
                    @endif
                @empty
                    <div class="panel-soft p-5 text-sm text-slate-300">Popularni modeli će se prikazivati kada bude dovoljno pretraga.</div>
                @endforelse
            </div>
        </div>
    </section>

    @if($priorityGuides->isNotEmpty())
        <section class="space-y-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <div class="data-kicker">Najtraženiji vodiči</div>
                    <h2 class="section-title mt-2">Poređenja koja već dobijaju Google signal</h2>
                </div>
                <a href="{{ route('blog.index') }}" wire:navigate class="btn-secondary">Svi vodiči</a>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                @foreach($priorityGuides as $post)
                    <x-blog-post-card :post="$post" compact />
                @endforeach
            </div>
        </section>
    @endif

    <section class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="data-kicker">Najbolje ponude</div>
                <h2 class="section-title mt-2">Automobili koji trenutno iskaču po vrednosti</h2>
            </div>
            <p class="section-copy">Kombinacija niže cene od proseka, zdrave kilometraže i godišta daje visoku AutoIQ procenu.</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            @forelse(($insights['bestDeals'] ?? []) as $listing)
                <x-listing-card :listing="$listing" />
            @empty
                <div class="panel p-8 text-slate-300 lg:col-span-3">Najbolje ponude će se pojaviti čim se u ponudi nađu oglasi koji izdvajaju vrednost i cenu.</div>
            @endforelse
        </div>
    </section>

    <section class="grid gap-8 lg:grid-cols-[0.95fr_1.05fr]">
        <div class="space-y-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <div class="data-kicker">Padovi cena</div>
                    <h2 class="section-title mt-2">Vozila kojima cena klizi nadole</h2>
                </div>
                <a href="{{ route('listings.index') }}" wire:navigate class="btn-secondary">Pogledaj oglase</a>
            </div>

            @php
                $fallingPrices = collect($insights['fallingPrices'] ?? [])
                    ->filter(fn ($listing) => $listing instanceof \App\Models\Listing)
                    ->take(4)
                    ->values();

                $featuredDrop = $fallingPrices->first();
                $secondaryDrops = $fallingPrices->skip(1);
            @endphp

            <div class="panel overflow-hidden">
                @if($featuredDrop)
                    @php($featuredDropPercentage = $featuredDrop->priceDropPercentage())

                    <a href="{{ route('listings.show', $featuredDrop) }}" wire:navigate class="group block p-5 transition hover:bg-white/[0.03]">
                        <div class="grid gap-5 sm:grid-cols-[180px_1fr]">
                            <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-slate-900">
                                <img
                                    src="{{ $featuredDrop->primaryImageUrl() }}"
                                    alt="{{ $featuredDrop->title }}"
                                    class="aspect-[4/3] h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]"
                                    loading="lazy"
                                >

                                @if($featuredDropPercentage)
                                    <div class="absolute left-3 top-3 rounded-full border border-cyan-300/40 bg-cyan-400/15 px-3 py-1 text-xs font-semibold text-cyan-100">
                                        -{{ number_format($featuredDropPercentage, 1, ',', '.') }}%
                                    </div>
                                @endif
                            </div>

                            <div class="flex min-w-0 flex-col justify-between gap-5">
                                <div>
                                    <x-score-badge :listing="$featuredDrop" />
                                    <h3 class="mt-4 font-display text-2xl font-bold text-white transition group-hover:text-amber-300">
                                        {{ $featuredDrop->brand }} {{ $featuredDrop->model }}
                                    </h3>
                                    <p class="mt-2 text-sm text-slate-400">
                                        {{ $featuredDrop->year }} · {{ number_format($featuredDrop->mileage, 0, ',', '.') }} km · {{ $featuredDrop->city }}
                                    </p>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div class="panel-soft px-4 py-3">
                                        <div class="text-xs uppercase tracking-[0.18em] text-slate-500">Trenutna cena</div>
                                        <div class="mt-1 font-display text-2xl font-bold text-white">{{ number_format($featuredDrop->price, 0, ',', '.') }} €</div>
                                    </div>
                                    <div class="panel-soft px-4 py-3">
                                        <div class="text-xs uppercase tracking-[0.18em] text-slate-500">U odnosu na prosek</div>
                                        <div class="mt-1 text-sm font-semibold text-slate-100">{{ $featuredDrop->marketDifferenceLabel() }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

                    @if($secondaryDrops->isNotEmpty())
                        <div class="divide-y divide-white/8 border-t border-white/8">
                            @foreach($secondaryDrops as $listing)
                                @php($drop = $listing->priceDropPercentage())

                                <a href="{{ route('listings.show', $listing) }}" wire:navigate class="group flex items-center gap-4 px-5 py-4 transition hover:bg-white/[0.03]">
                                    <img
                                        src="{{ $listing->primaryImageUrl() }}"
                                        alt="{{ $listing->title }}"
                                        class="h-16 w-20 shrink-0 rounded-2xl border border-white/10 object-cover"
                                        loading="lazy"
                                    >

                                    <div class="min-w-0 flex-1">
                                        <div class="truncate text-sm font-semibold text-white transition group-hover:text-amber-300">{{ $listing->brand }} {{ $listing->model }}</div>
                                        <div class="mt-1 text-xs text-slate-500">{{ $listing->year }} · {{ $listing->city }}</div>
                                    </div>

                                    <div class="text-right">
                                        <div class="font-display text-lg font-bold text-white">{{ number_format($listing->price, 0, ',', '.') }} €</div>
                                        @if($drop)
                                            <div class="mt-1 text-xs font-semibold text-cyan-200">-{{ number_format($drop, 1, ',', '.') }}%</div>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="panel p-8 text-slate-300">Čim neki oglas dobije novu nižu cenu, pojaviće se ovde.</div>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div>
                <div class="data-kicker">Pregled tržišta</div>
                <h2 class="section-title mt-2">Prosečne cene po modelu i godištu</h2>
            </div>

            <div class="panel overflow-hidden">
                <div class="grid grid-cols-[1.4fr_1fr_0.8fr] gap-4 border-b border-white/8 px-5 py-4 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                    <span>Model</span>
                    <span>Prosek</span>
                    <span>Oglasa</span>
                </div>
                <div class="divide-y divide-white/6">
                    @forelse(($insights['marketSnapshots'] ?? []) as $snapshot)
                        <div class="grid grid-cols-[1.4fr_1fr_0.8fr] gap-4 px-5 py-4 text-sm text-slate-200">
                            <div>
                                <div class="font-semibold text-white">{{ $snapshot->brand }} {{ $snapshot->model }}</div>
                                <div class="text-xs text-slate-500">{{ $snapshot->year }}</div>
                            </div>
                            <div class="font-display text-lg font-bold text-white">{{ number_format((float) $snapshot->average_price, 0, ',', '.') }} €</div>
                            <div class="text-slate-400">{{ number_format((int) $snapshot->listings_count, 0, ',', '.') }}</div>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-sm text-slate-300">Potrebno je više oglasa da bi analiza proseka bila reprezentativna.</div>
                    @endforelse
                </div>
            </div>

            <div class="panel p-6">
                <div class="data-kicker">Zašto AutoIQ?</div>
                <ul class="mt-4 space-y-4 text-sm leading-7 text-slate-300">
                    <li>AutoIQ procena odmah govori da li oglas odskače od tržišta.</li>
                    <li>Istorija cena pomaže da prepoznate realan trenutak za kupovinu.</li>
                    <li>Favoriti i sačuvane pretrage rade kao alarm sistem za nove prilike.</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="data-kicker">AutoIQ Blog</div>
                <h2 class="section-title mt-2">Praktični vodiči za pametniju kupovinu</h2>
            </div>
            <a href="{{ route('blog.index') }}" wire:navigate class="btn-secondary">Otvori blog</a>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            @forelse($latestBlogPosts as $post)
                <x-blog-post-card :post="$post" />
            @empty
                <div class="panel p-8 text-slate-300 lg:col-span-3">Prvi AutoIQ tekstovi stižu uskoro i pratiće realne signale sa tržišta Srbije.</div>
            @endforelse
        </div>
    </section>
</div>
