@props([
    'post',
    'compact' => false,
])

<article {{ $attributes->class($compact ? 'panel-soft overflow-hidden p-4' : 'panel overflow-hidden') }}>
    <a href="{{ route('blog.show', $post) }}" wire:navigate class="block">
        <div class="{{ $compact ? 'mb-4 aspect-[16/10]' : 'aspect-[16/9]' }} overflow-hidden rounded-[1.5rem] border border-white/10 bg-slate-900/70">
            <img
                src="{{ $post->coverImageUrl() }}"
                alt="{{ $post->cover_image_alt ?: $post->title }}"
                class="h-full w-full object-cover transition duration-500 hover:scale-[1.02]"
                loading="lazy"
            >
        </div>

        <div class="{{ $compact ? 'space-y-3' : 'space-y-4 p-5' }}">
            <div class="flex flex-wrap items-center gap-3 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">
                @if($post->category)
                    <span class="text-cyan-300">{{ $post->category }}</span>
                @endif
                <span>{{ optional($post->published_at)->format('d.m.Y') }}</span>
                <span>{{ $post->readingTimeLabel() }}</span>
            </div>

            <div class="space-y-2">
                <h3 class="font-display text-2xl font-bold leading-tight text-white">{{ $post->title }}</h3>
                <p class="text-sm leading-7 text-slate-300">{{ $post->excerptText() }}</p>
            </div>

            <div class="flex items-center justify-between gap-3">
                <div class="text-sm text-slate-500">Piše {{ $post->author_name }}</div>
                <span class="text-sm font-semibold text-amber-300">Pročitaj članak</span>
            </div>
        </div>
    </a>
</article>
