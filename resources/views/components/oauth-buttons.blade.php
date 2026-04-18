@props([
    'mode' => 'login',
])

@php
    $action = $mode === 'register' ? 'Nastavite registraciju' : 'Prijavite se';
    $providers = [
        'google' => [
            'label' => 'Google',
            'icon' => 'G',
            'classes' => 'bg-white text-slate-950 hover:bg-slate-100',
        ],
        'facebook' => [
            'label' => 'Facebook',
            'icon' => 'f',
            'classes' => 'bg-[#1877f2] text-white hover:bg-[#166fe5]',
        ],
    ];
@endphp

<div {{ $attributes->class('space-y-4') }}>
    <div class="grid gap-3 sm:grid-cols-2">
        @foreach($providers as $provider => $data)
            <a
                href="{{ route('oauth.redirect', $provider) }}"
                class="inline-flex min-h-12 items-center justify-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition {{ $data['classes'] }}"
                aria-label="{{ $action }} preko {{ $data['label'] }} naloga"
            >
                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-black/10 font-display text-base leading-none">{{ $data['icon'] }}</span>
                <span>{{ $action }} preko {{ $data['label'] }}</span>
            </a>
        @endforeach
    </div>

    <div class="flex items-center gap-3 text-xs uppercase tracking-[0.18em] text-slate-500">
        <span class="h-px flex-1 bg-white/10"></span>
        <span>{{ $mode === 'register' ? 'ili unesite podatke' : 'ili koristite email' }}</span>
        <span class="h-px flex-1 bg-white/10"></span>
    </div>
</div>
