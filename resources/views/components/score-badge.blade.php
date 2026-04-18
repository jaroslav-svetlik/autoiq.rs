@props(['listing'])

@php
    $classes = match ($listing->scoreTone()) {
        'emerald' => 'border-emerald-300/40 bg-emerald-400/15 text-emerald-100',
        'amber' => 'border-amber-300/40 bg-amber-400/15 text-amber-50',
        default => 'border-rose-300/40 bg-rose-400/15 text-rose-50',
    };
@endphp

<span class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-semibold {{ $classes }}">
    <span>{{ $listing->autoiq_score }}/100</span>
    <span>{{ $listing->scoreLabel() }}</span>
</span>
