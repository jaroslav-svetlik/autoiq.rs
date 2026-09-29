@php
    $isFavorite = auth()->check() && auth()->user()->hasFavorited($listing);
    $externalSource = $listing->externalSource;
    $sourceUrl = $externalSource?->publicSourceUrl();
    $dealer = $externalSource ? null : $listing->dealerProfile;
    $verified = $dealer?->verified_at !== null;
    $sellerName = $listing->sellerContactName();
    $sellerPhones = $listing->sellerContactPhones();
    $firstPhone = $sellerPhones->first();
    $contactEmail = $dealer?->email;
    $messageUrl = $contactEmail
        ? 'mailto:'.$contactEmail.'?subject='.rawurlencode('Pitanje o oglasu: '.$listing->title).'&body='.rawurlencode('Zdravo, zanima me vozilo iz oglasa: '.route('listings.show', $listing))
        : ($firstPhone ? 'sms:'.preg_replace('/[^0-9+]/', '', $firstPhone) : null);
    $modelLandingPage = \App\Support\Seo\VehicleLandingPages::for($listing->brand, $listing->model);
    $similarUrl = $modelLandingPage
        ? route('listings.model', \App\Support\Seo\VehicleLandingPages::routeParameters($listing->brand, $listing->model))
        : route('listings.index', ['brand' => $listing->brand, 'model' => $listing->model]);
    $mapUrl = 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($listing->city.', Srbija');
    $equipmentGroups = $listing->selectedEquipmentGroups();
    $specifications = [
        ['calendar-days', 'Godište', $listing->year],
        ['gauge', 'Kilometraža', number_format($listing->mileage, 0, ',', '.').' km'],
        ['fuel', 'Gorivo', $listing->fuel_type?->label()],
        ['cog', 'Menjač', $listing->transmission?->label()],
        ['car-front', 'Marka', $listing->brand],
        ['layers-2', 'Model', $listing->model],
        ['user-round', 'Prodavac', $listing->seller_type?->label()],
        ['map-pin', 'Lokacija', $listing->city],
    ];
@endphp

