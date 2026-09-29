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
                            class="flex w-full items-center gap-3 rounded-lg border px-3 py-3 text-left transition {{ $currentStep === $number ? 'border-brand/20 bg-brand/5 text-ink' : ($number < $currentStep ? 'border-emerald-300/25 bg-emerald-400/5 text-ink hover:border-emerald-300/40' : 'border-line bg-wash text-muted hover:border-line hover:bg-wash') }}"
                        >
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border {{ $currentStep === $number ? 'border-brand/20 bg-brand text-white' : ($number < $currentStep ? 'border-emerald-300/40 bg-emerald-600 text-white' : 'border-line bg-white text-muted') }}">
                                {{ $number }}
                            </span>
                            <span class="min-w-0">
                                <span class="block text-sm font-bold">{{ $step['title'] }}</span>
                                <span class="mt-0.5 block truncate text-xs text-muted">{{ $step['summary'] }}</span>
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="panel p-5">
                <div class="data-kicker">{{ $currentStepData['kicker'] }}</div>
                <h2 class="font-display mt-2 text-2xl font-bold text-ink">{{ $currentStepData['title'] }}</h2>
                <p class="mt-3 text-sm leading-7 text-muted">{{ $currentStepData['summary'] }}</p>
                @error('rate_limit') <p class="mt-4 text-sm text-rose-700">{{ $message }}</p> @enderror
            </div>
        </aside>

        <section class="min-w-0 space-y-6">
            @if($currentStep === 1)
                <div class="panel p-6 sm:p-8">
                    <div class="mb-6">
                        <div class="data-kicker">Vozilo</div>
                        <h2 class="font-display mt-2 text-2xl font-bold text-ink">Osnovni podaci</h2>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="field-label">Naslov</label>
                            <input type="text" wire:model.live="titleInput" class="input-shell w-full" placeholder="BMW 320d xDrive M paket, prvi vlasnik">
                            @error('titleInput') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Marka</label>
                            <x-select wire:model.live="brand" class="input-shell w-full">
                                <option value="">Izaberite marku</option>
                                @foreach($vehicleBrands as $vehicleBrand)
                                    <option value="{{ $vehicleBrand }}">{{ $vehicleBrand }}</option>
                                @endforeach
                            </x-select>
                            @error('brand') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Model</label>
                            <x-select wire:model.live="model" class="input-shell w-full" :disabled="blank($brand)">
                                <option value="">{{ blank($brand) ? 'Prvo izaberite marku' : 'Izaberite model' }}</option>
                                @foreach($vehicleModels as $vehicleModel)
                                    <option value="{{ $vehicleModel }}">{{ $vehicleModel }}</option>
                                @endforeach
                            </x-select>
                            @error('model') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Godište</label>
                            <input type="number" wire:model.live="year" class="input-shell w-full" placeholder="2018">
                            @error('year') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Cena (€)</label>
                            <input type="number" wire:model.live="price" class="input-shell w-full" placeholder="15900">
                            @error('price') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Kilometraža</label>
                            <input type="number" wire:model.live="mileage" class="input-shell w-full" placeholder="164000">
                            @error('mileage') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Gorivo</label>
                            <x-select wire:model.live="fuelType" class="input-shell w-full">
                                <option value="">Izaberite</option>
                                @foreach($fuelTypes as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </x-select>
                            @error('fuelType') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Menjač</label>
                            <x-select wire:model.live="transmission" class="input-shell w-full">
                                <option value="">Izaberite</option>
                                @foreach($transmissionTypes as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </x-select>
                            @error('transmission') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Lokacija</label>
                            <x-select wire:model.live="city" class="input-shell w-full">
                                <option value="">Izaberite grad</option>
                                @foreach($cities as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </x-select>
                            @error('city') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                        </div>

                        <div @class(['md:col-span-2', 'has-error' => $errors->has('description')])>
                            <x-rich-text-editor model="description" :value="$description" label="Opis oglasa" />
                            <p id="description-error" class="mt-2 text-sm text-rose-700" role="status">@error('description'){{ $message }}@enderror</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($currentStep === 2)
                <div class="panel p-6 sm:p-8">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <div class="data-kicker">Oprema</div>
                            <h2 class="font-display mt-2 text-2xl font-bold text-ink">Šta vozilo poseduje</h2>
                        </div>
                        <div class="text-sm text-muted">{{ count($equipment) }} odabranih stavki</div>
                    </div>

                    <div class="mt-6 grid gap-4 xl:grid-cols-2">
                        @foreach($equipmentCatalog as $group)
                            <div class="panel-soft p-5">
                                <div class="data-kicker">{{ $group['label'] }}</div>
                                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                    @foreach($group['options'] as $option)
                                        <label class="flex items-start gap-3 rounded-lg border border-line bg-wash px-4 py-3 text-sm text-ink transition hover:border-brand/20 hover:bg-brand/5">
                                            <input
                                                type="checkbox"
                                                value="{{ $option['key'] }}"
                                                wire:model.live="equipment"
                                                class="mt-1 h-4 w-4 rounded border-line bg-white text-brand focus:ring-brand/20"
                                            >
                                            <span>{{ $option['label'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @error('equipment') <p class="mt-4 text-sm text-rose-700">{{ $message }}</p> @enderror
                    @error('equipment.*') <p class="mt-4 text-sm text-rose-700">{{ $message }}</p> @enderror
                </div>
            @endif

            @if($currentStep === 3)
                <div class="panel p-6 sm:p-8">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <div class="data-kicker">Galerija</div>
                            <h2 class="font-display mt-2 text-2xl font-bold text-ink">Fotografije</h2>
                        </div>
                        <div class="text-sm text-muted">{{ ($listing?->images->count() ?? 0) + count($newImages) }}/20 fotografija · do 1 MB po slici</div>
                    </div>

                    <p id="photo-order-help" class="mt-3 text-sm leading-relaxed text-muted">Prevucite fotografije da promenite redosled. Prva fotografija je naslovna na oglasu i u rezultatima pretrage. Redosled se čuva kada sačuvate oglas.</p>

                    <label class="photo-upload-zone mt-5">
                        <input type="file" wire:model.live="newImages" multiple accept=".jpg,.jpeg,.png,.webp" aria-label="Dodaj fotografije" wire:loading.attr="disabled">
                        <span class="photo-upload-icon"><x-icon name="camera" /></span>
                        <span><strong>Dodajte fotografije</strong><span>Prevucite fajlove ovde ili kliknite za izbor · JPG, PNG, WEBP</span></span>
                        <x-icon name="plus" />
                    </label>
                    <div role="status" class="mt-2 text-sm text-muted" wire:loading wire:target="newImages">Fotografije se otpremaju, sačekajte…</div>
                    @error('newImages') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                    @error('newImages.*') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror

                    <div class="photo-order-grid mt-5" wire:sort="sortImage" wire:loading.class="pointer-events-none opacity-60" aria-label="Redosled fotografija" aria-describedby="photo-order-help">
                        @foreach($imageOrder as $position => $key)
                            @php
                                $item = $imageItems[$key];
                                $image = $item['image'];
                            @endphp
                            <div class="photo-order-card {{ $position === 0 ? 'is-cover' : '' }}" wire:key="photo-{{ $key }}" wire:sort:item="{{ $key }}">
                                <div class="photo-order-preview" wire:sort:handle title="Prevucite za promenu redosleda">
                                    @if($item['existing'] || $image->isPreviewable())
                                        <img src="{{ $item['existing'] ? $image->url() : $image->temporaryUrl() }}" alt="Fotografija {{ $position + 1 }}" draggable="false">
                                    @else
                                        <span class="photo-invalid">Pregled nije dostupan</span>
                                    @endif
                                    <span class="photo-order-badge">{{ $position === 0 ? 'Naslovna' : $position + 1 }}</span>
                                    <span class="photo-drag-hint"><x-icon name="grid" /></span>
                                    @unless($item['existing']) <span class="photo-new-badge">Nova</span> @endunless
                                </div>
                                <div class="photo-order-actions" wire:sort:ignore>
                                    <button type="button" wire:click="sortImage('{{ $key }}', {{ $position - 1 }})" class="photo-order-button" @disabled($position === 0) wire:loading.attr="disabled" aria-label="Pomeri fotografiju {{ $position + 1 }} ranije" title="Pomeri ranije"><x-icon name="arrow" class="rotate-180" /></button>
                                    <button type="button" wire:click="sortImage('{{ $key }}', {{ $position + 1 }})" class="photo-order-button" @disabled($loop->last) wire:loading.attr="disabled" aria-label="Pomeri fotografiju {{ $position + 1 }} kasnije" title="Pomeri kasnije"><x-icon name="arrow" /></button>
                                    <button type="button" wire:click="{{ $item['existing'] ? 'deleteImage('.$image->id.')' : 'removeNewImage('.$item['index'].')' }}" class="photo-order-button photo-remove-button" wire:loading.attr="disabled" aria-label="Ukloni fotografiju {{ $position + 1 }}" title="Ukloni fotografiju"><x-lucide-icon name="trash-2" /></button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="sr-only" role="status" aria-live="polite">{{ $imageOrderAnnouncement }}</p>
                </div>
            @endif

            @if($currentStep === 4)
                <div class="panel p-6 sm:p-8">
                    <div>
                        <div class="data-kicker">Prodavac</div>
                        <h2 class="font-display mt-2 text-2xl font-bold text-ink">Podaci prodavca</h2>
                    </div>

                    <div class="mt-6 grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="field-label">Tip prodavca</label>
                            <x-select wire:model.live="sellerType" class="input-shell w-full">
                                @foreach($sellerTypes as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </x-select>
                            @error('sellerType') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Ime i prezime prodavca</label>
                            <input type="text" wire:model.live="sellerName" class="input-shell w-full" placeholder="Milan Petrović">
                            @error('sellerName') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <div class="grid gap-4 md:grid-cols-2">
                                @foreach($sellerPhones as $index => $phone)
                                    <div wire:key="seller-phone-{{ $index }}" class="flex items-start gap-3">
                                        <div class="min-w-0 flex-1">
                                            <label class="field-label">Telefon {{ $index + 1 }}</label>
                                            <input type="text" wire:model.live="sellerPhones.{{ $index }}" class="input-shell w-full" placeholder="+381 6x xxx xxxx">
                                            @error("sellerPhones.{$index}") <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                                        </div>

                                        @if(count($sellerPhones) > 1)
                                            <button type="button" wire:click="removeSellerPhone({{ $index }})" class="btn-ghost mt-7 shrink-0 text-rose-700 hover:text-rose-700">Ukloni</button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            @error('sellerPhones') <p class="mt-4 text-sm text-rose-700">{{ $message }}</p> @enderror

                            @if(count($sellerPhones) < 3)
                                <button type="button" wire:click="addSellerPhone" class="btn-secondary mt-5">Dodaj broj</button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="panel flex flex-col gap-4 p-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-sm text-muted">
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
