<div class="space-y-14">
    <section class="grid gap-8 xl:grid-cols-[1.4fr_0.6fr] xl:items-start">
        <div class="min-w-0 space-y-6">
            <div class="space-y-4">
                <span class="chip">AutoIQ Blog</span>
                <div class="space-y-4">
                    <h1 class="font-display max-w-4xl text-4xl font-bold leading-tight tracking-tight text-white sm:text-6xl sm:leading-none">
                        Analize, vodiči i praktični saveti za pametniju kupovinu automobila.
                    </h1>
                    <p class="max-w-3xl text-lg leading-8 text-slate-300">
                        Blog prati tržišne signale, poredi rizike i objašnjava kako da oglase gledaš kroz stvarnu vrednost, a ne samo kroz prvu cenu.
                    </p>
                </div>
            </div>

            @if($featuredPost)
                <article class="panel relative overflow-hidden p-6 sm:p-8 lg:p-10">
                    <img
                        src="{{ $featuredPost->coverImageUrl() }}"
                        alt=""
                        aria-hidden="true"
                        class="pointer-events-none absolute inset-0 h-full w-full object-cover opacity-20"
                    >
                    <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-950/95 to-slate-900/80"></div>

                    <div class="relative max-w-3xl space-y-7">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="chip border-cyan-300/25 bg-slate-950/55 text-cyan-100">Izdvojeno</span>
                            <div class="flex flex-wrap items-center gap-3 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">
                                @if($featuredPost->category)
                                    <span class="text-cyan-300">{{ $featuredPost->category }}</span>
                                @endif
                                <span>{{ optional($featuredPost->published_at)->format('d.m.Y') }}</span>
                                <span>{{ $featuredPost->readingTimeLabel() }}</span>
                            </div>
                        </div>

                        <a href="{{ route('blog.show', $featuredPost) }}" wire:navigate class="block space-y-4">
                            <h2 class="font-display text-2xl font-bold leading-tight text-white sm:text-4xl lg:text-5xl">{{ $featuredPost->title }}</h2>
                            <p class="text-base leading-8 text-slate-300 sm:text-lg">{{ $featuredPost->excerptText() }}</p>
                        </a>

                        @if(!empty($featuredPost->highlights))
                            <div class="border-y border-white/10 py-5">
                                <div class="data-kicker">Brzi pregled</div>
                                <ul class="mt-4 space-y-3 text-sm leading-7 text-slate-200 sm:text-base">
                                    @foreach(array_slice($featuredPost->highlights, 0, 3) as $highlight)
                                        <li class="flex gap-3">
                                            <span class="mt-2.5 h-2 w-2 shrink-0 rounded-full bg-cyan-300"></span>
                                            <span>{{ $highlight }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="text-sm text-slate-500">Piše {{ $featuredPost->author_name }}</div>
                            <a href="{{ route('blog.show', $featuredPost) }}" wire:navigate class="btn-primary">Pročitaj članak</a>
                        </div>
                    </div>
                </article>
            @endif

            <section class="space-y-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <div class="data-kicker">Najnoviji članci</div>
                        <h2 class="section-title mt-2">
                            @if($category !== '')
                                {{ $category }}
                            @else
                                Šta je trenutno važno na tržištu
                            @endif
                        </h2>
                    </div>
                    <p class="section-copy max-w-2xl sm:max-w-sm">
                        Članci su kratki, konkretni i fokusirani na odluke koje se stvarno donose pri izboru polovnjaka.
                    </p>
                </div>

                @if($posts->count() > 0)
                    <div class="grid gap-6 lg:grid-cols-2">
                        @foreach($posts as $post)
                            <x-blog-post-card :post="$post" />
                        @endforeach
                    </div>

                    <div class="pt-2">
                        {{ $posts->links() }}
                    </div>
                @else
                    <div class="panel p-8 text-slate-300">Članci za ovu temu uskoro stižu. Izaberi drugu temu ili se vrati na sve tekstove.</div>
                @endif
            </section>
        </div>

        <aside class="min-w-0 space-y-5">
            <div class="panel p-6">
                <div class="data-kicker">Teme</div>
                <h2 class="mt-2 font-display text-3xl font-bold text-white">Pregled sadržaja</h2>
                <p class="mt-3 text-sm leading-7 text-slate-300">
                    Filtriraj članke po oblasti i zadrži fokus na onome što ti trenutno najviše znači pri kupovini ili prodaji vozila.
                </p>

                <div class="mt-6 space-y-3">
                    <button
                        type="button"
                        wire:click="setCategory"
                        class="panel-soft flex w-full items-center justify-between p-4 text-left transition hover:bg-white/10 {{ $category === '' ? 'border-cyan-300/35 bg-cyan-400/10 text-white' : '' }}"
                    >
                        <span class="font-semibold">Sve teme</span>
                        <span class="text-sm text-slate-400">{{ number_format($posts->total() + ($featuredPost ? 1 : 0), 0, ',', '.') }}</span>
                    </button>

                    @foreach($categories as $item)
                        <button
                            type="button"
                            wire:click="setCategory('{{ addslashes((string) $item->category) }}')"
                            class="panel-soft flex w-full items-center justify-between p-4 text-left transition hover:bg-white/10 {{ $category === $item->category ? 'border-cyan-300/35 bg-cyan-400/10 text-white' : '' }}"
                        >
                            <span class="font-semibold">{{ $item->category }}</span>
                            <span class="text-sm text-slate-400">{{ number_format((int) $item->total, 0, ',', '.') }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            @if($priorityGuides->isNotEmpty())
                <div class="panel p-6">
                    <div class="data-kicker">Vodiči za kupovinu</div>
                    <h2 class="mt-2 font-display text-3xl font-bold text-white">Kreni od vodiča koji olakšavaju izbor</h2>

                    <div class="mt-6 space-y-3">
                        @foreach($priorityGuides as $post)
                            <a href="{{ route('blog.show', $post) }}" wire:navigate class="group block rounded-2xl border border-white/10 bg-white/[0.03] p-4 transition hover:border-amber-300/35 hover:bg-white/[0.06]">
                                <div class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-200">{{ $post->category }}</div>
                                <div class="mt-2 text-sm font-semibold leading-6 text-white transition group-hover:text-amber-100">{{ $post->title }}</div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="panel p-6">
                <div class="data-kicker">Zašto blog</div>
                <ul class="mt-4 space-y-4 text-sm leading-7 text-slate-300">
                    <li>Objašnjavamo kako da čitaš cenu, a ne samo da je vidiš.</li>
                    <li>Spajamo oglase, istoriju promena i tržišni kontekst.</li>
                    <li>Tekstovi su pisani za kupce i prodavce na tržištu Srbije.</li>
                </ul>
            </div>
        </aside>
    </section>
</div>
