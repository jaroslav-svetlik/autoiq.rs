@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = $scrollTo !== false ? 'scrollToTarget()' : '';

$currentPage = $paginator->currentPage();
$lastPage = $paginator->lastPage();
$compactPages = $lastPage <= 7
    ? range(1, $lastPage)
    : [
        1,
        $currentPage - 1,
        $currentPage,
        $currentPage + 1,
        ...($currentPage <= 2 ? [2, 3] : []),
        ...($currentPage >= $lastPage - 1 ? [$lastPage - 2, $lastPage - 1] : []),
        $lastPage,
    ];

$compactPages = array_values(array_unique(array_filter(
    $compactPages,
    fn ($page) => $page >= 1 && $page <= $lastPage,
)));

sort($compactPages);

$compactPagination = [];
$previousPage = null;

foreach ($compactPages as $page) {
    if ($previousPage !== null && $page - $previousPage > 1) {
        if ($page - $previousPage === 2) {
            $compactPagination[] = [
                'type' => 'page',
                'page' => $previousPage + 1,
            ];
        } else {
            $compactPagination[] = [
                'type' => 'gap',
                'key' => "{$previousPage}-{$page}",
            ];
        }
    }

    $compactPagination[] = [
        'type' => 'page',
        'page' => $page,
    ];

    $previousPage = $page;
}
@endphp

<div x-data="paginationControls" data-scroll-target="{{ $scrollTo === false ? '' : $scrollTo }}">
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Paginacija" class="panel-soft flex flex-col gap-4 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5">
            <div class="text-sm leading-6 text-muted">
                Prikazano
                <span class="font-semibold text-ink">{{ number_format($paginator->firstItem() ?? 0, 0, ',', '.') }}</span>
                -
                <span class="font-semibold text-ink">{{ number_format($paginator->lastItem() ?? 0, 0, ',', '.') }}</span>
                od
                <span class="font-semibold text-brand">{{ number_format($paginator->total(), 0, ',', '.') }}</span>
                rezultata
            </div>

            <div class="flex items-center justify-between gap-3 sm:hidden">
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" aria-label="Prethodna strana" class="inline-flex min-h-11 items-center justify-center rounded-2xl border border-line bg-wash px-4 text-sm font-semibold text-muted">
                        Prethodna
                    </span>
                @else
                    <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before" class="inline-flex min-h-11 items-center justify-center rounded-2xl border border-line bg-wash px-4 text-sm font-semibold text-ink transition hover:border-brand/20 hover:bg-brand/5 hover:text-ink focus:outline-none focus:ring-2 focus:ring-brand/20 disabled:cursor-not-allowed disabled:opacity-60">
                        Prethodna
                    </button>
                @endif

                @if ($paginator->hasMorePages())
                    <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before" class="inline-flex min-h-11 items-center justify-center rounded-2xl bg-brand px-4 text-sm font-bold text-white transition hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand/20 disabled:cursor-not-allowed disabled:opacity-60">
                        Sledeća
                    </button>
                @else
                    <span aria-disabled="true" aria-label="Sledeća strana" class="inline-flex min-h-11 items-center justify-center rounded-2xl border border-line bg-wash px-4 text-sm font-semibold text-muted">
                        Sledeća
                    </span>
                @endif
            </div>

            <div class="hidden sm:flex sm:items-center sm:justify-end">
                <div class="inline-flex items-center gap-1 rounded-2xl border border-line bg-white p-1 shadow-[0_16px_45px_rgba(28,59,136,0.06)]">
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Prethodna strana" class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-slate-600">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    @else
                        <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after" class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-muted transition hover:bg-wash hover:text-ink focus:outline-none focus:ring-2 focus:ring-brand/20 disabled:cursor-not-allowed disabled:opacity-60" aria-label="Prethodna strana">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    @endif

                    @foreach ($compactPagination as $entry)
                        @if ($entry['type'] === 'gap')
                            <span wire:key="paginator-{{ $paginator->getPageName() }}-gap{{ $entry['key'] }}" aria-disabled="true" class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl px-3 text-sm font-semibold text-muted">
                                ...
                            </span>
                        @else
                            <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $entry['page'] }}">
                                @if ($entry['page'] == $paginator->currentPage())
                                    <span aria-current="page" class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl bg-brand px-3 text-sm font-bold text-white shadow-[0_10px_28px_rgba(36,66,255,0.15)]">
                                        {{ $entry['page'] }}
                                    </span>
                                @else
                                    <button type="button" wire:click="gotoPage({{ $entry['page'] }}, '{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl px-3 text-sm font-semibold text-muted transition hover:bg-wash hover:text-ink focus:outline-none focus:ring-2 focus:ring-brand/20 disabled:cursor-not-allowed disabled:opacity-60" aria-label="Idi na stranu {{ $entry['page'] }}">
                                        {{ $entry['page'] }}
                                    </button>
                                @endif
                            </span>
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after" class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-muted transition hover:bg-brand hover:text-slate-950 focus:outline-none focus:ring-2 focus:ring-brand/20 disabled:cursor-not-allowed disabled:opacity-60" aria-label="Sledeća strana">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    @else
                        <span aria-disabled="true" aria-label="Sledeća strana" class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-slate-600">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    @endif
                </div>
            </div>
        </nav>
    @endif
</div>
