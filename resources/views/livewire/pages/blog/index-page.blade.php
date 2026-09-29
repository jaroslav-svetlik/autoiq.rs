<div>
    <section class="catalog-hero blog-hero">
        <img class="hero-image" src="{{ asset('images/alpine-drive.webp') }}" alt="" fetchpriority="high" width="2172" height="724">
        <div class="container-frame hero-content">
            <div class="hero-copy">
                <nav aria-label="Putanja" class="text-xs text-muted mb-3"><a href="{{ route('home') }}" wire:navigate>Početna</a><span class="mx-2">›</span><span class="text-ink">Blog</span></nav>
                <h1>AutoIQ Blog</h1>
                <p class="hero-intro">Testovi, recenzije, saveti i aktuelne vesti iz sveta automobila.</p>
                <div class="hero-benefits">
                    <div><span class="feature-icon"><x-icon name="book" /></span><span><b>Vodiči za kupovinu</b><small>Za lakši izbor</small></span></div>
                    <div><span class="feature-icon"><x-icon name="shield" /></span><span><b>Praktični saveti</b><small>Za svakog vozača</small></span></div>
                    <div><span class="feature-icon"><x-icon name="car" /></span><span><b>Analize tržišta</b><small>Prava vrednost vozila</small></span></div>
                </div>
            </div>
        </div>
    </section>
    <div class="container-frame blog-content">
        <nav class="blog-categories" aria-label="Teme bloga">
            <button type="button" wire:click="setCategory" class="{{ $category === '' ? 'is-active' : '' }}" aria-pressed="{{ $category === '' ? 'true' : 'false' }}">Svi članci ({{ number_format($categories->sum('total'), 0, ',', '.') }})</button>
            @foreach($categories as $item)<button type="button" wire:click="setCategory(@js($item->category))" class="{{ $category === $item->category ? 'is-active' : '' }}" aria-pressed="{{ $category === $item->category ? 'true' : 'false' }}">{{ $item->category }} ({{ $item->total }})</button>@endforeach
        </nav>
        <div class="blog-layout">
            <section class="min-w-0" aria-label="Članci">
                @if($featuredPost)
                    <article class="panel blog-featured">
                        <a href="{{ route('blog.show', $featuredPost) }}" wire:navigate class="relative"><img src="{{ $featuredPost->coverImageUrl() }}" alt="{{ $featuredPost->cover_image_alt ?: $featuredPost->title }}" width="640" height="440"><span class="listing-badge">{{ $featuredPost->category ?: 'Izdvojeno' }}</span></a>
                        <div class="blog-featured-copy"><div class="blog-meta"><span><x-icon name="calendar" />{{ optional($featuredPost->published_at)->format('d. m. Y.') }}</span><span><x-icon name="clock" />{{ $featuredPost->readingTimeLabel() }}</span></div>
                            <h2><a href="{{ route('blog.show', $featuredPost) }}" wire:navigate>{{ $featuredPost->title }}</a></h2><p>{{ $featuredPost->excerptText() }}</p><a href="{{ route('blog.show', $featuredPost) }}" wire:navigate>Pročitaj ceo članak<x-icon name="arrow" /></a>
                        </div>
                    </article>
                @endif
                @if($posts->count())
                    <div class="blog-card-grid">@foreach($posts as $post)<x-blog-post-card :post="$post" wire:key="post-{{ $post->id }}" />@endforeach</div>
                    <div class="catalog-pagination">{{ $posts->links() }}</div>
                @elseif(!$featuredPost)
                    <div class="panel empty-results"><x-icon name="book" class="mx-auto text-brand" /><h2>Nema članaka za ovu pretragu</h2><p>Probajte drugi pojam ili izaberite drugu temu.</p><button type="button" wire:click="clearSearch" class="btn-primary">Prikaži sve članke</button></div>
                @endif
            </section>
            <aside class="blog-sidebar">
                <div class="panel"><h2>Pretraži blog</h2><label class="filter-search"><x-icon name="search" /><input type="search" wire:model.live.debounce.350ms="search" placeholder="Pretraži članke, teme, modele…" aria-label="Pretraži blog"></label><span wire:loading.delay class="text-xs text-brand mt-3" role="status">Pretraživanje…</span></div>
                @if($priorityGuides->isNotEmpty())
                    <section class="panel"><h2>Vodiči za kupovinu</h2><p class="text-xs text-muted">Kreni od vodiča koji olakšavaju izbor</p>@foreach($priorityGuides->take(5) as $guide)<a href="{{ route('blog.show', $guide) }}" wire:navigate class="blog-guide-link"><img src="{{ $guide->coverImageUrl() }}" alt="" loading="lazy" width="65" height="55"><div><h3>{{ $guide->title }}</h3><small>{{ $guide->readingTimeLabel() }}</small></div></a>@endforeach</section>
                @endif
                <section class="panel"><span class="feature-icon mb-4"><x-icon name="car" /></span><h2>Tražiš novi automobil?</h2><p class="text-sm text-muted leading-6 mb-5">Uporedi oglase, proveri cene i pronađi automobil po svojoj meri.</p><a href="{{ route('home') }}" wire:navigate class="btn-primary w-full">Pretraži automobile<x-icon name="arrow" /></a></section>
            </aside>
        </div>
        <div class="mt-7"><x-drive-banner :blog="false" /></div>
    </div>
</div>
