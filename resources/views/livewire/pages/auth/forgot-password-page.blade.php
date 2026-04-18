<div class="mx-auto max-w-lg">
    <div class="panel p-8 sm:p-10">
        <div class="mb-8">
            <div class="data-kicker">Pristup nalogu</div>
            <h1 class="font-display mt-2 text-4xl font-bold text-white">Zaboravljena lozinka</h1>
            <p class="mt-3 text-sm leading-7 text-slate-300">Unesite email i poslaćemo vam link za postavljanje nove lozinke.</p>
        </div>

        <form wire:submit="sendResetLink" class="space-y-5">
            <div>
                <label class="field-label">Email adresa</label>
                <input type="email" wire:model.live="email" class="input-shell w-full" placeholder="ime@primer.rs">
                @error('email') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
            </div>

            @error('rate_limit') <p class="text-sm text-rose-300">{{ $message }}</p> @enderror

            <button type="submit" class="btn-primary w-full">Pošalji link</button>
        </form>

        <div class="mt-6">
            <a href="{{ route('login') }}" wire:navigate class="text-sm text-slate-300 transition hover:text-white">Nazad na prijavu</a>
        </div>
    </div>
</div>
