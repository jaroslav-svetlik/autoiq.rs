@props([
    'mode' => 'login',
])

@php
    $action = $mode === 'register' ? 'Nastavite registraciju' : 'Prijavite se';
    $providers = [
        'google' => [
            'label' => 'Google',
            'accent' => 'from-[#4285f4] via-[#34a853] to-[#fbbc05]',
            'iconBorder' => 'border-white/12 bg-white',
        ],
        'facebook' => [
            'label' => 'Facebook',
            'accent' => 'from-[#1877f2] via-[#3b82f6] to-cyan-300',
            'iconBorder' => 'border-[#1877f2]/35 bg-[#1877f2]',
        ],
    ];
@endphp

<div {{ $attributes->class('space-y-4') }}>
    <div class="grid gap-3 sm:grid-cols-2">
        @foreach($providers as $provider => $data)
            <a
                href="{{ route('oauth.redirect', $provider) }}"
                class="group relative flex min-h-16 items-center justify-between gap-4 overflow-hidden rounded-2xl border border-white/10 bg-slate-950/55 px-4 py-3 text-left shadow-[0_18px_45px_rgba(2,6,23,0.22)] transition hover:border-cyan-300/35 hover:bg-white/8 focus:outline-none focus:ring-2 focus:ring-cyan-400/25"
                aria-label="{{ $action }} preko {{ $data['label'] }} naloga"
            >
                <span class="absolute inset-x-0 top-0 h-px bg-gradient-to-r {{ $data['accent'] }} opacity-70"></span>

                <span class="flex min-w-0 items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border shadow-inner {{ $data['iconBorder'] }}">
                        @if($provider === 'google')
                            <svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="#4285F4" d="M21.6 12.23c0-.76-.07-1.49-.2-2.19H12v4.14h5.38a4.6 4.6 0 0 1-2 3.02v2.51h3.24c1.9-1.75 2.98-4.32 2.98-7.48Z" />
                                <path fill="#34A853" d="M12 22c2.7 0 4.96-.9 6.62-2.43l-3.24-2.51c-.9.6-2.04.95-3.38.95-2.6 0-4.8-1.76-5.59-4.12H3.07v2.59A10 10 0 0 0 12 22Z" />
                                <path fill="#FBBC05" d="M6.41 13.89A6.03 6.03 0 0 1 6.1 12c0-.66.11-1.3.31-1.89V7.52H3.07A10 10 0 0 0 2 12c0 1.61.39 3.13 1.07 4.48l3.34-2.59Z" />
                                <path fill="#EA4335" d="M12 5.99c1.47 0 2.78.5 3.82 1.49l2.87-2.87C16.95 2.99 14.7 2 12 2a10 10 0 0 0-8.93 5.52l3.34 2.59C7.2 7.75 9.4 5.99 12 5.99Z" />
                            </svg>
                        @else
                            <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M15.12 8.19h2.17V4.41A28.07 28.07 0 0 0 14.13 4c-3.13 0-5.27 1.97-5.27 5.59v3.33H5.41v4.22h3.45V24h4.23v-6.86h3.31l.53-4.22h-3.84V10c0-1.22.33-1.81 2.03-1.81Z" />
                            </svg>
                        @endif
                    </span>

                    <span class="min-w-0">
                        <span class="block text-[0.68rem] font-semibold uppercase tracking-[0.18em] text-slate-500">
                            {{ $mode === 'register' ? 'Registracija' : 'Prijava' }}
                        </span>
                        <span class="block truncate text-sm font-bold text-white">
                            Nastavi preko {{ $data['label'] }}
                        </span>
                    </span>
                </span>

                <svg class="h-4 w-4 shrink-0 text-slate-500 transition group-hover:translate-x-0.5 group-hover:text-cyan-200" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.69l-3.22-3.22a.75.75 0 1 1 1.06-1.06l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 1 1-1.06-1.06l3.22-3.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" />
                </svg>
            </a>
        @endforeach
    </div>

    <div class="flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
        <span class="h-px flex-1 bg-white/10"></span>
        <span>{{ $mode === 'register' ? 'ili unesite podatke' : 'ili koristite email' }}</span>
        <span class="h-px flex-1 bg-white/10"></span>
    </div>
</div>
