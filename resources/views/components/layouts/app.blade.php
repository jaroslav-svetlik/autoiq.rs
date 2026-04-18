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
    $image = $meta['image'] ?? 'https://placehold.co/1200x630/0f172a/f8fafc?text=AutoIQ';
    $user = auth()->user();
    $notificationsCount = $user ? $user->unreadNotifications()->count() : 0;
    $userInitials = $user
        ? collect(explode(' ', trim($user->name)))
            ->filter()
            ->take(2)
            ->map(fn (string $namePart) => mb_strtoupper(mb_substr($namePart, 0, 1)))
            ->implode('')
        : '';
    $navActiveClass = 'border border-white/10 bg-white/8 text-white';
    $listingBrowseIsActive = request()->routeIs('listings.index', 'listings.show');
    $listingCreateIsActive = request()->routeIs('listings.create');
@endphp

<!DOCTYPE html>
<html lang="sr">
    <head>
        <meta charset="utf-8">
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
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
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
        <div class="shell">
            <header class="sticky top-0 z-40 border-b border-white/8 bg-slate-950/70 backdrop-blur-xl">
                <div class="container-frame flex items-center justify-between gap-4 py-3 sm:py-4">
                    <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-300 via-amber-400 to-orange-500 text-base font-black text-slate-950 shadow-lg shadow-amber-500/20">AIQ</div>
                        <div>
                            <div class="font-display text-lg font-bold tracking-tight text-white">AutoIQ</div>
                            <div class="hidden text-xs uppercase tracking-[0.28em] text-slate-400 sm:block">Pametnije tržište vozila</div>
                        </div>
                    </a>

                    <nav class="hidden items-center gap-2 lg:flex" data-desktop-primary-nav>
                        <a href="{{ route('home') }}" wire:navigate class="btn-ghost {{ request()->routeIs('home') ? $navActiveClass : '' }}">Početna</a>
                        <a href="{{ route('blog.index') }}" wire:navigate class="btn-ghost {{ request()->routeIs('blog.*') ? $navActiveClass : '' }}">Blog</a>
                        <a href="{{ route('listings.index') }}" wire:navigate class="btn-ghost {{ $listingBrowseIsActive ? $navActiveClass : '' }}">Oglasi</a>
                        <a href="{{ route('contact') }}" wire:navigate class="btn-ghost {{ request()->routeIs('contact') ? $navActiveClass : '' }}">Kontakt</a>
                    </nav>

                    <div class="hidden items-center gap-2 lg:flex">
                        <a href="{{ route('listings.create') }}" wire:navigate class="btn-primary gap-2 {{ $listingCreateIsActive ? 'ring-2 ring-amber-200/40' : '' }}" data-header-add-listing>
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                            Dodaj oglas
                        </a>

                        @auth
                            <details class="group relative" data-nav-menu>
                                <summary class="btn-secondary cursor-pointer gap-2 pr-3" data-user-menu>
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/10 text-xs font-bold text-white">{{ $userInitials }}</span>
                                    <span class="hidden xl:inline">Moj nalog</span>
                                    @if($notificationsCount > 0)
                                        <span class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-amber-400 px-1.5 text-[11px] font-bold text-slate-950">{{ $notificationsCount }}</span>
                                    @endif
                                    <svg class="h-4 w-4 text-slate-300 transition group-open:rotate-180" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </summary>

                                <div class="absolute right-0 top-full z-50 mt-3 w-72 rounded-2xl border border-white/10 bg-slate-950/95 p-3 shadow-2xl shadow-slate-950/50 backdrop-blur-xl">
                                    <div class="border-b border-white/8 px-3 pb-3">
                                        <div class="truncate text-sm font-semibold text-white">{{ $user->name }}</div>
                                        <div class="mt-1 truncate text-xs text-slate-400">{{ $user->email }}</div>
                                        <div class="mt-2 inline-flex rounded-full border border-white/10 bg-white/6 px-2.5 py-1 text-xs font-semibold text-cyan-200">{{ $user->roleLabel() }}</div>
                                    </div>

                                    <div class="grid gap-1 py-3">
                                        <a href="{{ route('account.dashboard') }}" wire:navigate class="btn-ghost justify-start {{ request()->routeIs('account.*') ? $navActiveClass : '' }}">Profil i oglasi</a>
                                        @if($notificationsCount > 0)
                                            <a href="{{ route('account.dashboard', ['tab' => 'obavestenja']) }}" wire:navigate class="btn-ghost justify-between">
                                                Obaveštenja
                                                <span class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-amber-400 px-1.5 text-[11px] font-bold text-slate-950">{{ $notificationsCount }}</span>
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
                            <a href="{{ route('login') }}" wire:navigate class="btn-ghost">Prijava</a>
                        @endauth
                    </div>

                    <details class="relative lg:hidden" data-nav-menu data-mobile-menu>
                        <summary class="flex h-11 w-11 cursor-pointer items-center justify-center rounded-2xl border border-white/10 bg-white/6 text-white transition hover:bg-white/10" aria-label="Otvori meni">
                            <span class="sr-only">Meni</span>
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M5 7h14M5 12h14M5 17h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </summary>

                        <div class="absolute right-0 top-full z-50 mt-3 w-[min(22rem,calc(100vw-2rem))] rounded-2xl border border-white/10 bg-slate-950/95 p-4 shadow-2xl shadow-slate-950/50 backdrop-blur-xl">
                            <a href="{{ route('listings.create') }}" wire:navigate class="btn-primary w-full gap-2 {{ $listingCreateIsActive ? 'ring-2 ring-amber-200/40' : '' }}" data-mobile-add-listing>
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                </svg>
                                Dodaj oglas
                            </a>

                            <nav class="mt-4 grid gap-2" data-mobile-primary-nav>
                                <a href="{{ route('home') }}" wire:navigate class="btn-ghost justify-start {{ request()->routeIs('home') ? $navActiveClass : '' }}">Početna</a>
                                <a href="{{ route('blog.index') }}" wire:navigate class="btn-ghost justify-start {{ request()->routeIs('blog.*') ? $navActiveClass : '' }}">Blog</a>
                                <a href="{{ route('listings.index') }}" wire:navigate class="btn-ghost justify-start {{ $listingBrowseIsActive ? $navActiveClass : '' }}">Oglasi</a>
                                <a href="{{ route('contact') }}" wire:navigate class="btn-ghost justify-start {{ request()->routeIs('contact') ? $navActiveClass : '' }}">Kontakt</a>
                            </nav>

                            @auth
                                <div class="mt-4 border-t border-white/8 pt-4">
                                    <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 p-3">
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/10 text-xs font-bold text-white">{{ $userInitials }}</span>
                                        <div class="min-w-0">
                                            <div class="truncate text-sm font-semibold text-white">{{ $user->name }}</div>
                                            <div class="truncate text-xs text-slate-400">{{ $user->roleLabel() }}</div>
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
                                <div class="mt-4 grid gap-2 border-t border-white/8 pt-4">
                                    <a href="{{ route('login') }}" wire:navigate class="btn-secondary w-full">Prijava</a>
                                </div>
                            @endauth
                        </div>
                    </details>
                </div>
            </header>

            @if (session('status'))
                <div class="container-frame pt-6">
                    <div class="rounded-2xl border border-emerald-400/30 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-100">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            <main class="container-frame py-8 sm:py-10">
                {{ $slot }}
            </main>

            <footer class="border-t border-white/8 bg-slate-950/40 py-10">
                <div class="container-frame flex flex-col gap-6 text-sm text-slate-400 md:flex-row md:items-center md:justify-between">
                    <div class="max-w-xl">
                        <div class="font-display text-lg font-semibold text-white">AutoIQ</div>
                        <p class="mt-2 leading-7">Pametna platforma za kupovinu i prodaju automobila u Srbiji: oglasi, analiza cena, favoriti, alarmi i AutoIQ procena.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('blog.index') }}" wire:navigate class="btn-ghost">Blog</a>
                        <a href="{{ route('listings.index') }}" wire:navigate class="btn-ghost">Tržište</a>
                        <a href="{{ route('contact') }}" wire:navigate class="btn-ghost">Kontakt</a>
                        @auth
                            <a href="{{ route('account.dashboard') }}" wire:navigate class="btn-ghost">Profil</a>
                        @endauth
                        <a href="{{ route('sitemap') }}" class="btn-ghost">Mapa sajta</a>
                    </div>
                </div>
            </footer>
        </div>

        @livewireScripts
    </body>
</html>
