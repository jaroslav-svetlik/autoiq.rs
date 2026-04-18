<div class="space-y-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <div class="data-kicker">Real-time pretraga</div>
            <h1 class="section-title mt-2">Auto oglasi i analiza tržišta</h1>
            <p class="section-copy mt-3">Filtrirajte po budžetu, godištu, kilometraži, lokaciji i opremi, pa odmah vidite gde cena odstupa od proseka.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @auth
                <a href="{{ route('listings.create') }}" wire:navigate class="btn-primary">Dodaj oglas</a>
            @endauth
            <button type="button" wire:click="clearFilters" class="btn-secondary">Reset filtera</button>
        </div>
    </div>

    <div class="grid gap-8 xl:grid-cols-[320px_1fr]">
        <aside class="space-y-4 xl:sticky xl:top-28 xl:self-start">
            <div class="panel p-5">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="font-display text-2xl font-bold text-white">Filteri</h2>
                    <span class="chip">Pametna pretraga</span>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="field-label">Tekstualna pretraga</label>
                        <input type="text" wire:model.live.debounce.350ms="search" class="input-shell w-full" placeholder="Audi A4, SUV, automatik...">
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
                        <div>
                            <label class="field-label">Marka</label>
                            <select wire:model.live="brand" class="input-shell w-full">
                                <option value="">Sve marke</option>
                                @foreach($brands as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="field-label">Model</label>
                            <select wire:model.live="model" class="input-shell w-full">
                                <option value="">Svi modeli</option>
                                @foreach($models as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
                        <div>
                            <label class="field-label">Cena od (€)</label>
                            <input type="number" wire:model.live.debounce.400ms="minPrice" class="input-shell w-full" placeholder="3000">
                        </div>
                        <div>
                            <label class="field-label">Cena do (€)</label>
                            <input type="number" wire:model.live.debounce.400ms="maxPrice" class="input-shell w-full" placeholder="15000">
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
                        <div>
                            <label class="field-label">Godište od</label>
                            <input type="number" wire:model.live.debounce.400ms="minYear" class="input-shell w-full" placeholder="2015">
                        </div>
                        <div>
                            <label class="field-label">Kilometraža do</label>
                            <input type="number" wire:model.live.debounce.400ms="maxMileage" class="input-shell w-full" placeholder="180000">
                        </div>
                    </div>

                    <div>
                        <label class="field-label">Gorivo</label>
                        <select wire:model.live="fuelType" class="input-shell w-full">
                            <option value="">Sve opcije</option>
                            @foreach($fuelTypes as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="field-label">Menjač</label>
                        <select wire:model.live="transmission" class="input-shell w-full">
                            <option value="">Svi menjači</option>
                            @foreach($transmissionTypes as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="field-label">Lokacija</label>
                        <select wire:model.live="city" class="input-shell w-full">
                            <option value="">Cela Srbija</option>
                            @foreach($cities as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="field-label">Sortiranje</label>
                        <select wire:model.live="sort" class="input-shell w-full">
                            <option value="newest">Najnoviji</option>
                            <option value="price_asc">Cena rastuće</option>
                            <option value="price_desc">Cena opadajuće</option>
                            <option value="best">Najbolja ponuda</option>
                            <option value="relevance">Relevantnost</option>
                        </select>
                    </div>

                    <div class="panel-soft p-4">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="field-label">Oprema</div>
                                <p class="mt-2 text-xs leading-6 text-slate-400">Prikazujemo samo oglase koji imaju sve odabrane stavke.</p>
                            </div>
                            <div class="text-xs text-slate-400">{{ count($equipment) }} izabrano</div>
                        </div>

                        @if($selectedEquipmentLabels->isNotEmpty())
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach($selectedEquipmentLabels as $label)
                                    <span class="chip">{{ $label }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-4 space-y-3">
                            @foreach($equipmentCatalog as $group)
                                <details class="rounded-2xl border border-white/10 bg-white/5 p-4" @if($loop->first) open @endif>
                                    <summary class="cursor-pointer list-none text-sm font-semibold text-white">{{ $group['label'] }}</summary>
                                    <div class="mt-4 grid gap-3">
                                        @foreach($group['options'] as $option)
                                            <label class="flex items-start gap-3 rounded-2xl border border-white/10 bg-slate-950/40 px-3 py-3 text-sm text-slate-200 transition hover:border-cyan-400/30 hover:bg-cyan-400/5">
                                                <input
                                                    type="checkbox"
                                                    value="{{ $option['key'] }}"
                                                    wire:model.live="equipment"
                                                    class="mt-1 h-4 w-4 rounded border-white/20 bg-slate-950/60 text-cyan-400 focus:ring-cyan-400/30"
                                                >
                                                <span>{{ $option['label'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </details>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            @auth
                <div class="panel p-5">
                    <h3 class="font-display text-xl font-bold text-white">Sačuvaj pretragu</h3>
                    <p class="mt-2 text-sm leading-7 text-slate-300">Aktivirajte alarm za nove oglase i pad cene.</p>

                    <div class="mt-4 space-y-4">
                        <input type="text" wire:model.live="saveSearchName" class="input-shell w-full" placeholder="Npr. Golf 7 do 9000 €">
                        <button type="button" wire:click="saveCurrentSearch" class="btn-primary w-full">Sačuvaj i uključi alarme</button>
                    </div>
                </div>
            @endauth
        </aside>

        <section class="space-y-6">
            <div class="panel flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="text-sm text-slate-400">Rezultati</div>
                    <div class="font-display text-3xl font-bold text-white">{{ number_format($listings->total(), 0, ',', '.') }}</div>
                </div>
                    <div class="grid gap-3 sm:grid-cols-3">
                        <div class="panel-soft px-4 py-3 text-sm text-slate-300">
                        <div class="text-xs uppercase tracking-[0.18em] text-slate-500">Sort</div>
                        <div class="mt-1 font-semibold text-white">
                            {{
                                match($sort) {
                                    'price_asc' => 'Cena rastuće',
                                    'price_desc' => 'Cena opadajuće',
                                    'best' => 'Najbolja ponuda',
                                    'relevance' => 'Relevantnost',
                                    default => 'Najnoviji',
                                }
                            }}
                        </div>
                    </div>
                    <div class="panel-soft px-4 py-3 text-sm text-slate-300">
                        <div class="text-xs uppercase tracking-[0.18em] text-slate-500">AutoIQ fokus</div>
                        <div class="mt-1 font-semibold text-white">Score 0–100</div>
                        </div>
                        <div class="panel-soft px-4 py-3 text-sm text-slate-300">
                            <div class="text-xs uppercase tracking-[0.18em] text-slate-500">Oprema</div>
                            <div class="mt-1 font-semibold text-white">{{ $selectedEquipmentLabels->isNotEmpty() ? $selectedEquipmentLabels->count().' odabrane stavke' : 'Bez ograničenja' }}</div>
                        </div>
                    </div>
                </div>

            @if($listings->count())
                <div class="grid gap-6 lg:grid-cols-2 2xl:grid-cols-3">
                    @foreach($listings as $listing)
                        <div wire:key="listing-{{ $listing->id }}">
                            <x-listing-card :listing="$listing" favouritable />
                        </div>
                    @endforeach
                </div>

                <div class="pt-2">
                    {{ $listings->links() }}
                </div>
            @else
                <div class="panel p-10 text-center">
                    <h2 class="font-display text-3xl font-bold text-white">Nema rezultata</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-300">Probajte širi budžet, drugačiji grad ili uklonite deo filtera.</p>
                </div>
            @endif
        </section>
    </div>
</div>
