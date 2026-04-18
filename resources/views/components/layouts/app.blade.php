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
    $notificationsCount = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0;
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
                <div class="container-frame flex items-center justify-between gap-4 py-4">
                    <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-300 via-amber-400 to-orange-500 text-base font-black text-slate-950 shadow-lg shadow-amber-500/20">AIQ</div>
                        <div>
                            <div class="font-display text-lg font-bold tracking-tight text-white">AutoIQ</div>
                            <div class="text-xs uppercase tracking-[0.28em] text-slate-400">Pametnije tržište vozila</div>
                        </div>
                    </a>

                    <nav class="hidden items-center gap-2 lg:flex">
                        <a href="{{ route('home') }}" wire:navigate class="btn-ghost {{ request()->routeIs('home') ? 'border border-white/10 bg-white/8 text-white' : '' }}">Početna</a>
                        <a href="{{ route('blog.index') }}" wire:navigate class="btn-ghost {{ request()->routeIs('blog.*') ? 'border border-white/10 bg-white/8 text-white' : '' }}">Blog</a>
                        <a href="{{ route('listings.index') }}" wire:navigate class="btn-ghost {{ request()->routeIs('listings.*') ? 'border border-white/10 bg-white/8 text-white' : '' }}">Oglasi</a>
                        @auth
                            <a href="{{ route('listings.create') }}" wire:navigate class="btn-ghost {{ request()->routeIs('listings.create') ? 'border border-white/10 bg-white/8 text-white' : '' }}">Dodaj oglas</a>
                            <a href="{{ route('account.dashboard') }}" wire:navigate class="btn-ghost {{ request()->routeIs('account.*') ? 'border border-white/10 bg-white/8 text-white' : '' }}">
                                Moj nalog
                                @if($notificationsCount > 0)
                                    <span class="ml-2 inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-amber-400 px-1.5 text-[11px] font-bold text-slate-950">{{ $notificationsCount }}</span>
                                @endif
                            </a>
                            @can('view admin dashboard')
                                <a href="{{ route('admin.dashboard') }}" wire:navigate class="btn-ghost {{ request()->routeIs('admin.*') ? 'border border-white/10 bg-white/8 text-white' : '' }}">Upravljanje</a>
                            @endcan
                        @endauth
                    </nav>

                    <div class="flex items-center gap-2">
                        @auth
                            <div class="hidden rounded-2xl border border-white/10 bg-white/5 px-4 py-2 text-right sm:block">
                                <div class="text-sm font-semibold text-white">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-slate-400">{{ auth()->user()->roleLabel() }}</div>
                            </div>
                            <a href="{{ route('logout') }}" wire:navigate class="btn-secondary">Odjava</a>
                        @else
                            <a href="{{ route('login') }}" wire:navigate class="btn-ghost">Prijava</a>
                            <a href="{{ route('register') }}" wire:navigate class="btn-primary">Otvori nalog</a>
                        @endauth
                    </div>
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
