@props(['post', 'compact' => false])
<article {{ $attributes->class('panel blog-card overflow-hidden') }}>
    <a href="{{ route('blog.show', $post) }}" wire:navigate>
        <div class="blog-card-photo">
            <img src="{{ $post->coverImageUrl() }}" alt="{{ $post->cover_image_alt ?: $post->title }}" loading="lazy" width="480" height="290">
            @if($post->category)<span class="listing-badge">{{ $post->category }}</span>@endif
        </div>
        <div class="blog-card-copy">
            <div class="blog-meta"><span><x-icon name="calendar" />{{ optional($post->published_at)->format('d. m. Y.') }}</span><span><x-icon name="clock" />{{ $post->readingTimeLabel() }}</span></div>
            <h3>{{ $post->title }}</h3>
            <p class="line-clamp-3">{{ $post->excerptText() }}</p>
            <span class="blog-card-link">Pročitaj više <x-icon name="arrow" /></span>
        </div>
    </a>
</article>
