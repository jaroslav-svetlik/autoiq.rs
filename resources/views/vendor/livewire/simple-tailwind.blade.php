@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = $scrollTo !== false ? 'scrollToTarget()' : '';
@endphp

<div x-data="paginationControls" data-scroll-target="{{ $scrollTo === false ? '' : $scrollTo }}">
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Paginacija" class="panel-soft flex items-center justify-between gap-3 px-4 py-4 sm:px-5">
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="Prethodna strana" class="inline-flex min-h-11 items-center justify-center rounded-2xl border border-line bg-wash px-4 text-sm font-semibold text-muted">
                    Prethodna
                </span>
            @else
                @if (method_exists($paginator, 'getCursorName'))
                    <button type="button" dusk="previousPage" wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $paginator->previousCursor()->encode() }}" wire:click="setPage('{{ $paginator->previousCursor()->encode() }}', '{{ $paginator->getCursorName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="inline-flex min-h-11 items-center justify-center rounded-2xl border border-line bg-wash px-4 text-sm font-semibold text-ink transition hover:border-brand/20 hover:bg-brand/5 hover:text-ink focus:outline-none focus:ring-2 focus:ring-brand/20 disabled:cursor-not-allowed disabled:opacity-60">
                        Prethodna
                    </button>
                @else
                    <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" class="inline-flex min-h-11 items-center justify-center rounded-2xl border border-line bg-wash px-4 text-sm font-semibold text-ink transition hover:border-brand/20 hover:bg-brand/5 hover:text-ink focus:outline-none focus:ring-2 focus:ring-brand/20 disabled:cursor-not-allowed disabled:opacity-60">
                        Prethodna
                    </button>
                @endif
            @endif

            @if ($paginator->hasMorePages())
                @if (method_exists($paginator, 'getCursorName'))
                    <button type="button" dusk="nextPage" wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $paginator->nextCursor()->encode() }}" wire:click="setPage('{{ $paginator->nextCursor()->encode() }}', '{{ $paginator->getCursorName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="inline-flex min-h-11 items-center justify-center rounded-2xl bg-brand px-4 text-sm font-bold text-white transition hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand/20 disabled:cursor-not-allowed disabled:opacity-60">
                        Sledeća
                    </button>
                @else
                    <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" class="inline-flex min-h-11 items-center justify-center rounded-2xl bg-brand px-4 text-sm font-bold text-white transition hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand/20 disabled:cursor-not-allowed disabled:opacity-60">
                        Sledeća
                    </button>
                @endif
            @else
                <span aria-disabled="true" aria-label="Sledeća strana" class="inline-flex min-h-11 items-center justify-center rounded-2xl border border-line bg-wash px-4 text-sm font-semibold text-muted">
                    Sledeća
                </span>
            @endif
        </nav>
    @endif
</div>
