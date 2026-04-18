@props([
    'listing',
    'favouritable' => false,
    'editable' => false,
])

@if($listing instanceof \App\Models\Listing)
    @php
        $drop = $listing->priceDropPercentage();
    @endphp

    <article class="panel group overflow-hidden">
    <div class="relative">
        <a href="{{ route('listings.show', $listing) }}" wire:navigate class="block">
            <div class="aspect-[4/3] overflow-hidden bg-slate-900">
                <img
                    src="{{ $listing->primaryImageUrl() }}"
                    alt="{{ $listing->title }}"
                    class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]"
                    loading="lazy"
                >
            </div>
        </a>

        <div class="absolute left-4 top-4">
            <x-score-badge :listing="$listing" />
        </div>

        @if($drop)
            <div class="absolute right-4 top-4 rounded-full border border-cyan-300/40 bg-cyan-400/15 px-3 py-1 text-xs font-semibold text-cyan-100">
                -{{ number_format($drop, 1, ',', '.') }}%
            </div>
        @endif

        @if($favouritable && auth()->check())
            <button
                type="button"
                wire:click.prevent.stop="toggleFavorite({{ $listing->id }})"
                class="absolute bottom-4 right-4 inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/15 bg-slate-950/70 text-lg text-white transition hover:bg-slate-900"
                aria-label="Dodaj u favorite"
            >
                {{ auth()->user()->hasFavorited($listing) ? '★' : '☆' }}
            </button>
        @endif
    </div>

    <div class="space-y-4 p-5">
        <div class="flex items-start justify-between gap-4">
            <div>
                <a href="{{ route('listings.show', $listing) }}" wire:navigate class="font-display text-xl font-bold text-white transition hover:text-amber-300">
                    {{ $listing->brand }} {{ $listing->model }}
                </a>
                <p class="mt-1 text-sm text-slate-400">{{ $listing->year }} · {{ number_format($listing->mileage, 0, ',', '.') }} km · {{ $listing->city }}</p>
            </div>
            <div class="text-right">
                <div class="font-display text-2xl font-bold text-white">{{ number_format($listing->price, 0, ',', '.') }} €</div>
                <div class="text-xs text-slate-400">{{ $listing->marketDifferenceLabel() }} u odnosu na prosek</div>
            </div>
        </div>

        <p class="line-clamp-2 text-sm leading-6 text-slate-300">{{ $listing->description }}</p>

        <div class="grid grid-cols-2 gap-3 text-sm text-slate-300">
            <div class="panel-soft px-3 py-2">
                <div class="text-xs uppercase tracking-[0.18em] text-slate-500">Gorivo</div>
                <div class="mt-1 font-semibold text-white">{{ $listing->fuel_type?->label() }}</div>
            </div>
            <div class="panel-soft px-3 py-2">
                <div class="text-xs uppercase tracking-[0.18em] text-slate-500">Menjač</div>
                <div class="mt-1 font-semibold text-white">{{ $listing->transmission?->label() }}</div>
            </div>
        </div>

        <div class="panel-soft p-3">
            <div class="mb-2 flex items-center justify-between text-xs uppercase tracking-[0.18em] text-slate-500">
                <span>Kretanje cene</span>
                <span>{{ $listing->priceHistories->count() }} tačaka</span>
            </div>
            <svg viewBox="0 0 112 28" class="h-10 w-full">
                <polyline
                    fill="none"
                    stroke="rgba(56,189,248,0.85)"
                    stroke-width="2.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    points="{{ $listing->sparklinePoints() }}"
                />
            </svg>
        </div>

        <div class="flex items-center justify-between">
            <div class="text-xs uppercase tracking-[0.18em] text-slate-500">
                {{ $listing->seller_type?->label() }}
            </div>

            <div class="flex items-center gap-2">
                @if($editable)
                    <a href="{{ route('listings.edit', $listing) }}" wire:navigate class="btn-ghost">Izmeni</a>
                @endif
                <a href="{{ route('listings.show', $listing) }}" wire:navigate class="btn-secondary">Detalji</a>
            </div>
        </div>
    </div>
    </article>
@endif
