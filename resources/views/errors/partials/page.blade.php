@php
    $title = $title ?? 'AutoIQ';
    $description = $description ?? 'AutoIQ stranica trenutno nije dostupna.';
    $statusCode = $statusCode ?? '500';
    $eyebrow = $eyebrow ?? 'AutoIQ';
    $heading = $heading ?? 'Stranica trenutno nije dostupna';
    $message = $message ?? 'Pokušajte ponovo za nekoliko trenutaka.';
    $primaryAction = $primaryAction ?? ['label' => 'Nazad na početnu', 'url' => route('home')];
    $secondaryAction = $secondaryAction ?? null;
    $tertiaryAction = $tertiaryAction ?? null;
    $panelTitle = $panelTitle ?? 'Šta dalje';
    $panelItems = $panelItems ?? [];
    $canonical = $canonical ?? request()->fullUrl();
@endphp

<!DOCTYPE html>
<html lang="sr" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="color-scheme" content="light">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title }}</title>
        <meta name="description" content="{{ $description }}">
        <meta name="robots" content="noindex,nofollow">
        <link rel="canonical" href="{{ $canonical }}">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="AutoIQ">
        <meta property="og:title" content="{{ $title }}">
        <meta property="og:description" content="{{ $description }}">
        <meta property="og:url" content="{{ $canonical }}">
        <meta property="og:image" content="https://placehold.co/1200x630/0f172a/f8fafc?text=AutoIQ">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $title }}">
        <meta name="twitter:description" content="{{ $description }}">
        <meta name="twitter:image" content="https://placehold.co/1200x630/0f172a/f8fafc?text=AutoIQ">
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
    </head>
    <body>
        <div class="shell">
            <header class="border-b border-line bg-white backdrop-blur-xl">
                <div class="container-frame flex items-center justify-between gap-4 py-4">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <x-brand />
                        <span class="brand-tagline">Pametniji izbor<br>na svakom putu</span>
                    </a>

                    <nav class="hidden items-center gap-2 sm:flex" aria-label="Glavna navigacija">
                        <a href="{{ route('blog.index') }}" class="btn-ghost">Blog</a>
                        <a href="{{ route('listings.index') }}" class="btn-ghost">Oglasi</a>
                        <a href="{{ route('contact') }}" class="btn-ghost">Kontakt</a>
                    </nav>
                </div>
            </header>

            <main class="container-frame flex min-h-[calc(100vh-13rem)] items-center py-10 sm:py-14">
                <section class="grid w-full gap-6 lg:grid-cols-[1.2fr_0.8fr] lg:items-stretch">
                    <div class="panel relative overflow-hidden p-6 sm:p-8 lg:p-10">
                        <div class="absolute right-6 top-6 hidden font-display text-[7rem] font-bold leading-none text-brand/10 sm:block">{{ $statusCode }}</div>

                        <div class="relative max-w-3xl">
                            <div class="chip">{{ $eyebrow }}</div>
                            <div class="mt-6 inline-flex items-center gap-3 rounded-2xl border border-brand/20 bg-brand/5 px-4 py-2 text-sm font-bold text-brand">
                                <span class="h-2.5 w-2.5 rounded-full bg-brand"></span>
                                Status {{ $statusCode }}
                            </div>

                            <h1 class="font-display mt-6 max-w-2xl text-4xl font-bold leading-tight tracking-tight text-ink sm:text-5xl lg:text-6xl">
                                {{ $heading }}
                            </h1>
                            <p class="mt-5 max-w-2xl text-base leading-8 text-muted sm:text-lg">
                                {{ $message }}
                            </p>

                            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                                <a href="{{ $primaryAction['url'] }}" class="btn-primary">
                                    {{ $primaryAction['label'] }}
                                </a>

                                @if($secondaryAction)
                                    <a href="{{ $secondaryAction['url'] }}" class="btn-secondary">
                                        {{ $secondaryAction['label'] }}
                                    </a>
                                @endif

                                @if($tertiaryAction)
                                    <a href="{{ $tertiaryAction['url'] }}" class="btn-ghost">
                                        {{ $tertiaryAction['label'] }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <aside class="panel-soft p-6 sm:p-8">
                        <div class="data-kicker">AutoIQ vodič</div>
                        <h2 class="font-display mt-2 text-2xl font-bold text-ink">{{ $panelTitle }}</h2>

                        @if($panelItems)
                            <div class="mt-6 grid gap-4">
                                @foreach($panelItems as $item)
                                    <div class="rounded-2xl border border-line bg-white p-4">
                                        <div class="flex items-start gap-3">
                                            <span class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-brand/5 text-xs font-bold text-brand">{{ $loop->iteration }}</span>
                                            <div>
                                                <div class="font-semibold text-ink">{{ $item['title'] }}</div>
                                                <p class="mt-2 text-sm leading-7 text-muted">{{ $item['text'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </aside>
                </section>
            </main>

            <footer class="border-t border-line bg-white py-8">
                <div class="container-frame flex flex-col gap-4 text-sm text-muted sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <div class="font-display text-lg font-semibold text-ink">AutoIQ</div>
                        <p class="mt-1 leading-7">Oglasi, analiza cena i pametnija procena polovnih automobila u Srbiji.</p>
                    </div>
                    <a href="{{ route('sitemap') }}" class="btn-ghost self-start sm:self-auto">Mapa sajta</a>
                </div>
            </footer>
        </div>
    </body>
</html>
