@props(['listing', 'favorite' => false])
@php
    $galleryImages = $listing->images->map(fn ($image) => ['url' => $image->url(), 'alt' => $image->alt_text ?: $listing->title])->values();
    if ($galleryImages->isEmpty()) {
        $galleryImages = collect([['url' => $listing->primaryImageUrl(), 'alt' => $listing->title]]);
    }
    $featured = $listing->is_featured && (! $listing->featured_until || $listing->featured_until->isFuture());
    $visibleThumbnails = $galleryImages->count() > 6 ? 5 : 6;
@endphp
<div class="detail-gallery" data-gallery-carousel data-gallery-images='@json($galleryImages->all())'
    x-data="listingGallery" x-on:keydown.left.stop.prevent="previous()" x-on:keydown.right.stop.prevent="next()"
    x-on:keydown.left.window="previousWhenOpen($event)" x-on:keydown.right.window="nextWhenOpen($event)"
    x-on:keydown.escape.window="closeLightbox()" x-on:keydown.tab.window="trapFocus($event)"
    x-on:livewire:navigating.window="closeLightbox()" tabindex="0" aria-label="Galerija vozila">
    <div class="detail-gallery-stage">
        <button type="button" x-on:click="openLightbox()" class="detail-gallery-open" aria-label="Otvori fotografiju u uvećanom prikazu">
            <img src="{{ $galleryImages->first()['url'] }}" alt="{{ $galleryImages->first()['alt'] }}"
                x-bind:src="activeImage.url" x-bind:alt="activeImage.alt" fetchpriority="high" width="960" height="540">
        </button>
        @if($featured)<span class="listing-badge">Top ponuda</span>@endif
        @auth
            <button type="button" wire:click="toggleFavorite" wire:loading.attr="disabled" wire:target="toggleFavorite"
                class="favorite-button {{ $favorite ? 'is-favorite' : '' }}" aria-label="{{ $favorite ? 'Ukloni iz favorita' : 'Dodaj u favorite' }}" aria-pressed="{{ $favorite ? 'true' : 'false' }}"><x-icon name="heart" /></button>
        @else
            <a href="{{ route('login') }}" wire:navigate class="favorite-button" aria-label="Prijavite se da sačuvate oglas"><x-icon name="heart" /></a>
        @endauth
        @if($galleryImages->count() > 1)
            <button type="button" x-on:click="previous()" class="gallery-arrow gallery-arrow-prev" aria-label="Prethodna fotografija"><x-icon name="chevron" /></button>
            <button type="button" x-on:click="next()" class="gallery-arrow gallery-arrow-next" aria-label="Sledeća fotografija"><x-icon name="chevron" /></button>
        @endif
        <button type="button" x-on:click="openLightbox()" class="gallery-counter" aria-label="Pogledaj sve fotografije"><x-icon name="camera" /><span><span x-text="active + 1">1</span> / {{ $galleryImages->count() }}</span></button>
    </div>
    @if($galleryImages->count() > 1)
        <div class="detail-thumbnails" data-gallery-thumbnail-track>
            @foreach($galleryImages->take($visibleThumbnails) as $index => $image)
                <button type="button" x-on:click="setActive({{ $index }})" x-bind:class="active === {{ $index }} ? 'is-active' : ''"
                    x-bind:aria-pressed="active === {{ $index }}" data-gallery-thumbnail="{{ $index }}" aria-label="Prikaži fotografiju {{ $index + 1 }}">
                    <img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}" loading="lazy" width="160" height="100">
                </button>
            @endforeach
            @if($galleryImages->count() > 6)
                <button type="button" x-on:click="openLightbox(5)" class="gallery-more" aria-label="Prikaži još {{ $galleryImages->count() - 5 }} fotografija">
                    <img src="{{ $galleryImages[5]['url'] }}" alt="" loading="lazy" width="160" height="100">
                    <span><b>+{{ $galleryImages->count() - 5 }}</b>još fotografija</span>
                </button>
            @endif
        </div>
    @endif
                <template x-teleport="body">
                    <div
                        x-cloak
                        x-show="lightboxOpen"
                        x-transition.opacity
                        class="fixed inset-0 z-50 flex items-center justify-center bg-white p-4 sm:p-6"
                        role="dialog"
                        aria-modal="true"
                        aria-label="Galerija fotografija vozila"
                        x-ref="galleryDialog"
                    >
                        <button
                            type="button"
                            x-on:click="closeLightbox()"
                            class="absolute inset-0 cursor-zoom-out"
                            aria-label="Zatvori uvećani prikaz"
                        ></button>

                        <div class="relative z-10 flex h-full w-full max-w-7xl min-w-0 flex-col gap-4">
                            <div class="flex min-w-0 flex-wrap items-center justify-between gap-3">
                                <div class="rounded-full border border-line bg-wash px-4 py-2 text-sm text-ink">
                                    <span x-text="active + 1"></span> / {{ $galleryImages->count() }}
                                </div>

                                <div class="flex min-w-0 flex-wrap items-center gap-2">
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
                                        aria-label="Vrati početnu veličinu"
                                    >
                                        <span x-text="zoomLabel"></span>
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

                            <div class="grid min-h-0 min-w-0 flex-1 gap-4 lg:grid-cols-[88px_minmax(0,1fr)]">
                                @if($galleryImages->count() > 1)
                                    <div class="order-2 flex h-20 min-w-0 shrink-0 gap-3 overflow-x-auto overflow-y-hidden lg:order-1 lg:h-full lg:flex-col lg:overflow-x-hidden lg:overflow-y-auto" data-gallery-thumbnail-track>
                                        @foreach($galleryImages as $index => $image)
                                            <button
                                                type="button"
                                                x-on:click="setActive({{ $index }})"
                                                x-bind:class="active === {{ $index }} ? 'border-brand ring-2 ring-brand/20' : 'border-line opacity-70 hover:opacity-100'"
                                                data-gallery-thumbnail="{{ $index }}"
                                                class="relative h-20 w-20 shrink-0 overflow-hidden rounded-lg border bg-white transition"
                                                aria-label="Otvori fotografiju {{ $index + 1 }} u uvećanom prikazu"
                                            >
                                                <img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}" class="h-full w-full object-cover">
                                            </button>
                                        @endforeach
                                    </div>
                                @endif

                                <div
                                    class="relative order-1 flex min-h-[45vh] min-w-0 flex-1 items-center justify-center overflow-hidden rounded-xl border border-line bg-wash sm:min-h-[55vh] lg:order-2"
                                    x-on:wheel.prevent="handleWheel($event)"
                                >
                                    @if($galleryImages->count() > 1)
                                        <button
                                            type="button"
                                            x-on:click.stop="previous()"
                                            class="absolute left-6 top-1/2 z-10 hidden h-14 w-14 -translate-y-1/2 items-center justify-center rounded-full border border-line bg-white text-2xl text-ink transition hover:border-brand/20 hover:text-brand md:flex"
                                            aria-label="Prethodna fotografija u uvećanom prikazu"
                                        >
                                            ‹
                                        </button>
                                        <button
                                            type="button"
                                            x-on:click.stop="next()"
                                            class="absolute right-6 top-1/2 z-10 hidden h-14 w-14 -translate-y-1/2 items-center justify-center rounded-full border border-line bg-white text-2xl text-ink transition hover:border-brand/20 hover:text-brand md:flex"
                                            aria-label="Sledeća fotografija u uvećanom prikazu"
                                        >
                                            ›
                                        </button>
                                    @endif

                                    <img
                                        src="{{ $galleryImages->first()['url'] }}"
                                        alt="{{ $galleryImages->first()['alt'] }}"
                                        x-bind:src="activeImage.url"
                                        x-bind:alt="activeImage.alt"
                                        x-bind:style="zoomStyle"
                                        class="max-h-full max-w-full object-contain transition duration-200 ease-out"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
</div>
