<div class="mx-auto max-w-lg">
    <div class="panel p-8 sm:p-10">
        <div class="mb-8">
            <div class="data-kicker">AutoIQ nalog</div>
            <h1 class="font-display mt-2 text-4xl font-bold text-white">Prijava</h1>
            <p class="mt-3 text-sm leading-7 text-slate-300">Pristupite favoritima, alarmima, oglasima i tržišnim analizama bez napuštanja aplikacije.</p>
        </div>

        <form wire:submit="login" class="space-y-5">
            <div>
                <label class="field-label" for="email">Email adresa</label>
                <input id="email" type="email" wire:model.live="email" class="input-shell w-full" placeholder="ime@primer.rs">
                @error('email') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="field-label" for="password">Lozinka</label>
                <input id="password" type="password" wire:model.live="password" class="input-shell w-full" placeholder="Vaša lozinka">
                @error('password') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
            </div>

            @error('rate_limit') <p class="text-sm text-rose-300">{{ $message }}</p> @enderror

            <label class="flex items-center gap-3 text-sm text-slate-300">
                <input type="checkbox" wire:model="remember" class="h-4 w-4 rounded border-white/10 bg-slate-950/60">
                Ostani prijavljen
            </label>

            <button type="submit" class="btn-primary w-full">Prijavi se</button>
        </form>

        <div class="mt-6 flex flex-col gap-3 text-sm text-slate-400 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('password.request') }}" wire:navigate class="text-cyan-300 transition hover:text-cyan-200">Zaboravili ste lozinku?</a>
            <a href="{{ route('register') }}" wire:navigate class="text-slate-300 transition hover:text-white">Nemate nalog? Registrujte se</a>
        </div>
    </div>
</div>
