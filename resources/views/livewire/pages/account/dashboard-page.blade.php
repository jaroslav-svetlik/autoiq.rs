<div class="space-y-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <div class="data-kicker">Moj nalog</div>
            <h1 class="section-title mt-2">Moj AutoIQ prostor</h1>
            <p class="section-copy mt-3">Profil, moji oglasi, favoriti, alarmi i obaveštenja na jednom mestu.</p>
        </div>
        <a href="{{ route('listings.create') }}" wire:navigate class="btn-primary">Dodaj novi oglas</a>
    </div>

    <div class="panel flex flex-wrap gap-2 p-3">
        @foreach([
            'profil' => 'Profil',
            'oglasi' => 'Moji oglasi',
            'favoriti' => 'Favoriti',
            'pretrage' => 'Sačuvane pretrage',
            'obavestenja' => 'Obaveštenja',
        ] as $value => $label)
            <button
                type="button"
                wire:click="$set('tab', '{{ $value }}')"
                class="tab-button {{ $tab === $value ? 'tab-button-active' : 'tab-button-inactive' }}"
            >
                {{ $label }}
                @if($value === 'obavestenja' && $user->unreadNotifications->count())
                    <span class="ml-2 rounded-full bg-amber-400 px-2 py-0.5 text-xs font-bold text-slate-950">{{ $user->unreadNotifications->count() }}</span>
                @endif
            </button>
        @endforeach
    </div>

    @if($tab === 'profil')
        <div class="panel p-6 sm:p-8">
            <form wire:submit="saveProfile" class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="field-label">Ime i prezime</label>
                    <input type="text" wire:model.live="name" class="input-shell w-full">
                    @error('name') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="field-label">Email</label>
                    <input type="email" wire:model.live="email" class="input-shell w-full">
                    @error('email') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="field-label">Telefon</label>
                    <input type="text" wire:model.live="phone" class="input-shell w-full">
                </div>
                <div>
                    <label class="field-label">Grad</label>
                    <select wire:model.live="city" class="input-shell w-full">
                        <option value="">Izaberite grad</option>
                        @foreach($cities as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="field-label">O meni</label>
                    <textarea wire:model.live="bio" class="textarea-shell w-full" placeholder="Kratak opis vaših interesovanja ili iskustva sa kupovinom/prodajom vozila."></textarea>
                </div>

                @if($user->isDealer())
                    <div class="md:col-span-2 grid gap-6 rounded-3xl border border-amber-300/15 bg-amber-400/5 p-6 md:grid-cols-2">
                        <div>
                            <label class="field-label">Naziv dilera</label>
                            <input type="text" wire:model.live="dealerCompanyName" class="input-shell w-full">
                        </div>
                        <div>
                            <label class="field-label">Sajt dilera</label>
                            <input type="url" wire:model.live="dealerWebsite" class="input-shell w-full">
                        </div>
                        <div class="md:col-span-2">
                            <label class="field-label">Opis dilera</label>
                            <textarea wire:model.live="dealerDescription" class="textarea-shell w-full"></textarea>
                        </div>
                    </div>
                @endif

                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="btn-primary">Sačuvaj profil</button>
                </div>
            </form>
        </div>
    @endif

    @if($tab === 'oglasi')
        <div class="grid gap-6 lg:grid-cols-2 xl:grid-cols-3">
            @forelse($user->listings as $listing)
                <div class="space-y-3" wire:key="my-listing-{{ $listing->id }}">
                    <x-listing-card :listing="$listing" editable />
                    <button type="button" wire:click="deleteListing({{ $listing->id }})" class="btn-ghost w-full text-rose-200 hover:text-rose-100">Obriši oglas</button>
                </div>
            @empty
                <div class="panel p-8 text-slate-300 lg:col-span-3">Još nemate objavljenih oglasa.</div>
            @endforelse
        </div>
    @endif

    @if($tab === 'favoriti')
        <div class="grid gap-6 lg:grid-cols-2 xl:grid-cols-3">
            @forelse($user->favoriteListings as $listing)
                <div class="space-y-3" wire:key="favorite-{{ $listing->id }}">
                    <x-listing-card :listing="$listing" />
                    <button type="button" wire:click="removeFavorite({{ $listing->id }})" class="btn-secondary w-full">Ukloni iz favorita</button>
                </div>
            @empty
                <div class="panel p-8 text-slate-300 lg:col-span-3">Lista favorita je prazna.</div>
            @endforelse
        </div>
    @endif

    @if($tab === 'pretrage')
        <div class="grid gap-4 lg:grid-cols-2">
            @forelse($user->savedSearches as $search)
                <div class="panel p-6" wire:key="search-{{ $search->id }}">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="font-display text-2xl font-bold text-white">{{ $search->name }}</h2>
                            <p class="mt-2 text-sm text-slate-400">{{ $search->summary() }}</p>
                        </div>
                        <a href="{{ route('listings.index') }}?{{ $search->queryString() }}" wire:navigate class="btn-secondary">Otvori</a>
                    </div>

                    <div class="mt-5 flex flex-wrap gap-3">
                        <button type="button" wire:click="toggleSavedSearchFlag({{ $search->id }}, 'notify_new_matches')" class="btn-ghost {{ $search->notify_new_matches ? 'text-emerald-200' : 'text-slate-400' }}">
                            Obaveštenja za nove oglase: {{ $search->notify_new_matches ? 'uključeno' : 'isključeno' }}
                        </button>
                        <button type="button" wire:click="toggleSavedSearchFlag({{ $search->id }}, 'notify_price_drops')" class="btn-ghost {{ $search->notify_price_drops ? 'text-cyan-200' : 'text-slate-400' }}">
                            Pad cene: {{ $search->notify_price_drops ? 'uključeno' : 'isključeno' }}
                        </button>
                        <button type="button" wire:click="deleteSavedSearch({{ $search->id }})" class="btn-ghost text-rose-200 hover:text-rose-100">
                            Obriši
                        </button>
                    </div>
                </div>
            @empty
                <div class="panel p-8 text-slate-300 lg:col-span-2">Još nemate sačuvanih pretraga. Sačuvajte ih direktno sa stranice oglasa.</div>
            @endforelse
        </div>
    @endif

    @if($tab === 'obavestenja')
        <div class="space-y-4">
            <div class="flex justify-end">
                <button type="button" wire:click="markAllNotificationsRead" class="btn-secondary">Označi sve kao pročitano</button>
            </div>

            @forelse($notifications as $notification)
                <div class="panel p-5" wire:key="notification-{{ $notification->id }}">
                    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                        <div>
                            <div class="flex items-center gap-3">
                                <h2 class="text-lg font-semibold text-white">{{ $notification->data['title'] ?? 'Obaveštenje' }}</h2>
                                @if(!$notification->read_at)
                                    <span class="rounded-full bg-amber-400 px-2 py-0.5 text-xs font-bold text-slate-950">Novo</span>
                                @endif
                            </div>
                            <p class="mt-2 text-sm leading-7 text-slate-300">{{ $notification->data['message'] ?? '' }}</p>
                            <div class="mt-3 text-xs uppercase tracking-[0.18em] text-slate-500">{{ $notification->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="flex gap-2">
                            @if(isset($notification->data['url']))
                                <a href="{{ $notification->data['url'] }}" wire:navigate class="btn-secondary">Otvori</a>
                            @endif
                            @if(!$notification->read_at)
                                <button type="button" wire:click="markNotificationRead('{{ $notification->id }}')" class="btn-ghost">Pročitano</button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="panel p-8 text-slate-300">Trenutno nema obaveštenja.</div>
            @endforelse
        </div>
    @endif
</div>
