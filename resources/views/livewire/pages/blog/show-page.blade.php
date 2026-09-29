@php
    $blocks = $blogPost->contentBlocks();
    $paragraphCount = 0;
    $shownContextualLinks = false;
@endphp

<div class="space-y-12">
    <section class="space-y-8">
        <div class="flex flex-wrap items-center gap-3 text-xs font-semibold uppercase tracking-[0.22em] text-muted">
            <a href="{{ route('blog.index') }}" wire:navigate class="text-muted transition hover:text-ink">Blog</a>
            @if($blogPost->category)
                <span>·</span>
                <a href="{{ route('blog.index', ['tema' => $blogPost->category]) }}" wire:navigate class="text-brand transition hover:text-brand">{{ $blogPost->category }}</a>
            @endif
            <span>·</span>
            <span>{{ optional($blogPost->published_at)->format('d.m.Y') }}</span>
            <span>·</span>
            <span>{{ $blogPost->readingTimeLabel() }}</span>
        </div>

        <div class="grid gap-8 xl:grid-cols-[1.15fr_0.85fr] xl:items-end">
            <div class="space-y-6">
                <h1 class="font-display max-w-4xl text-5xl font-bold leading-none tracking-tight text-ink sm:text-6xl">
                    {{ $blogPost->title }}
                </h1>
                <p class="max-w-3xl text-lg leading-8 text-muted">{{ $blogPost->excerptText() }}</p>

                @if(!empty($blogPost->tags))
                    <div class="flex flex-wrap gap-3">
                        @foreach($blogPost->tags as $tag)
                            <span class="rounded-full border border-line bg-wash px-4 py-2 text-sm font-medium text-ink">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif

                @if($searchIntentBrief)
                    <div class="grid gap-3 rounded-xl border border-brand/20 bg-brand/5 p-5 sm:grid-cols-3 sm:p-6">
                        <div class="sm:col-span-3">
                            <div class="data-kicker text-brand">{{ $searchIntentBrief['label'] }}</div>
                            <h2 class="mt-2 font-display text-2xl font-bold leading-tight text-ink">{{ $searchIntentBrief['heading'] }}</h2>
                        </div>
                        @foreach($searchIntentBrief['items'] as $item)
                            <div class="rounded-lg border border-line bg-white p-4 text-sm leading-7 text-ink">{{ $item }}</div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="panel-soft p-6 sm:p-7">
                <div class="data-kicker">Autor</div>
                <div class="mt-3 space-y-3">
                    <div class="font-display text-3xl font-bold text-ink">{{ $blogPost->author_name }}</div>
                    <p class="text-sm leading-7 text-muted">
                        AutoIQ tim prati tržišne signale, ponašanje cena i obrasce kupovine kako bi odluke oko automobila bile manje rizične i više zasnovane na podacima.
                    </p>
                </div>
            </div>
        </div>

        <div class="panel overflow-hidden bg-white p-2 sm:p-3">
            <div class="aspect-[3/2] overflow-hidden rounded-[1.25rem] bg-white">
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
                @foreach($blocks as $index => $block)
                    @if($block['type'] === 'heading')
                        @if(($block['level'] ?? 2) === 3)
                            <h3 class="font-display text-2xl font-bold leading-tight text-ink">{{ $block['text'] }}</h3>
                        @else
                            <h2 class="font-display text-3xl font-bold leading-tight text-ink">{{ $block['text'] }}</h2>
                        @endif
                    @elseif($block['type'] === 'faq')
                        <details class="group rounded-xl border border-brand/20 bg-brand/5 p-5 sm:p-6">
                            <summary class="cursor-pointer font-display text-xl font-bold leading-tight text-ink marker:text-brand">
                                {{ $block['question'] }}
                            </summary>
                            <p class="mt-4 text-base leading-8 text-ink">{{ $block['answer'] }}</p>
                        </details>
                    @else
                        @php($paragraphCount++)
                        <div class="space-y-4">
                            @if($paragraphCount === 1)
                                <div class="data-kicker">Uvod</div>
                            @endif
                            <p class="text-base leading-8 text-ink sm:text-lg">{{ $block['text'] }}</p>
                        </div>
                    @endif

                    @if(!$shownContextualLinks && $paragraphCount >= 2 && $contextualLinks->isNotEmpty())
                        @php($shownContextualLinks = true)
                        <nav aria-label="Povezani vodiči u tekstu" class="rounded-xl border border-brand/20 bg-brand/5 p-5 sm:p-6">
                            <div class="data-kicker text-brand">Povezani vodiči</div>
                            <h2 class="mt-2 font-display text-2xl font-bold text-ink">Pročitaj pre sledećeg oglasa</h2>
                            <div class="mt-5 grid gap-3">
                                @foreach($contextualLinks as $link)
                                    <a href="{{ $link['url'] }}" wire:navigate class="group rounded-lg border border-line bg-white p-4 transition hover:border-brand/20 hover:bg-white">
                                        <div class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-[0.18em] text-brand">
                                            @if($link['category'])
                                                <span>{{ $link['category'] }}</span>
                                            @endif
                                            <span>·</span>
                                            <span>Interni vodič</span>
                                        </div>
                                        <div class="mt-2 font-display text-xl font-bold leading-tight text-ink transition group-hover:text-brand">{{ $link['title'] }}</div>
                                        <p class="mt-2 line-clamp-2 text-sm leading-6 text-muted">{{ $link['description'] }}</p>
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
                            <div class="panel-soft p-4 text-sm leading-7 text-ink">{{ $highlight }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($topicHubPosts->isNotEmpty())
                <div class="panel p-6">
                    <div class="data-kicker">Glavni vodiči</div>
                    <h2 class="mt-2 font-display text-2xl font-bold text-ink">Poveži ovaj izbor sa širom računicom</h2>

                    <div class="mt-5 space-y-3">
                        @foreach($topicHubPosts as $post)
                            <a href="{{ route('blog.show', $post) }}" wire:navigate class="group block rounded-lg border border-line bg-wash p-4 transition hover:border-brand/20 hover:bg-wash">
                                <div class="text-xs font-semibold uppercase tracking-[0.18em] text-brand">{{ $post->category }}</div>
                                <div class="mt-2 text-sm font-semibold leading-6 text-ink transition group-hover:text-brand">{{ $post->title }}</div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="panel p-6">
                <div class="data-kicker">Pretraga oglasa</div>
                <h2 class="mt-2 font-display text-3xl font-bold text-ink">Pređi iz čitanja u proveru tržišta</h2>
                <p class="mt-3 text-sm leading-7 text-muted">
                    Ovi linkovi vode na povezane AutoIQ filtere kako bi tekst odmah mogao da se proveri kroz aktuelne oglase.
                </p>
                @if($marketLinks->isNotEmpty())
                    <div class="mt-5 space-y-3">
                        @foreach($marketLinks as $index => $link)
                            <a href="{{ $link['url'] }}" wire:navigate class="{{ $index === 0 ? 'btn-primary' : 'btn-secondary' }} block text-center">
                                {{ $link['label'] }}
                            </a>
                            <p class="-mt-1 text-xs leading-6 text-muted">{{ $link['description'] }}</p>
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
                    <h2 class="mt-2 font-display text-2xl font-bold text-ink">{{ $blogPost->category }}</h2>
                    <p class="mt-3 text-sm leading-7 text-muted">
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
            <div class="panel p-8 text-muted">Kako novi tekstovi budu objavljivani, ovde će se pojavljivati naredni vodiči i analize.</div>
        @endif
    </section>
</div>
