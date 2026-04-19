@props(['listing'])

@php
    $sellerPhones = $listing->sellerContactPhones();
    $sellerPhoneLinks = $sellerPhones->map(fn (string $phone) => [
        'label' => $phone,
        'href' => 'tel:'.preg_replace('/[^0-9+]/', '', $phone),
    ]);
@endphp

<div {{ $attributes->class('space-y-5') }}>
    <div>
        <div class="data-kicker">Brza odluka</div>
        <div class="mt-3 flex flex-col gap-4">
            <div>
                <div class="text-sm text-slate-400">Tražena cena</div>
                <div class="font-display mt-2 text-4xl font-bold text-white">{{ number_format($listing->price, 0, ',', '.') }} €</div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <x-score-badge :listing="$listing" />
                <span class="text-sm text-slate-400">{{ $listing->marketDifferenceLabel() }} u odnosu na prosečnu cenu</span>
            </div>
        </div>
    </div>

    <div class="panel-soft p-4">
        <div class="text-xs uppercase tracking-[0.18em] text-slate-500">Kontakt prodavca</div>
        <div class="mt-2 text-lg font-semibold text-white">{{ $listing->sellerContactName() }}</div>
        @if($sellerPhoneLinks->isNotEmpty())
            <div class="mt-3 flex flex-col gap-2">
                @foreach($sellerPhoneLinks as $phone)
                    <a href="{{ $phone['href'] }}" class="text-sm font-semibold text-cyan-200 transition hover:text-cyan-100">
                        {{ $phone['label'] }}
                    </a>
                @endforeach
            </div>
        @else
            <div class="mt-3 text-sm text-slate-400">Telefon nije unet.</div>
        @endif
    </div>

    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-1">
        <div class="panel-soft p-4">
            <div class="text-xs uppercase tracking-[0.18em] text-slate-500">Prosek tržišta</div>
            <div class="mt-2 font-display text-2xl font-bold text-white">{{ number_format($listing->market_average_price ?: $listing->price, 0, ',', '.') }} €</div>
        </div>
        <div class="panel-soft p-4">
            <div class="text-xs uppercase tracking-[0.18em] text-slate-500">Tržišni signal</div>
            <div class="mt-2 text-lg font-semibold text-white">{{ $listing->scoreLabel() }}</div>
        </div>
    </div>

    <div class="space-y-3">
        @auth
            <button type="button" wire:click="toggleFavorite" class="btn-primary w-full">
                {{ auth()->user()->hasFavorited($listing) ? 'Ukloni iz favorita' : 'Dodaj u favorite' }}
            </button>
        @else
            <a href="{{ route('login') }}" wire:navigate class="btn-primary w-full">Prijavite se za favorite</a>
        @endauth

        @if(auth()->id() === $listing->user_id || auth()->user()?->isAdmin())
            <a href="{{ route('listings.edit', $listing) }}" wire:navigate class="btn-secondary w-full">Izmeni oglas</a>
        @endif
    </div>
</div>
