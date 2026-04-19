@php
    $stepCount = count($steps);
    $isLastStep = $currentStep === $stepCount;
    $currentStepData = $steps[$currentStep];
@endphp

<div class="mx-auto max-w-6xl space-y-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <div class="data-kicker">Objava oglasa</div>
            <h1 class="section-title mt-2">{{ $listing ? 'Izmena postojećeg oglasa' : 'Dodavanje novog oglasa' }}</h1>
            <p class="section-copy mt-3">Unos je podeljen na jasne korake da lakše završite oglas bez pretrpane forme.</p>
        </div>
        <div class="chip">Korak {{ $currentStep }} od {{ $stepCount }}</div>
    </div>

    <form wire:submit="{{ $isLastStep ? 'save' : 'nextStep' }}" class="grid gap-8 lg:grid-cols-[280px_minmax(0,1fr)]">
        <aside class="space-y-4 lg:sticky lg:top-28 lg:self-start">
            <div class="panel p-4">
                <div class="data-kicker">Tok unosa</div>
                <div class="mt-4 grid gap-2">
                    @foreach($steps as $number => $step)
                        <button
                            type="button"
                            wire:click="goToStep({{ $number }})"
                            class="flex w-full items-center gap-3 rounded-2xl border px-3 py-3 text-left transition {{ $currentStep === $number ? 'border-cyan-300/45 bg-cyan-400/10 text-white' : ($number < $currentStep ? 'border-emerald-300/25 bg-emerald-400/5 text-slate-200 hover:border-emerald-300/40' : 'border-white/10 bg-white/5 text-slate-300 hover:border-white/20 hover:bg-white/8') }}"
                        >
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border {{ $currentStep === $number ? 'border-cyan-300/50 bg-cyan-300 text-slate-950' : ($number < $currentStep ? 'border-emerald-300/40 bg-emerald-300 text-slate-950' : 'border-white/10 bg-slate-950/50 text-slate-300') }}">
                                {{ $number }}
                            </span>
                            <span class="min-w-0">
                                <span class="block text-sm font-bold">{{ $step['title'] }}</span>
                                <span class="mt-0.5 block truncate text-xs text-slate-400">{{ $step['summary'] }}</span>
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="panel p-5">
                <div class="data-kicker">{{ $currentStepData['kicker'] }}</div>
                <h2 class="font-display mt-2 text-2xl font-bold text-white">{{ $currentStepData['title'] }}</h2>
                <p class="mt-3 text-sm leading-7 text-slate-300">{{ $currentStepData['summary'] }}</p>
                @error('rate_limit') <p class="mt-4 text-sm text-rose-300">{{ $message }}</p> @enderror
            </div>
        </aside>

        <section class="min-w-0 space-y-6">
            @if($currentStep === 1)
                <div class="panel p-6 sm:p-8">
                    <div class="mb-6">
                        <div class="data-kicker">Vozilo</div>
                        <h2 class="font-display mt-2 text-2xl font-bold text-white">Osnovni podaci</h2>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="field-label">Naslov</label>
                            <input type="text" wire:model.live="titleInput" class="input-shell w-full" placeholder="BMW 320d xDrive M paket, prvi vlasnik">
                            @error('titleInput') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Marka</label>
                            <select wire:model.live="brand" class="input-shell w-full">
                                <option value="">Izaberite marku</option>
                                @foreach($vehicleBrands as $vehicleBrand)
                                    <option value="{{ $vehicleBrand }}">{{ $vehicleBrand }}</option>
                                @endforeach
                            </select>
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

                        <div class="md:col-span-2">
                            <label class="field-label">Opis</label>
                            <textarea wire:model.live="description" class="textarea-shell min-h-44 w-full" placeholder="Stanje vozila, servisna istorija, oprema, vlasništvo, ulaganja..."></textarea>
                            @error('description') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            @endif

            @if($currentStep === 2)
                <div class="panel p-6 sm:p-8">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <div class="data-kicker">Oprema</div>
                            <h2 class="font-display mt-2 text-2xl font-bold text-white">Šta vozilo poseduje</h2>
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
            @endif

            @if($currentStep === 3)
                <div class="panel p-6 sm:p-8">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
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
                                        <button type="button" wire:click="removeNewImage({{ $index }})" class="btn-ghost w-full text-amber-200 hover:text-amber-100">Ukloni pre slanja</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            @if($currentStep === 4)
                <div class="panel p-6 sm:p-8">
                    <div>
                        <div class="data-kicker">Prodavac</div>
                        <h2 class="font-display mt-2 text-2xl font-bold text-white">Podaci prodavca</h2>
                    </div>

                    <div class="mt-6 grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="field-label">Tip prodavca</label>
                            <select wire:model.live="sellerType" class="input-shell w-full">
                                @foreach($sellerTypes as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('sellerType') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Ime i prezime prodavca</label>
                            <input type="text" wire:model.live="sellerName" class="input-shell w-full" placeholder="Milan Petrović">
                            @error('sellerName') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <div class="grid gap-4 md:grid-cols-2">
                                @foreach($sellerPhones as $index => $phone)
                                    <div wire:key="seller-phone-{{ $index }}" class="flex items-start gap-3">
                                        <div class="min-w-0 flex-1">
                                            <label class="field-label">Telefon {{ $index + 1 }}</label>
                                            <input type="text" wire:model.live="sellerPhones.{{ $index }}" class="input-shell w-full" placeholder="+381 6x xxx xxxx">
                                            @error("sellerPhones.{$index}") <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                                        </div>

                                        @if(count($sellerPhones) > 1)
                                            <button type="button" wire:click="removeSellerPhone({{ $index }})" class="btn-ghost mt-7 shrink-0 text-rose-200 hover:text-rose-100">Ukloni</button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            @error('sellerPhones') <p class="mt-4 text-sm text-rose-300">{{ $message }}</p> @enderror

                            @if(count($sellerPhones) < 3)
                                <button type="button" wire:click="addSellerPhone" class="btn-secondary mt-5">Dodaj broj</button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="panel flex flex-col gap-4 p-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-sm text-slate-400">
                    {{ $currentStepData['kicker'] }} · {{ $currentStepData['title'] }}
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    @if($currentStep > 1)
                        <button type="button" wire:click="previousStep" class="btn-secondary" wire:loading.attr="disabled">Nazad</button>
                    @endif

                    <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                        {{ $isLastStep ? ($listing ? 'Sačuvaj izmene' : 'Objavi oglas') : 'Nastavi' }}
                    </button>
                </div>
            </div>
        </section>
    </form>
</div>
