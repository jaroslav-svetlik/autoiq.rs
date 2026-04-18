<div class="mx-auto max-w-lg">
    <div class="panel p-8 sm:p-10">
        <div class="mb-8">
            <div class="data-kicker">Bezbednost naloga</div>
            <h1 class="font-display mt-2 text-4xl font-bold text-white">Nova lozinka</h1>
            <p class="mt-3 text-sm leading-7 text-slate-300">Postavite novu lozinku i vratite pristup svom AutoIQ nalogu.</p>
        </div>

        <form wire:submit="resetPassword" class="space-y-5">
            <div>
                <label class="field-label">Email</label>
                <input type="email" wire:model.live="email" class="input-shell w-full">
                @error('email') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="field-label">Nova lozinka</label>
                <input type="password" wire:model.live="password" class="input-shell w-full">
                @error('password') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="field-label">Potvrda lozinke</label>
                <input type="password" wire:model.live="passwordConfirmation" class="input-shell w-full">
                @error('passwordConfirmation') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
            </div>

            @error('rate_limit') <p class="text-sm text-rose-300">{{ $message }}</p> @enderror

            <button type="submit" class="btn-primary w-full">Sačuvaj novu lozinku</button>
        </form>
    </div>
</div>