<div class="listing-detail" x-data="listingActions" data-listing-url="{{ route('listings.show', $listing) }}">
    <nav class="detail-breadcrumbs" aria-label="Putanja stranice">
        <a href="{{ route('home') }}" wire:navigate>Početna</a><x-icon name="chevron" />
        <a href="{{ route('listings.index') }}" wire:navigate>Automobili</a><x-icon name="chevron" />
        <a href="{{ route('listings.index', ['brand' => $listing->brand]) }}" wire:navigate>{{ $listing->brand }}</a><x-icon name="chevron" />
        <a href="{{ $similarUrl }}" wire:navigate>{{ $listing->model }}</a><x-icon name="chevron" />
        <span aria-current="page">{{ $listing->title }}</span>
    </nav>

    <div class="detail-layout">
        <div class="detail-media"><x-listing-gallery :listing="$listing" :favorite="$isFavorite" /></div>

        <div class="detail-right">
        <section class="detail-summary" aria-labelledby="listing-title">
            <div class="detail-heading-row">
                <div>
                    <h1 id="listing-title">{{ $listing->title }}</h1>
                    <div class="detail-quick-specs"><span>{{ $listing->year }}</span><span>{{ $listing->fuel_type?->label() }}</span><span>{{ number_format($listing->mileage, 0, ',', '.') }} km</span><span>{{ $listing->transmission?->label() }}</span></div>
                </div>
                <div class="detail-actions">
                    @auth
                        <button type="button" wire:click="toggleFavorite" wire:loading.attr="disabled" wire:target="toggleFavorite" class="{{ $isFavorite ? 'is-saved' : '' }}" aria-pressed="{{ $isFavorite ? 'true' : 'false' }}" aria-label="{{ $isFavorite ? 'Ukloni iz favorita' : 'Dodaj u favorite' }}"><x-icon name="heart" /><span>{{ $isFavorite ? 'Sačuvano' : 'Sačuvaj' }}</span></button>
                    @else
                        <a href="{{ route('login') }}" wire:navigate aria-label="Prijavite se da sačuvate oglas"><x-icon name="heart" /><span>Sačuvaj</span></a>
                    @endauth
                    <button type="button" x-on:click="copyLink()" aria-label="Kopiraj link oglasa"><x-icon name="share" /><span>Podeli</span></button>
                    <a href="{{ route('contact', ['oglas' => $listing->slug]) }}" wire:navigate aria-label="Prijavi oglas AutoIQ podršci"><x-icon name="flag" /><span>Prijavi</span></a>
                </div>
            </div>
            <div class="detail-share-feedback" role="status" x-text="shareStatus" x-show="shareStatus" x-cloak></div>
            <input x-cloak x-show="showShareLink" x-ref="shareLink" type="text" readonly value="{{ route('listings.show', $listing) }}" aria-label="Link oglasa za deljenje" class="input-shell w-full">
            <div class="detail-price" aria-label="Tražena cena">{{ number_format($listing->price, 0, ',', '.') }} €</div>
            <div class="detail-location-line"><a href="#lokacija-vozila"><x-icon name="pin" />{{ $listing->city }}</a><span>ID oglasa: #{{ $listing->id }}</span></div>
            <div class="detail-badges">
                @if($verified)<span><x-icon name="shield" />Proveren prodavac</span>@endif
                <a href="#procena-cene"><x-icon name="chart" />AutoIQ {{ $listing->autoiq_score }}/100</a>
                <span><x-icon name="user" />{{ $listing->seller_type?->label() }}</span>
            </div>
            @if(auth()->id() === $listing->user_id || auth()->user()?->isAdmin())
                <a href="{{ route('listings.edit', $listing) }}" wire:navigate class="detail-edit-link">Izmeni oglas <x-icon name="arrow" /></a>
            @endif
        </section>

        <aside class="detail-sidebar" aria-label="Prodavac i informacije o kupovini">
            @if($externalSource)
            <section class="detail-panel seller-panel external-listing-panel" aria-label="Izvor oglasa i kontakt prodavca">
                <div class="detail-section-heading"><h2>Oglas sa sajta {{ $externalSource->sourceLabel() }}</h2></div>
                <div class="seller-profile">
                    <div class="seller-identity">
                        <span class="seller-avatar" aria-hidden="true"><x-icon name="car" /></span>
                        <div><h3>{{ $sellerName }}</h3><p><x-icon name="pin" />{{ $listing->city }}</p></div>
                    </div>
                    @if($externalSource->fetched_at)<div class="seller-facts"><span><x-icon name="clock" />Podaci preuzeti {{ $externalSource->fetched_at->format('d.m.Y.') }}</span></div>@endif
                </div>
                <p class="external-listing-note">Ovaj oglas je preuzet sa drugog sajta. Aktuelnu cenu, dostupnost vozila i kontakt prodavca proverite u originalnom oglasu.</p>
                @if($sourceUrl)<a href="{{ $sourceUrl }}" target="_blank" rel="noopener noreferrer" class="btn-primary external-listing-link">Pogledaj originalni oglas <x-icon name="arrow" /></a>@endif
            </section>
            @else
            <section class="detail-panel seller-panel" aria-label="Kontakt prodavca">
                <div class="detail-section-heading"><h2>Prodavac</h2>@if($dealer)<a href="{{ route('dealers.show', $dealer) }}" wire:navigate>Svi oglasi prodavca <x-icon name="arrow" /></a>@endif</div>
                <div class="seller-profile">
                    <div class="seller-identity">
                        @if($dealer?->logo_path)<img src="{{ $dealer->logoUrl() }}" alt="" width="48" height="48" class="seller-avatar">
                        @else<span class="seller-avatar" aria-hidden="true">{{ str($sellerName)->substr(0, 1)->upper() }}</span>@endif
                        <div><h3>@if($dealer)<a href="{{ route('dealers.show', $dealer) }}" wire:navigate>{{ $sellerName }}</a>@else{{ $sellerName }}@endif</h3><p><x-icon name="pin" />{{ $dealer?->city ?: $listing->city }}</p></div>
                    </div>
                    <div class="seller-facts">
                        @if($listing->user?->created_at)<span><x-icon name="clock" />Član od {{ $listing->user->created_at->format('Y') }}.</span>@endif
                        <span><x-icon :name="$verified ? 'shield' : 'user'" />{{ $verified ? 'Proveren prodavac' : $listing->seller_type?->label() }}</span>
                    </div>
                </div>
                <div class="seller-contact-actions">
                    @if($sellerPhones->isNotEmpty())
                        <details class="seller-phone-menu">
                            <summary class="btn-primary"><x-icon name="phone" />Pozovi</summary>
                            <div class="seller-phone-numbers"><span>Kontakt prodavca</span>@foreach($sellerPhones as $phone)<a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}">{{ $phone }}</a>@endforeach</div>
                        </details>
                    @else<div class="seller-no-phone">Telefon nije unet.</div>@endif
                    @if($messageUrl)<a href="{{ $messageUrl }}" class="btn-secondary" aria-label="{{ $contactEmail ? 'Pošalji email prodavcu' : 'Pošalji SMS prodavcu' }}"><x-icon name="message" />Pošalji poruku</a>@endif
                </div>
                @if($messageUrl)<p class="seller-contact-note">{{ $contactEmail ? 'Poruka se otvara u vašoj email aplikaciji.' : 'Poruka se otvara u vašoj SMS aplikaciji.' }}</p>@endif
            </section>
            @endif

            <div class="detail-panel detail-services">
                <a href="#procena-cene" class="detail-service"><span class="detail-service-icon"><x-icon name="chart" /></span><span><strong>AutoIQ procena cene</strong><small>{{ $listing->scoreLabel() }}. Pogledajte poređenje sa tržištem i istoriju cene.</small></span><span class="detail-service-arrow"><x-icon name="arrow" /></span></a>
                <a href="{{ route('blog.index', ['tema' => 'Kupovina polovnjaka']) }}" wire:navigate class="detail-service"><span class="detail-service-icon"><x-icon name="book" /></span><span><strong>Saveti za kupovinu</strong><small>Pripremite se za pregled vozila i razgovor sa prodavcem.</small></span><span class="detail-service-arrow"><x-icon name="arrow" /></span></a>
            </div>

            <section id="lokacija-vozila" class="detail-panel detail-location">
                <div class="detail-section-heading"><h2><x-icon name="pin" />Lokacija</h2><a href="{{ $mapUrl }}" target="_blank" rel="noopener noreferrer">Prikaži na mapi <x-icon name="arrow" /></a></div>
                <a class="detail-city-preview" href="{{ $mapUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Pronađi {{ $listing->city }} na Google mapi"><span class="city-preview-icon"><x-icon name="pin" /></span><strong>{{ $listing->city }}</strong><span>Srbija</span></a>
                <p><x-icon name="pin" /><span><strong>{{ $listing->city }}</strong><small>Prikazan je grad iz oglasa. Tačnu lokaciju dogovorite sa prodavcem.</small></span></p>
            </section>

            <a href="{{ route('blog.index', ['tema' => 'Kupovina polovnjaka']) }}" wire:navigate class="detail-service detail-safety"><span class="detail-service-icon"><x-icon name="shield" /></span><span><strong>Sigurnija kupovina uz AutoIQ.rs</strong><small>Pročitajte na šta da obratite pažnju pre kupovine polovnog vozila.</small></span><span class="detail-service-arrow"><x-icon name="arrow" /></span></a>
        </aside>
        </div>

        <div class="detail-information">
            <section class="detail-panel detail-specifications" aria-labelledby="listing-specifications-title">
                <h2 id="listing-specifications-title">Osnovne informacije</h2>
                <dl class="detail-spec-grid">
                    @foreach($specifications as [$icon, $label, $value])
                        @if(filled($value))<div><span class="detail-spec-icon"><x-lucide-icon :name="$icon" /></span><div><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div></div>@endif
                    @endforeach
                </dl>
            </section>

            @if($equipmentGroups->isNotEmpty())
                <section class="detail-panel detail-equipment-panel" aria-labelledby="listing-equipment-title">
                    <h2 id="listing-equipment-title">Oprema</h2>
                    @foreach($equipmentGroups as $group)
                        <div class="detail-equipment-group">
                            <h3 id="equipment-{{ $group['key'] }}">{{ $group['label'] }}</h3>
                            <ul class="detail-equipment" aria-labelledby="equipment-{{ $group['key'] }}">
                                @foreach($group['options'] as $option)
                                    <li>
                                        <span class="detail-equipment-icon"><x-equipment-icon :equipment="$option['key']" /></span>
                                        <span>{{ $option['label'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </section>
            @endif

            <section class="detail-panel detail-description">
                <h2>Opis oglasa</h2>
                <div class="detail-description-text rich-text-content">{!! $listing->descriptionHtml() !!}</div>
                <div class="detail-listing-meta"><span><x-icon name="calendar" />{{ $listing->published_at ? 'Objavljen '.$listing->published_at->format('d.m.Y.') : 'Oglas u pripremi' }}</span><span><x-icon name="users" />Pregledi: {{ number_format($listing->views_count, 0, ',', '.') }}</span></div>
            </section>

            <section id="procena-cene" class="detail-panel detail-market">
                <div class="detail-section-heading"><h2>AutoIQ analiza cene</h2><span class="detail-score">{{ $listing->autoiq_score }}/100</span></div>
                <div class="detail-market-stats"><div><span>Tržišni signal</span><strong>{{ $listing->scoreLabel() }}</strong></div><div><span>Prosek tržišta</span><strong>{{ $listing->market_average_price ? number_format($listing->market_average_price, 0, ',', '.').' €' : 'Nedovoljno podataka' }}</strong></div></div>
                <p>
                    @if($listing->price_deviation_percentage !== null)
                        Cena oglasa je {{ $listing->marketDifferenceLabel() }} u odnosu na prosečnu vrednost za {{ $listing->brand }} {{ $listing->model }} {{ $listing->year }}.
                    @else
                        Trenutno nema dovoljno podataka za poređenje ovog oglasa sa tržištem.
                    @endif
                </p>
                @if($listing->priceHistories->isNotEmpty())
                    <details class="detail-price-history">
                        <summary>Istorija cene <span>{{ $listing->priceHistories->count() }} {{ $listing->priceHistories->count() === 1 ? 'zapis' : 'zapisa' }} <x-icon name="chevron" /></span></summary>
                        <div class="detail-history-chart"><svg viewBox="0 0 400 60" role="img" aria-label="Kretanje zabeleženih cena"><polyline fill="none" stroke="#2442ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" points="{{ $listing->sparklinePoints(400, 60) }}" /></svg></div>
                        <ul>@foreach($listing->priceHistories->sortByDesc('recorded_at')->take(6) as $history)<li><span><strong>{{ number_format($history->price, 0, ',', '.') }} €</strong><small>{{ $history->note ?: 'Zabeležena cena' }}</small></span><time datetime="{{ $history->recorded_at->toDateString() }}">{{ $history->recorded_at->format('d.m.Y.') }}</time></li>@endforeach</ul>
                    </details>
                @endif
            </section>
        </div>
    </div>

    <section class="detail-similar">
        <div class="detail-section-heading"><h2>Slični oglasi</h2><a href="{{ $similarUrl }}" wire:navigate>Pogledaj sve slične oglase <x-icon name="arrow" /></a></div>
        <div class="detail-similar-grid">
            @forelse($similarListings as $item)<x-listing-card :listing="$item" :favouritable="true" :favorite="in_array($item->id, $favoriteIds, true)" />
            @empty<div class="detail-panel detail-similar-empty"><x-icon name="car" /><div><h3>Trenutno nema drugih oglasa za ovaj model.</h3><p>Istražite ostale automobile i pronađite ponudu za sebe.</p></div><a href="{{ route('listings.index', ['brand' => $listing->brand]) }}" wire:navigate class="btn-secondary">Svi {{ $listing->brand }} oglasi <x-icon name="arrow" /></a></div>@endforelse
        </div>
    </section>

    <section class="detail-drive-banner drive-banner"><div class="drive-banner-content"><span class="feature-icon"><x-icon name="car" /></span><div><h2>Tražiš novi automobil?</h2><p>Pronađi svoju sledeću vožnju na AutoIQ.rs.</p></div><a href="{{ route('home') }}" wire:navigate class="btn-primary">Pretraži automobile <x-icon name="arrow" /></a></div></section>
</div>
