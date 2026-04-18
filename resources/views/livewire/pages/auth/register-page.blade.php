<div class="mx-auto max-w-3xl">
    <div class="panel p-8 sm:p-10">
        <div class="mb-8">
            <div class="data-kicker">AutoIQ.rs nalog</div>
            <h1 class="font-display mt-2 text-4xl font-bold text-white">Registracija</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-300">Otvarate nalog za AutoIQ.rs. Ako nastavite preko Google-a, unos lozinke se radi samo na zvaničnoj Google strani.</p>
        </div>

        <x-oauth-buttons mode="register" class="mb-6" />

        <form wire:submit="register" class="grid gap-6 md:grid-cols-2">
            <div>
                <label class="field-label">Ime i prezime</label>
                <input type="text" wire:model.live="name" class="input-shell w-full" placeholder="Milan Petrović">
                @error('name') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="field-label">Email</label>
                <input type="email" wire:model.live="email" class="input-shell w-full" placeholder="ime@domen.rs">
                @error('email') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="field-label">Tip naloga</label>
                <select wire:model.live="role" class="input-shell w-full">
                    @foreach($roles as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('role') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="field-label">Grad</label>
                <select wire:model.live="city" class="input-shell w-full">
                    <option value="">Izaberite grad</option>
                    @foreach($cities as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
                @error('city') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="field-label">Telefon</label>
                <input type="text" wire:model.live="phone" class="input-shell w-full" placeholder="+381 6x xxx xxxx">
                @error('phone') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="field-label">Lozinka</label>
                <input type="password" wire:model.live="password" class="input-shell w-full" placeholder="Najmanje 8 karaktera">
                @error('password') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="field-label">Potvrda lozinke</label>
                <input type="password" wire:model.live="passwordConfirmation" class="input-shell w-full" placeholder="Ponovite lozinku">
                @error('passwordConfirmation') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
            </div>

            @if($role === 'dealer')
                <div class="md:col-span-2 grid gap-6 rounded-3xl border border-amber-300/15 bg-amber-400/5 p-6 md:grid-cols-2">
                    <div>
                        <label class="field-label">Naziv dilera</label>
                        <input type="text" wire:model.live="dealerCompanyName" class="input-shell w-full" placeholder="Auto centar Novi Sad">
                        @error('dealerCompanyName') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="field-label">Sajt dilera</label>
                        <input type="url" wire:model.live="dealerWebsite" class="input-shell w-full" placeholder="https://vas-sajt.rs">
                        @error('dealerWebsite') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Opis dilera</label>
                        <textarea wire:model.live="dealerDescription" class="textarea-shell w-full" placeholder="Ukratko opišite ponudu, iskustvo i kontakt podatke."></textarea>
                        @error('dealerDescription') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                    </div>
                </div>
            @endif

            @error('rate_limit') <p class="md:col-span-2 text-sm text-rose-300">{{ $message }}</p> @enderror

            <div class="md:col-span-2 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('login') }}" wire:navigate class="text-sm text-slate-300 transition hover:text-white">Već imate nalog? Prijavite se</a>
                <button type="submit" class="btn-primary">Kreiraj nalog</button>
            </div>
        </form>
    </div>
</div>
