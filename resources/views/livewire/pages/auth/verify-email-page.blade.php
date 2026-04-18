<div class="mx-auto max-w-2xl">
    <div class="panel p-8 sm:p-10">
        <div class="data-kicker">Verifikacija email-a</div>
        <h1 class="font-display mt-2 text-4xl font-bold text-white">Potvrdite svoju adresu</h1>
        <p class="mt-4 max-w-xl text-sm leading-7 text-slate-300">
            Poslali smo verifikacioni link na vašu email adresu. Potvrda je potrebna za objavu oglasa, favorite i alarm sistem.
        </p>

        <div class="mt-8 flex flex-col gap-4 sm:flex-row">
            <button type="button" wire:click="resend" class="btn-primary">Pošalji ponovo</button>
            <a href="{{ route('logout') }}" wire:navigate class="btn-secondary">Odjava</a>
        </div>

        @error('rate_limit') <p class="mt-4 text-sm text-rose-300">{{ $message }}</p> @enderror
    </div>
</div>
