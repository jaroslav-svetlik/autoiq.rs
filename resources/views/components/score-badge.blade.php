@props(['listing', 'expanded' => false])

@php
    $score = max(0, min(100, (int) $listing->autoiq_score));
    $label = $listing->scoreLabel();
@endphp

<div {{ $attributes->class(['score-meter', 'score-meter-'.$listing->scoreTone(), 'score-meter-expanded' => $expanded]) }}
    role="meter" aria-label="AutoIQ procena" aria-valuemin="0" aria-valuemax="100"
    aria-valuenow="{{ $score }}" aria-valuetext="{{ $label }} — {{ $score }} od 100"
    style="--score-position: {{ $score }}%">
    <div class="score-meter-heading" aria-hidden="true">
        <span>{{ $expanded ? $label : 'AutoIQ procena' }}</span>
        <strong>{{ $expanded ? $score.'/100' : $label }}</strong>
    </div>
    <div class="score-meter-track" aria-hidden="true">
        @foreach(range(0, 4) as $segment)
            <span class="score-meter-segment"><span style="width: {{ max(0, min(100, ($score - $segment * 20) * 5)) }}%"></span></span>
        @endforeach
        <span class="score-meter-pointer"></span>
    </div>
    @if($expanded)
        <div class="score-meter-legend" aria-hidden="true"><span>Manje povoljno</span><span>Povoljnije</span></div>
    @endif
</div>
