<div class="space-y-8">
    <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
        <div>
            <div class="data-kicker">Moderacija, pristupi i operacije</div>
            <h1 class="section-title mt-2">Kontrola platforme</h1>
            <p class="section-copy mt-3">Ovde upravljate korisnicima, nivoima pristupa, oglasima i dilerima kako bi ponuda na platformi ostala pouzdana i pregledna.</p>
        </div>

        <div class="panel flex flex-col gap-3 p-4 sm:flex-row sm:items-center">
            <input type="text" wire:model.live.debounce.350ms="query" class="input-shell w-full sm:w-80" placeholder="Pretraga korisnika, dilera ili oglasa">
            <select wire:model.live="section" class="input-shell w-full sm:w-52">
                <option value="overview">Pregled</option>
                <option value="users">Korisnici</option>
                <option value="listings">Oglasi</option>
                <option value="roles">Nivoi pristupa</option>
                <option value="dealers">Dileri</option>
            </select>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-7">
        <div class="stat-card">
            <div class="metric-value">{{ number_format($summary['users'], 0, ',', '.') }}</div>
            <div class="metric-label">Ukupno korisnika</div>
        </div>
        <div class="stat-card">
            <div class="metric-value">{{ number_format($summary['admins'], 0, ',', '.') }}</div>
            <div class="metric-label">Admin naloga</div>
        </div>
        <div class="stat-card">
            <div class="metric-value">{{ number_format($summary['dealers'], 0, ',', '.') }}</div>
            <div class="metric-label">Dilerskih naloga</div>
        </div>
        <div class="stat-card">
            <div class="metric-value">{{ number_format($summary['activeListings'], 0, ',', '.') }}</div>
            <div class="metric-label">Aktivnih oglasa</div>
        </div>
        <div class="stat-card">
            <div class="metric-value">{{ number_format($summary['featuredListings'], 0, ',', '.') }}</div>
            <div class="metric-label">Istaknutih oglasa</div>
        </div>
        <div class="stat-card">
            <div class="metric-value">{{ number_format($summary['rejectedListings'], 0, ',', '.') }}</div>
            <div class="metric-label">Odbijenih oglasa</div>
        </div>
        <div class="stat-card">
            <div class="metric-value">{{ number_format($summary['verifiedDealers'], 0, ',', '.') }}</div>
            <div class="metric-label">Verifikovanih dilera</div>
        </div>
    </div>

    <div class="panel flex flex-wrap gap-2 p-3">
        @foreach([
            'overview' => 'Pregled',
            'users' => 'Korisnici',
            'listings' => 'Oglasi',
            'roles' => 'Nivoi pristupa',
            'dealers' => 'Dileri',
        ] as $value => $label)
            <button
                type="button"
                wire:click="$set('section', '{{ $value }}')"
                class="tab-button {{ $section === $value ? 'tab-button-active' : 'tab-button-inactive' }}"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    @if($section === 'overview')
        <div class="grid gap-6 xl:grid-cols-3">
            <div class="panel p-6">
                <div class="data-kicker">Pristupi i ovlašćenja</div>
                <h2 class="font-display mt-2 text-2xl font-bold text-white">Administrativna struktura</h2>
                <div class="mt-6 space-y-3">
                    @foreach($roles as $role)
                        <div class="panel-soft p-4">
                            <div class="flex items-center justify-between">
                                <div class="font-semibold text-white">{{ $roleOptions[$role->name] ?? ucfirst($role->name) }}</div>
                                <div class="text-xs uppercase tracking-[0.18em] text-slate-500">{{ $role->permissions->count() }} dozvola</div>
                            </div>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach($role->permissions->take(5) as $permission)
                                    <span class="rounded-full border border-white/10 bg-white/5 px-2.5 py-1 text-xs text-slate-300">{{ $permissionLabels[$permission->name] ?? $permission->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="panel p-6 xl:col-span-2">
                <div class="data-kicker">Moderacija oglasa</div>
                <h2 class="font-display mt-2 text-2xl font-bold text-white">Sveži oglasi pod kontrolom</h2>
                <div class="mt-6 space-y-3">
                    @forelse($listings->take(8) as $listing)
                        <div class="panel-soft flex flex-col gap-4 p-4 lg:flex-row lg:items-center lg:justify-between">
                            <div class="flex items-center gap-4">
                                <img src="{{ $listing->primaryImageUrl() }}" alt="{{ $listing->title }}" class="h-18 w-24 rounded-2xl object-cover">
                                <div>
                                    <div class="font-semibold text-white">{{ $listing->title }}</div>
                                    <div class="text-sm text-slate-400">{{ $listing->user->name }} · {{ $listing->status->label() }}</div>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" wire:click="setStatus({{ $listing->id }}, 'published')" class="btn-ghost">Odobri</button>
                                <button type="button" wire:click="toggleFeatured({{ $listing->id }})" class="btn-ghost">{{ $listing->is_featured ? 'Skini isticanje' : 'Istakni' }}</button>
                                <a href="{{ route('listings.show', $listing) }}" wire:navigate class="btn-secondary">Detalji</a>
                            </div>
                        </div>
                    @empty
                        <div class="panel-soft p-6 text-sm text-slate-300">Nema oglasa za pregled.</div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    @if($section === 'users')
        <div class="panel p-5">
            <div class="grid gap-4 lg:grid-cols-[1fr_180px_180px]">
                <select wire:model.live="userRoleFilter" class="input-shell w-full">
                    <option value="">Sve vrste naloga</option>
                    @foreach($roleOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                <select wire:model.live="userStatusFilter" class="input-shell w-full">
                    <option value="all">Svi statusi</option>
                    <option value="active">Aktivni</option>
                    <option value="banned">Banjovani</option>
                </select>
                <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-400">Prikazano: {{ $users->count() }}</div>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($users as $user)
                <div class="panel p-5" wire:key="admin-user-{{ $user->id }}">
                    <div class="grid gap-5 xl:grid-cols-[1.2fr_0.8fr_0.8fr]">
                        <div>
                            <div class="font-display text-2xl font-bold text-white">{{ $user->name }}</div>
                            <div class="mt-1 text-sm text-slate-400">{{ $user->email }} · {{ $user->city ?: 'Bez grada' }}</div>
                            <div class="mt-3 flex flex-wrap gap-2">
                                <span class="rounded-full border border-cyan-300/20 bg-cyan-400/10 px-3 py-1 text-xs text-cyan-100">{{ $user->roleLabel() }}</span>
                                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-300">
                                    {{ $user->email_verified_at ? 'Email verifikovan' : 'Bez verifikacije' }}
                                </span>
                                <span class="rounded-full border px-3 py-1 text-xs {{ $user->is_banned ? 'border-rose-300/20 bg-rose-400/10 text-rose-100' : 'border-emerald-300/20 bg-emerald-400/10 text-emerald-100' }}">
                                    {{ $user->is_banned ? 'Suspendovan' : 'Aktivan' }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-2 text-sm text-slate-300">
                            <div>Oglasi: <span class="font-semibold text-white">{{ $user->listings_count }}</span></div>
                            <div>Favoriti: <span class="font-semibold text-white">{{ $user->favorite_listings_count }}</span></div>
                            <div>Sačuvane pretrage: <span class="font-semibold text-white">{{ $user->saved_searches_count }}</span></div>
                            <div>Poslednja aktivnost: <span class="font-semibold text-white">{{ $user->last_seen_at?->diffForHumans() ?? 'Nema podatka' }}</span></div>
                        </div>

                        <div class="space-y-3">
                            <select wire:change="setUserRole({{ $user->id }}, $event.target.value)" class="input-shell w-full">
                                @foreach($roleOptions as $value => $label)
                                    <option value="{{ $value }}" @selected($user->role->value === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <button type="button" wire:click="toggleBan({{ $user->id }})" class="btn-secondary w-full">
                                {{ $user->is_banned ? 'Vrati pristup' : 'Suspenduj korisnika' }}
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="panel p-8 text-slate-300">Nema korisnika za prikaz sa zadatim filterima.</div>
            @endforelse
        </div>
    @endif

    @if($section === 'listings')
        <div class="panel p-5">
            <div class="grid gap-4 lg:grid-cols-[1fr_220px]">
                <select wire:model.live="listingStatusFilter" class="input-shell w-full">
                    <option value="">Svi statusi oglasa</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
                <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-400">Prikazano: {{ $listings->count() }}</div>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($listings as $listing)
                <div class="panel p-5" wire:key="admin-listing-{{ $listing->id }}">
                    <div class="grid gap-5 xl:grid-cols-[1fr_0.75fr]">
                        <div class="flex gap-4">
                            <img src="{{ $listing->primaryImageUrl() }}" alt="{{ $listing->title }}" class="h-24 w-32 rounded-2xl object-cover">
                            <div class="min-w-0 flex-1">
                                <div class="font-display text-2xl font-bold text-white">{{ $listing->title }}</div>
                                <div class="mt-1 text-sm text-slate-400">{{ $listing->user->name }} · {{ $listing->city }} · {{ number_format($listing->price, 0, ',', '.') }} €</div>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-300">{{ $listing->status->label() }}</span>
                                    <span class="rounded-full border border-cyan-300/20 bg-cyan-400/10 px-3 py-1 text-xs text-cyan-100">AutoIQ {{ $listing->autoiq_score }}</span>
                                    @if($listing->is_featured)
                                        <span class="rounded-full border border-amber-300/20 bg-amber-400/10 px-3 py-1 text-xs text-amber-100">Istaknut</span>
                                    @endif
                                    @if($listing->dealerProfile)
                                        <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-300">Diler: {{ $listing->dealerProfile->company_name }}</span>
                                    @endif
                                </div>
                                @if($listing->rejected_reason)
                                    <div class="mt-3 rounded-2xl border border-rose-300/20 bg-rose-400/10 px-4 py-3 text-sm text-rose-100">
                                        Razlog odbijanja: {{ $listing->rejected_reason }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="grid gap-2 sm:grid-cols-2">
                                <button type="button" wire:click="setStatus({{ $listing->id }}, 'published')" class="btn-secondary">Objavi</button>
                                <button type="button" wire:click="setStatus({{ $listing->id }}, 'sold')" class="btn-ghost">Prodat</button>
                                <button type="button" wire:click="toggleFeatured({{ $listing->id }})" class="btn-secondary sm:col-span-2">
                                    {{ $listing->is_featured ? 'Skini isticanje' : 'Istakni oglas' }}
                                </button>
                            </div>

                            <textarea wire:model.live="listingNotes.{{ $listing->id }}" class="textarea-shell w-full" placeholder="Upišite razlog odbijanja ili moderacionu belešku."></textarea>
                            <button type="button" wire:click="setStatus({{ $listing->id }}, 'rejected')" class="btn-ghost w-full text-rose-200 hover:text-rose-100">Odbij oglas</button>
                            <a href="{{ route('listings.show', $listing) }}" wire:navigate class="btn-primary w-full">Otvori oglas</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="panel p-8 text-slate-300">Nema oglasa za prikaz sa zadatim filterima.</div>
            @endforelse
        </div>
    @endif

    @if($section === 'roles')
        <div class="grid gap-6 xl:grid-cols-3">
            @foreach($roles as $role)
                <div class="panel p-6" wire:key="admin-role-{{ $role->id }}">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="data-kicker">Nivoi pristupa</div>
                            <h2 class="font-display mt-2 text-2xl font-bold text-white">{{ $roleOptions[$role->name] ?? ucfirst($role->name) }}</h2>
                        </div>
                        <div class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs uppercase tracking-[0.18em] text-slate-400">{{ $role->permissions->count() }} dozvola</div>
                    </div>

                    <div class="mt-6 space-y-3">
                        @foreach($permissionLabels as $permission => $label)
                            <label class="panel-soft flex items-center justify-between gap-3 p-3 text-sm text-slate-200">
                                <span>{{ $label }}</span>
                                <button
                                    type="button"
                                    wire:click="toggleRolePermission('{{ $role->name }}', '{{ $permission }}')"
                                    class="rounded-full px-3 py-1 text-xs font-semibold {{ $role->hasPermissionTo($permission) ? 'bg-emerald-400 text-slate-950' : 'bg-slate-800 text-slate-300' }}"
                                >
                                    {{ $role->hasPermissionTo($permission) ? 'Dodeljeno' : 'Dodaj' }}
                                </button>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if($section === 'dealers')
        <div class="panel p-5">
            <div class="grid gap-4 lg:grid-cols-[1fr_220px]">
                <select wire:model.live="dealerVerificationFilter" class="input-shell w-full">
                    <option value="all">Svi dileri</option>
                    <option value="verified">Verifikovani</option>
                    <option value="unverified">Neverifikovani</option>
                </select>
                <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-400">Prikazano: {{ $dealers->count() }}</div>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($dealers as $dealer)
                <div class="panel p-5" wire:key="admin-dealer-{{ $dealer->id }}">
                    <div class="grid gap-5 xl:grid-cols-[1fr_0.75fr]">
                        <div>
                            <div class="font-display text-2xl font-bold text-white">{{ $dealer->company_name }}</div>
                            <div class="mt-1 text-sm text-slate-400">{{ $dealer->city ?: 'Bez grada' }} · {{ $dealer->email ?: 'Bez emaila' }}</div>
                            <div class="mt-3 text-sm leading-7 text-slate-300">{{ $dealer->description ?: 'Opis nije unet.' }}</div>
                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-300">Aktivni oglasi: {{ $dealer->listings_count }}</span>
                                <span class="rounded-full border px-3 py-1 text-xs {{ $dealer->verified_at ? 'border-emerald-300/20 bg-emerald-400/10 text-emerald-100' : 'border-amber-300/20 bg-amber-400/10 text-amber-100' }}">
                                    {{ $dealer->verified_at ? 'Verifikovan' : 'Čeka verifikaciju' }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <button type="button" wire:click="toggleDealerVerification({{ $dealer->id }})" class="btn-secondary w-full">
                                {{ $dealer->verified_at ? 'Ukloni verifikaciju' : 'Verifikuj dilera' }}
                            </button>
                            <a href="{{ route('dealers.show', $dealer) }}" wire:navigate class="btn-primary w-full">Otvori profil dilera</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="panel p-8 text-slate-300">Nema dilera za prikaz sa zadatim filterima.</div>
            @endforelse
        </div>
    @endif
</div>
