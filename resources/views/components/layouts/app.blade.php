@props([
    'title' => 'AutoIQ',
    'meta' => [],
    'jsonLd' => [],
])

@php
    $description = $meta['description'] ?? 'Pametna platforma za kupovinu automobila u Srbiji.';
    $canonical = $meta['canonical'] ?? request()->fullUrl();
    $robots = $meta['robots'] ?? 'index,follow';
    $type = $meta['type'] ?? 'website';
    $image = $meta['image'] ?? asset('images/alpine-drive.webp');
    $user = auth()->user();
    $notificationsCount = $user ? $user->unreadNotifications()->count() : 0;
    $userInitials = $user
        ? collect(explode(' ', trim($user->name)))
            ->filter()
            ->take(2)
            ->map(fn (string $namePart) => mb_strtoupper(mb_substr($namePart, 0, 1)))
            ->implode('')
        : '';
    $navActiveClass = 'is-active';
    $listingBrowseIsActive = request()->routeIs('home', 'listings.index', 'listings.model', 'listings.show');
    $listingCreateIsActive = request()->routeIs('listings.create');
@endphp

<!DOCTYPE html>
<html lang="sr" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="color-scheme" content="light">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title }}</title>
        <meta name="description" content="{{ $description }}">
        <meta name="robots" content="{{ $robots }}">
        <link rel="canonical" href="{{ $canonical }}">
        <meta property="og:type" content="{{ $type }}">
        <meta property="og:site_name" content="AutoIQ">
        <meta property="og:title" content="{{ $title }}">
        <meta property="og:description" content="{{ $description }}">
        <meta property="og:url" content="{{ $canonical }}">
        <meta property="og:image" content="{{ $image }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $title }}">
        <meta name="twitter:description" content="{{ $description }}">
        <meta name="twitter:image" content="{{ $image }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        @production
            <script async src="https://www.googletagmanager.com/gtag/js?id=G-1TMX1CMMRS"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                window.gtag = gtag;
                window.gtagMeasurementId = 'G-1TMX1CMMRS';
                window.gtagLastPagePath = window.location.pathname + window.location.search;

                gtag('js', new Date());
                gtag('config', 'G-1TMX1CMMRS');
            </script>
        @endproduction
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @foreach($jsonLd as $structuredData)
            <script type="application/ld+json">{!! json_encode($structuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
        @endforeach
    </head>
    <body>
        <a class="skip-link" href="#main-content">Preskoči na sadržaj</a>
        <div class="shell">
            <header class="site-header">
                <div class="container-frame header-inner">
                    <a href="{{ route('home') }}" wire:navigate class="brand-link" aria-label="AutoIQ.rs — početna">
                        <x-brand />
                    </a>
                    <nav class="desktop-nav" aria-label="Glavna navigacija" data-desktop-primary-nav>
                        <a href="{{ route('home') }}" wire:navigate class="nav-link {{ $listingBrowseIsActive ? 'is-active' : '' }}">Oglasi</a>
                        <a href="{{ route('blog.index') }}" wire:navigate class="nav-link {{ request()->routeIs('blog.*') ? 'is-active' : '' }}">Blog</a>
                        <a href="{{ route('contact') }}" wire:navigate class="nav-link {{ request()->routeIs('contact') ? 'is-active' : '' }}">Kontakt</a>
                    </nav>
                    <div class="header-actions">
                        <a href="{{ route('home') }}#pretraga" class="icon-button" aria-label="Pretraži oglase"><x-icon name="search" /></a>
                        <a href="{{ auth()->check() ? route('account.dashboard', ['tab' => 'favoriti']) : route('login') }}" wire:navigate class="icon-button" aria-label="Sačuvani oglasi"><x-icon name="heart" /></a>
                        @auth
                            <details class="group relative" data-nav-menu>
                                <summary class="account-trigger" data-user-menu>
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-wash text-xs font-bold text-ink">{{ $userInitials }}</span>
                                    <span class="hidden xl:inline">Moj nalog</span>
                                    @if($notificationsCount > 0)
                                        <span class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-brand px-1.5 text-[11px] font-bold text-white">{{ $notificationsCount }}</span>
                                    @endif
                                    <svg class="h-4 w-4 text-muted transition group-open:rotate-180" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </summary>

                                <div class="site-dropdown w-72 p-3">
                                    <div class="border-b border-line px-3 pb-3">
                                        <div class="truncate text-sm font-semibold text-ink">{{ $user->name }}</div>
                                        <div class="mt-1 truncate text-xs text-muted">{{ $user->email }}</div>
                                        <div class="mt-2 inline-flex rounded-full border border-line bg-wash px-2.5 py-1 text-xs font-semibold text-brand">{{ $user->roleLabel() }}</div>
                                    </div>

                                    <div class="grid gap-1 py-3">
                                        <a href="{{ route('account.dashboard') }}" wire:navigate class="btn-ghost justify-start {{ request()->routeIs('account.*') ? $navActiveClass : '' }}">Profil i oglasi</a>
                                        @if($notificationsCount > 0)
                                            <a href="{{ route('account.dashboard', ['tab' => 'obavestenja']) }}" wire:navigate class="btn-ghost justify-between">
                                                Obaveštenja
                                                <span class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-brand px-1.5 text-[11px] font-bold text-white">{{ $notificationsCount }}</span>
                                            </a>
                                        @endif
                                        @can('view admin dashboard')
                                            <a href="{{ route('admin.dashboard') }}" wire:navigate class="btn-ghost justify-start {{ request()->routeIs('admin.*') ? $navActiveClass : '' }}">Upravljanje</a>
                                        @endcan
                                    </div>

                                    <a href="{{ route('logout') }}" wire:navigate class="btn-secondary w-full">Odjava</a>
                                </div>
                            </details>
                        @else
                            <a href="{{ route('login') }}" wire:navigate class="account-trigger"><x-icon name="user" /><span>Moj nalog</span></a>
                        @endauth
                        <a href="{{ route('listings.create') }}" wire:navigate class="btn-primary header-cta" data-header-add-listing><x-icon name="plus" />Postavi oglas</a>
                    </div>
                    <details class="relative lg:hidden" data-nav-menu data-mobile-menu>
                        <summary class="flex h-11 w-11 cursor-pointer items-center justify-center rounded-2xl border border-line bg-wash text-ink transition hover:bg-wash" aria-label="Otvori meni">
                            <span class="sr-only">Meni</span>
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M5 7h14M5 12h14M5 17h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </summary>

                        <div class="site-dropdown w-[min(22rem,calc(100vw-2rem))] p-4">
                            <a href="{{ route('listings.create') }}" wire:navigate class="btn-primary w-full gap-2 {{ $listingCreateIsActive ? 'ring-2 ring-brand/20' : '' }}" data-mobile-add-listing>
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                </svg>
                                Postavi oglas
                            </a>

                            <nav class="mt-4 grid gap-2" aria-label="Mobilna navigacija" data-mobile-primary-nav>
                                <a href="{{ route('home') }}" wire:navigate class="btn-ghost justify-start {{ $listingBrowseIsActive ? $navActiveClass : '' }}">Oglasi</a>
                                <a href="{{ route('blog.index') }}" wire:navigate class="btn-ghost justify-start {{ request()->routeIs('blog.*') ? $navActiveClass : '' }}">Blog</a>
                                <a href="{{ route('contact') }}" wire:navigate class="btn-ghost justify-start {{ request()->routeIs('contact') ? $navActiveClass : '' }}">Kontakt</a>
                            </nav>

                            @auth
                                <div class="mt-4 border-t border-line pt-4">
                                    <div class="flex items-center gap-3 rounded-2xl border border-line bg-wash p-3">
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-wash text-xs font-bold text-ink">{{ $userInitials }}</span>
                                        <div class="min-w-0">
                                            <div class="truncate text-sm font-semibold text-ink">{{ $user->name }}</div>
                                            <div class="truncate text-xs text-muted">{{ $user->roleLabel() }}</div>
                                        </div>
                                    </div>

                                    <div class="mt-3 grid gap-2">
                                        <a href="{{ route('account.dashboard') }}" wire:navigate class="btn-ghost justify-start {{ request()->routeIs('account.*') ? $navActiveClass : '' }}">Profil i oglasi</a>
                                        @can('view admin dashboard')
                                            <a href="{{ route('admin.dashboard') }}" wire:navigate class="btn-ghost justify-start {{ request()->routeIs('admin.*') ? $navActiveClass : '' }}">Upravljanje</a>
                                        @endcan
                                        <a href="{{ route('logout') }}" wire:navigate class="btn-secondary w-full">Odjava</a>
                                    </div>
                                </div>
                            @else
                                <div class="mt-4 grid gap-2 border-t border-line pt-4">
                                    <a href="{{ route('login') }}" wire:navigate class="btn-secondary w-full">Prijava</a>
                                </div>
                            @endauth
                        </div>
                    </details>
                </div>
            </header>

            @if (session('status'))
                <div class="container-frame pt-6">
                    <div class="rounded-2xl border border-emerald-400/30 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-700">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            <main id="main-content" class="site-main {{ request()->routeIs('home', 'listings.index', 'listings.model', 'blog.index') ? '' : 'container-frame py-8 sm:py-10' }}">
                {{ $slot }}
            </main>

            <footer class="site-footer">
                <div class="container-frame footer-grid">
                    <div class="footer-about">
                        <a href="{{ route('home') }}" wire:navigate aria-label="AutoIQ.rs — početna"><x-brand /></a>
                        <p>Pametniji izbor na svakom putu.</p>
                        <p>AutoIQ.rs je platforma za kupovinu i prodaju automobila u Srbiji. Povezujemo ljude, automobile i bolje prilike.</p>
                        <small>© {{ date('Y') }} AutoIQ.rs. Sva prava zadržana.</small>
                    </div>
                    <div><h2>Brzi linkovi</h2>
                        <a href="{{ route('home') }}" wire:navigate>Oglasi</a>
                        <a href="{{ route('blog.index') }}" wire:navigate>Blog i saveti</a>
                        <a href="{{ route('privacy') }}" wire:navigate>Politika privatnosti</a>
                        <a href="{{ route('terms') }}" wire:navigate>Uslovi korišćenja</a>
                    </div>
                    <div><h2>Za korisnike</h2>
                        <a href="{{ route('account.dashboard') }}" wire:navigate>Moj nalog</a>
                        <a href="{{ route('account.dashboard', ['tab' => 'favoriti']) }}" wire:navigate>Sačuvani oglasi</a>
                        <a href="{{ route('contact') }}" wire:navigate>Kontakt i pomoć</a>
                        <a href="{{ route('register') }}" wire:navigate>Registracija</a>
                    </div>
                    <div><h2>Za prodavce</h2>
                        <a href="{{ route('listings.create') }}" wire:navigate>Postavi oglas</a>
                        <a href="{{ route('account.dashboard', ['tab' => 'oglasi']) }}" wire:navigate>Moji oglasi</a>
                        <a href="{{ route('contact') }}" wire:navigate>Za auto kuće</a>
                        <a href="{{ route('blog.index') }}" wire:navigate>Saveti za prodaju</a>
                    </div>
                    <div class="footer-guide"><h2>Budite u toku</h2>
                        <p>Praktični vodiči, poređenja i saveti za vašu sledeću vožnju.</p>
                        <a href="{{ route('blog.index') }}" wire:navigate class="btn-secondary">Istražite AutoIQ Blog <x-icon name="arrow" /></a>
                    </div>
                </div>
            </footer>
        </div>

        @livewireScripts(['url' => url(app('livewire')->getUriPrefix()).'/livewire.min.js?csp=1'])
    </body>
</html>
