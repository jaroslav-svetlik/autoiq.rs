@php
    $paragraphs = collect(preg_split('/\n\s*\n/', trim((string) $blogPost->content)))
        ->filter(fn ($paragraph) => filled($paragraph))
        ->values();
@endphp

<div class="space-y-12">
    <section class="space-y-8">
        <div class="flex flex-wrap items-center gap-3 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">
            <a href="{{ route('blog.index') }}" wire:navigate class="text-slate-400 transition hover:text-white">Blog</a>
            @if($blogPost->category)
                <span>·</span>
                <a href="{{ route('blog.index', ['tema' => $blogPost->category]) }}" wire:navigate class="text-cyan-300 transition hover:text-cyan-200">{{ $blogPost->category }}</a>
            @endif
            <span>·</span>
            <span>{{ optional($blogPost->published_at)->format('d.m.Y') }}</span>
            <span>·</span>
            <span>{{ $blogPost->readingTimeLabel() }}</span>
        </div>

        <div class="grid gap-8 xl:grid-cols-[1.15fr_0.85fr] xl:items-end">
            <div class="space-y-6">
                <h1 class="font-display max-w-4xl text-5xl font-bold leading-none tracking-tight text-white sm:text-6xl">
                    {{ $blogPost->title }}
                </h1>
                <p class="max-w-3xl text-lg leading-8 text-slate-300">{{ $blogPost->excerptText() }}</p>

                @if(!empty($blogPost->tags))
                    <div class="flex flex-wrap gap-3">
                        @foreach($blogPost->tags as $tag)
                            <span class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-slate-200">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="panel-soft p-6 sm:p-7">
                <div class="data-kicker">Autor</div>
                <div class="mt-3 space-y-3">
                    <div class="font-display text-3xl font-bold text-white">{{ $blogPost->author_name }}</div>
                    <p class="text-sm leading-7 text-slate-300">
                        AutoIQ tim prati tržišne signale, ponašanje cena i obrasce kupovine kako bi odluke oko automobila bile manje rizične i više zasnovane na podacima.
                    </p>
                </div>
            </div>
        </div>

        <div class="panel overflow-hidden bg-slate-950/70 p-2 sm:p-3">
            <div class="aspect-[3/2] overflow-hidden rounded-[1.25rem] bg-slate-950">
                <img
                    src="{{ $blogPost->coverImageUrl() }}"
                    alt="{{ $blogPost->cover_image_alt ?: $blogPost->title }}"
                    class="h-full w-full object-contain"
                >
            </div>
        </div>
    </section>

    <section class="grid gap-8 xl:grid-cols-[1fr_340px] xl:items-start">
        <article class="panel p-6 sm:p-8 lg:p-10">
            <div class="space-y-8">
                @foreach($paragraphs as $index => $paragraph)
                    <div class="space-y-4">
                        @if($index === 0)
                            <div class="data-kicker">Uvod</div>
                        @endif
                        <p class="text-base leading-8 text-slate-200 sm:text-lg">{{ $paragraph }}</p>
                    </div>

                    @if($index === 1 && $contextualLinks->isNotEmpty())
                        <nav aria-label="Povezani vodiči u tekstu" class="rounded-[1.75rem] border border-amber-300/20 bg-amber-300/[0.07] p-5 sm:p-6">
                            <div class="data-kicker text-amber-200">Povezani vodiči</div>
                            <h2 class="mt-2 font-display text-2xl font-bold text-white">Pročitaj pre sledećeg oglasa</h2>
                            <div class="mt-5 grid gap-3">
                                @foreach($contextualLinks as $link)
                                    <a href="{{ $link['url'] }}" wire:navigate class="group rounded-2xl border border-white/10 bg-slate-950/40 p-4 transition hover:border-amber-300/35 hover:bg-slate-950/70">
                                        <div class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-[0.18em] text-amber-200">
                                            @if($link['category'])
                                                <span>{{ $link['category'] }}</span>
                                            @endif
                                            <span>·</span>
                                            <span>Interni vodič</span>
                                        </div>
                                        <div class="mt-2 font-display text-xl font-bold leading-tight text-white transition group-hover:text-amber-100">{{ $link['title'] }}</div>
                                        <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-300">{{ $link['description'] }}</p>
                                    </a>
                                @endforeach
                            </div>
                        </nav>
                    @endif
                @endforeach
            </div>
        </article>

        <aside class="space-y-6 xl:sticky xl:top-28">
            @if(!empty($blogPost->highlights))
                <div class="panel p-6">
                    <div class="data-kicker">Ključne poruke</div>
                    <div class="mt-5 space-y-3">
                        @foreach($blogPost->highlights as $highlight)
                            <div class="panel-soft p-4 text-sm leading-7 text-slate-200">{{ $highlight }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="panel p-6">
                <div class="data-kicker">Pretraga oglasa</div>
                <h2 class="mt-2 font-display text-3xl font-bold text-white">Pređi iz čitanja u proveru tržišta</h2>
                <p class="mt-3 text-sm leading-7 text-slate-300">
                    Ovi linkovi vode na povezane AutoIQ filtere kako bi tekst odmah mogao da se proveri kroz aktuelne oglase.
                </p>
                @if($marketLinks->isNotEmpty())
                    <div class="mt-5 space-y-3">
                        @foreach($marketLinks as $index => $link)
                            <a href="{{ $link['url'] }}" wire:navigate class="{{ $index === 0 ? 'btn-primary' : 'btn-secondary' }} block text-center">
                                {{ $link['label'] }}
                            </a>
                            <p class="-mt-1 text-xs leading-6 text-slate-400">{{ $link['description'] }}</p>
                        @endforeach
                    </div>
                @else
                    <div class="mt-5 flex flex-col gap-3">
                        <a href="{{ route('listings.index') }}" wire:navigate class="btn-primary text-center">Pregledaj oglase</a>
                        <a href="{{ route('home') }}" wire:navigate class="btn-secondary text-center">Nazad na početnu</a>
                    </div>
                @endif
            </div>

            @if($blogPost->category)
                <div class="panel p-6">
                    <div class="data-kicker">Tema</div>
                    <h2 class="mt-2 font-display text-2xl font-bold text-white">{{ $blogPost->category }}</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-300">
                        Svi tekstovi iz ove teme čuvaju kontekst na jednom mestu i pomažu da porediš slične odluke pre kupovine ili prodaje.
                    </p>
                    <a href="{{ route('blog.index', ['tema' => $blogPost->category]) }}" wire:navigate class="btn-secondary mt-5 block text-center">
                        Otvori celu temu
                    </a>
                </div>
            @endif
        </aside>
    </section>

    <section class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="data-kicker">Povezani članci</div>
                <h2 class="section-title mt-2">Nastavi dalje kroz AutoIQ Blog</h2>
            </div>
            <a href="{{ route('blog.index') }}" wire:navigate class="btn-secondary">Svi članci</a>
        </div>

        @if($relatedPosts->isNotEmpty())
            <div class="grid gap-6 lg:grid-cols-3">
                @foreach($relatedPosts as $post)
                    <x-blog-post-card :post="$post" compact />
                @endforeach
            </div>
        @else
            <div class="panel p-8 text-slate-300">Kako novi tekstovi budu objavljivani, ovde će se pojavljivati naredni vodiči i analize.</div>
        @endif
    </section>
</div>
