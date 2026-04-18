<div class="space-y-12">
    <section class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
        <div class="space-y-8">
            <div class="space-y-5">
                <span class="chip">Kontakt</span>
                <div class="space-y-4">
                    <h1 class="font-display max-w-3xl text-4xl font-bold leading-tight tracking-tight text-white sm:text-6xl">
                        Javite nam šta vam treba oko AutoIQ platforme.
                    </h1>
                    <p class="max-w-2xl text-lg leading-8 text-slate-300">
                        Pišite nam za pitanja o oglasima, nalogu, dilerima, saradnji ili problemima koje primetite dok koristite platformu.
                    </p>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                <div class="panel-soft p-5">
                    <div class="data-kicker">Odgovor</div>
                    <p class="mt-3 text-sm leading-7 text-slate-300">Poruke pregledamo po prioritetu i odgovaramo na email koji ostavite u formi.</p>
                </div>
                <div class="panel-soft p-5">
                    <div class="data-kicker">Sigurno slanje</div>
                    <p class="mt-3 text-sm leading-7 text-slate-300">Navedite samo podatke potrebne za odgovor; lozinke, kartice i dokumenta nisu potrebni u prvom kontaktu.</p>
                </div>
            </div>
        </div>

        <form wire:submit="send" class="panel p-6 sm:p-8 lg:p-10">
            <div class="mb-7">
                <div class="data-kicker">Poruka timu</div>
                <h2 class="font-display mt-2 text-3xl font-bold text-white">Pošaljite upit</h2>
                <p class="mt-3 text-sm leading-7 text-slate-300">Što preciznije opišete pitanje, lakše ćemo dati konkretan odgovor.</p>
            </div>

            @if (session('status'))
                <div class="mb-6 rounded-2xl border border-emerald-400/30 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-100">
                    {{ session('status') }}
                </div>
            @endif

            <div class="absolute -left-[10000px] top-auto h-px w-px overflow-hidden" aria-hidden="true">
                <label for="contact-website">Website</label>
                <input id="contact-website" type="text" name="website" wire:model="website" tabindex="-1" autocomplete="off">
            </div>

            <input type="hidden" wire:model="formRenderedAt">

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="field-label" for="contact-name">Ime i prezime</label>
                    <input id="contact-name" type="text" wire:model.blur="name" class="input-shell w-full" placeholder="Milan Petrović" autocomplete="name">
                    @error('name') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="field-label" for="contact-email">Email adresa</label>
                    <input id="contact-email" type="email" wire:model.blur="email" class="input-shell w-full" placeholder="ime@primer.rs" autocomplete="email">
                    @error('email') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="field-label" for="contact-phone">Telefon</label>
                    <input id="contact-phone" type="text" wire:model.blur="phone" class="input-shell w-full" placeholder="+381 6x xxx xxxx" autocomplete="tel">
                    @error('phone') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="field-label" for="contact-topic">Tema</label>
                    <select id="contact-topic" wire:model.blur="topic" class="input-shell w-full">
                        <option value="">Izaberite temu</option>
                        @foreach($topics as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('topic') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="field-label" for="contact-message">Poruka</label>
                    <textarea id="contact-message" wire:model.blur="message" class="textarea-shell min-h-44 w-full" placeholder="Ukratko napišite šta vam treba, uz link ka oglasu ako je relevantno."></textarea>
                    @error('message') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>

                @error('rate_limit') <p class="md:col-span-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                @error('email_delivery') <p class="md:col-span-2 text-sm text-rose-300">{{ $message }}</p> @enderror

                <div class="md:col-span-2 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm leading-7 text-slate-400">
                        Ne šaljite lozinke, podatke kartica ili druge osetljive podatke.
                    </p>
                    <button
                        type="submit"
                        class="btn-primary min-w-44 disabled:cursor-wait disabled:opacity-70"
                        wire:loading.attr="disabled"
                        wire:target="send"
                    >
                        <span wire:loading.remove wire:target="send">Pošalji poruku</span>
                        <span wire:loading.flex wire:target="send" class="items-center gap-2">
                            <span class="h-4 w-4 animate-spin rounded-full border-2 border-slate-950/25 border-t-slate-950"></span>
                            Šalje se...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </section>
</div>
