@props(['listing'])

@php
    $classes = match ($listing->scoreTone()) {
        'emerald' => 'border-emerald-300/40 bg-emerald-400/15 text-emerald-700',
        'amber' => 'border-brand/20 bg-brand/5 text-brand',
        default => 'border-rose-300/40 bg-rose-400/15 text-rose-700',
    };
@endphp

<span class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-semibold {{ $classes }}">
    <span>{{ $listing->autoiq_score }}/100</span>
    <span>{{ $listing->scoreLabel() }}</span>
</span>
