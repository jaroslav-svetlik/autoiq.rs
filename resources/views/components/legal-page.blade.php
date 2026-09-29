@props(['title', 'description'])

<x-layouts.app :title="$title.' | AutoIQ'" :meta="['description' => $description, 'canonical' => url()->current()]">
    <div class="mx-auto max-w-4xl space-y-6 sm:space-y-8">
        <nav aria-label="Putanja stranice" class="flex flex-wrap items-center gap-2 text-sm text-muted">
            <a href="{{ route('home') }}" wire:navigate class="hover:text-primary">Početna</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page" class="text-ink">{{ $title }}</span>
        </nav>

        <header class="space-y-4">
            <span class="chip">AutoIQ.rs</span>
            <h1 class="font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">{{ $title }}</h1>
            <p class="max-w-2xl text-base leading-7 text-muted">{{ $description }}</p>
            <p class="text-xs text-muted">Poslednja izmena: <time datetime="2026-09-29">29. septembar 2026.</time></p>
        </header>

        <nav aria-label="Informacije o korišćenju sajta" class="flex flex-wrap gap-2">
            <a href="{{ route('privacy') }}" wire:navigate @if(request()->routeIs('privacy')) aria-current="page" @endif class="{{ request()->routeIs('privacy') ? 'btn-primary' : 'btn-secondary' }}">Politika privatnosti</a>
            <a href="{{ route('terms') }}" wire:navigate @if(request()->routeIs('terms')) aria-current="page" @endif class="{{ request()->routeIs('terms') ? 'btn-primary' : 'btn-secondary' }}">Uslovi korišćenja</a>
        </nav>

        <article class="legal-document panel space-y-8 p-6 sm:p-10" aria-label="{{ $title }}">
            {{ $slot }}
        </article>

        <aside class="panel-soft flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-ink">Imate pitanje?</h2>
                <p class="mt-1 text-sm leading-6 text-muted">Pišite nam za dodatne informacije ili pomoć oko svojih podataka.</p>
            </div>
            <a href="{{ route('contact') }}" wire:navigate class="btn-secondary shrink-0">Kontaktirajte nas <x-icon name="arrow" /></a>
        </aside>
    </div>
</x-layouts.app>
