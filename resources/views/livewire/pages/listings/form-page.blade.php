<div class="mx-auto max-w-5xl space-y-8">
    <div>
        <div class="data-kicker">Objava oglasa</div>
        <h1 class="section-title mt-2">{{ $listing ? 'Izmena postojećeg oglasa' : 'Dodavanje novog oglasa' }}</h1>
        <p class="section-copy mt-3">Svi unosi prolaze proveru, a fotografije se otpremaju uz ograničenje broja, veličine i podržanih formata.</p>
    </div>

    <form wire:submit="save" class="grid gap-8 lg:grid-cols-[1fr_320px]">
        <div class="space-y-6">
            <div class="panel p-6 sm:p-8">
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="field-label">Naslov</label>
                        <input type="text" wire:model.live="titleInput" class="input-shell w-full" placeholder="BMW 320d xDrive M paket, prvi vlasnik">
                        @error('titleInput') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="field-label">Marka</label>
                        <input type="text" wire:model.live="brand" class="input-shell w-full" placeholder="BMW">
                        @error('brand') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="field-label">Model</label>
                        <input type="text" wire:model.live="model" class="input-shell w-full" placeholder="320d">
                        @error('model') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="field-label">Godište</label>
                        <input type="number" wire:model.live="year" class="input-shell w-full" placeholder="2018">
                        @error('year') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="field-label">Cena (€)</label>
                        <input type="number" wire:model.live="price" class="input-shell w-full" placeholder="15900">
                        @error('price') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="field-label">Kilometraža</label>
                        <input type="number" wire:model.live="mileage" class="input-shell w-full" placeholder="164000">
                        @error('mileage') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="field-label">Gorivo</label>
                        <select wire:model.live="fuelType" class="input-shell w-full">
                            <option value="">Izaberite</option>
                            @foreach($fuelTypes as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('fuelType') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="field-label">Menjač</label>
                        <select wire:model.live="transmission" class="input-shell w-full">
                            <option value="">Izaberite</option>
                            @foreach($transmissionTypes as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('transmission') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="field-label">Lokacija</label>
                        <select wire:model.live="city" class="input-shell w-full">
                            <option value="">Izaberite grad</option>
                            @foreach($cities as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('city') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="field-label">Tip prodavca</label>
                        <select wire:model.live="sellerType" class="input-shell w-full">
                            @foreach($sellerTypes as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('sellerType') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="field-label">Opis</label>
                        <textarea wire:model.live="description" class="textarea-shell w-full" placeholder="Stanje vozila, servisna istorija, oprema, vlasništvo, ulaganja..."></textarea>
                        @error('description') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="panel p-6 sm:p-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <div class="data-kicker">Oprema</div>
                        <h2 class="font-display mt-2 text-2xl font-bold text-white">Šta vozilo poseduje</h2>
                        <p class="mt-3 text-sm leading-7 text-slate-300">Izaberite samo opremu koja je zaista ugrađena u vozilo. Ovaj izbor koristimo za precizniju pretragu i kvalitetniji prikaz oglasa.</p>
                    </div>
                    <div class="text-sm text-slate-400">{{ count($equipment) }} odabranih stavki</div>
                </div>

                <div class="mt-6 grid gap-4 xl:grid-cols-2">
                    @foreach($equipmentCatalog as $group)
                        <div class="panel-soft p-5">
                            <div class="data-kicker">{{ $group['label'] }}</div>
                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                @foreach($group['options'] as $option)
                                    <label class="flex items-start gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-200 transition hover:border-cyan-400/30 hover:bg-cyan-400/5">
                                        <input
                                            type="checkbox"
                                            value="{{ $option['key'] }}"
                                            wire:model.live="equipment"
                                            class="mt-1 h-4 w-4 rounded border-white/20 bg-slate-950/60 text-cyan-400 focus:ring-cyan-400/30"
                                        >
                                        <span>{{ $option['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                @error('equipment') <p class="mt-4 text-sm text-rose-300">{{ $message }}</p> @enderror
                @error('equipment.*') <p class="mt-4 text-sm text-rose-300">{{ $message }}</p> @enderror
            </div>

            <div class="panel p-6 sm:p-8">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="data-kicker">Galerija</div>
                        <h2 class="font-display mt-2 text-2xl font-bold text-white">Fotografije</h2>
                    </div>
                    <div class="text-sm text-slate-400">{{ ($listing?->images->count() ?? 0) + count($newImages) }}/20 fotografija · do 1 MB po slici</div>
                </div>

                <div class="mt-6">
                    <input type="file" wire:model.live="newImages" multiple accept=".jpg,.jpeg,.png,.webp" class="input-shell w-full">
                    @error('newImages') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                    @error('newImages.*') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>

                @if($listing?->images->count())
                    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($listing->images as $image)
                            <div class="panel-soft overflow-hidden">
                                <img src="{{ $image->url() }}" alt="{{ $image->alt_text ?: $listing->title }}" class="aspect-[4/3] w-full object-cover">
                                <div class="p-3">
                                    <button type="button" wire:click="deleteImage({{ $image->id }})" class="btn-ghost w-full text-rose-200 hover:text-rose-100">Ukloni sliku</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if(count($newImages))
                    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($newImages as $index => $image)
                            <div class="panel-soft overflow-hidden">
                                <img src="{{ $image->temporaryUrl() }}" alt="Nova fotografija" class="aspect-[4/3] w-full object-cover">
                                <div class="p-3">
                                    <button type="button" wire:click="removeNewImage({{ $index }})" class="btn-ghost w-full text-amber-200 hover:text-amber-100">Ukloni iz otpreme</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <aside class="space-y-4">
            <div class="panel p-5">
                <div class="data-kicker">Objava</div>
                <h2 class="font-display mt-2 text-2xl font-bold text-white">Spremno za objavu</h2>
                <p class="mt-3 text-sm leading-7 text-slate-300">Posle čuvanja vaš oglas dobija svoju adresu, AutoIQ procenu i praćenje promene cene.</p>

                @error('rate_limit') <p class="mt-4 text-sm text-rose-300">{{ $message }}</p> @enderror

                <button type="submit" class="btn-primary mt-6 w-full">{{ $listing ? 'Sačuvaj izmene' : 'Objavi oglas' }}</button>
            </div>
        </aside>
    </form>
</div>
