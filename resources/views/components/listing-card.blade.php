@props(['listing', 'favouritable' => false, 'editable' => false, 'favorite' => null])
@if($listing instanceof \App\Models\Listing)
    @php
        $isFavorite = $favorite ?? (auth()->check() && $favouritable && auth()->user()->hasFavorited($listing));
        $featured = $listing->is_featured && (! $listing->featured_until || $listing->featured_until->isFuture());
        $new = $listing->published_at?->greaterThan(now()->subDays(7));
        $goodPrice = $listing->price_deviation_percentage !== null && $listing->price_deviation_percentage <= -5;
        $verified = $listing->dealerProfile?->verified_at !== null;
    @endphp
    <article class="listing-card">
        <div class="listing-photo">
            <a href="{{ route('listings.show', $listing) }}" wire:navigate tabindex="-1" aria-hidden="true">
                <img src="{{ $listing->primaryImageUrl() }}" alt="{{ $listing->title }}" loading="lazy" width="480" height="300">
            </a>
            @if($editable)
                <span class="listing-badge listing-status listing-status-{{ $listing->status->value }}" aria-live="polite">{{ $listing->status->label() }}</span>
            @elseif($featured || $goodPrice || $new)
                <span class="listing-badge {{ $featured ? '' : 'listing-badge-teal' }}">{{ $featured ? 'Top ponuda' : ($goodPrice ? 'Odlična cena' : 'Novo') }}</span>
            @endif
            @if($favouritable)
                @auth
                    <button type="button" wire:click="toggleFavorite({{ $listing->id }})" wire:loading.attr="disabled" wire:target="toggleFavorite({{ $listing->id }})" class="favorite-button {{ $isFavorite ? 'is-favorite' : '' }}" aria-label="{{ $isFavorite ? 'Ukloni iz favorita' : 'Dodaj u favorite' }}" aria-pressed="{{ $isFavorite ? 'true' : 'false' }}"><x-icon name="heart" /></button>
                @else
                    <a href="{{ route('login') }}" wire:navigate class="favorite-button" aria-label="Prijavite se da sačuvate oglas"><x-icon name="heart" /></a>
                @endauth
            @endif
        </div>
        @if($editable)
            <x-listing-owner-menu :listing="$listing" />
        @endif
        <div class="listing-body">
            <div class="listing-heading">
                <h3><a href="{{ route('listings.show', $listing) }}" wire:navigate>{{ $listing->brand }} {{ $listing->model }}</a></h3>
                <strong class="listing-price">{{ number_format($listing->price, 0, ',', '.') }} €</strong>
            </div>
            <div class="listing-specs">
                <span><x-icon name="calendar" />{{ $listing->year }}</span>
                <span><x-icon name="gauge" />{{ number_format($listing->mileage, 0, ',', '.') }} km</span>
                <span><x-icon name="fuel" />{{ $listing->fuel_type?->label() }}</span>
                <span><x-icon name="gear" />{{ $listing->transmission?->label() }}</span>
                <span class="listing-location"><x-icon name="pin" />{{ $listing->city }}</span>
            </div>
            <p class="listing-description">{{ \Illuminate\Support\Str::limit($listing->description, 180) }}</p>
            <div class="listing-bottom">
                <div class="listing-seller">
                    <x-icon :name="$verified ? 'shield' : 'user'" />
                    <div><span>{{ $verified ? 'Proveren prodavac' : $listing->seller_type?->label() }}</span><small>AutoIQ procena <b>{{ $listing->autoiq_score }}/100</b></small></div>
                </div>
                <a href="{{ route('listings.show', $listing) }}" wire:navigate class="card-arrow" aria-label="Pogledaj {{ $listing->brand }} {{ $listing->model }}"><x-icon name="arrow" /></a>
            </div>
            @if($editable && $listing->status !== \App\Enums\ListingStatus::Published)
                <p class="listing-visibility-note"><x-lucide-icon name="eye-off" />Oglas nije javno vidljiv.</p>
            @endif
        </div>
    </article>
@endif
