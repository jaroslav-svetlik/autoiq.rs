<div class="mx-auto max-w-lg">
    <div class="panel p-8 sm:p-10">
        <div class="mb-8">
            <div class="data-kicker">AutoIQ nalog</div>
            <h1 class="font-display mt-2 text-4xl font-bold text-ink">Prijava</h1>
            <p class="mt-3 text-sm leading-7 text-muted">Pristupate AutoIQ.rs nalogu za oglase, favorite i alarme. AutoIQ nikada ne traži vašu Google lozinku.</p>
        </div>

        <x-oauth-buttons mode="login" class="mb-6" />

        <form wire:submit="login" class="space-y-5">
            <div>
                <label class="field-label" for="email">Email adresa</label>
                <input id="email" type="email" wire:model.live="email" class="input-shell w-full" placeholder="ime@primer.rs">
                @error('email') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="field-label" for="password">Lozinka</label>
                <input id="password" type="password" wire:model.live="password" class="input-shell w-full" placeholder="Vaša lozinka">
                @error('password') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
            </div>

            @error('rate_limit') <p class="text-sm text-rose-700">{{ $message }}</p> @enderror

            <label class="flex items-center gap-3 text-sm text-muted">
                <input type="checkbox" wire:model="remember" class="h-4 w-4 rounded border-line bg-white">
                Ostani prijavljen
            </label>

            <button type="submit" class="btn-primary w-full">Prijavi se</button>
        </form>

        <div class="mt-6 flex flex-col gap-3 text-sm text-muted sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('password.request') }}" wire:navigate class="text-brand transition hover:text-brand">Zaboravili ste lozinku?</a>
            <a href="{{ route('register') }}" wire:navigate class="text-muted transition hover:text-ink">Nemate nalog? Registrujte se</a>
        </div>
    </div>
</div>
