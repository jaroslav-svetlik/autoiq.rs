@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Paginacija" class="panel-soft flex flex-col gap-4 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5">
            <div class="text-sm leading-6 text-slate-400">
                Prikazano
                <span class="font-semibold text-white">{{ number_format($paginator->firstItem() ?? 0, 0, ',', '.') }}</span>
                -
                <span class="font-semibold text-white">{{ number_format($paginator->lastItem() ?? 0, 0, ',', '.') }}</span>
                od
                <span class="font-semibold text-amber-200">{{ number_format($paginator->total(), 0, ',', '.') }}</span>
                rezultata
            </div>

            <div class="flex items-center justify-between gap-3 sm:hidden">
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" aria-label="Prethodna strana" class="inline-flex min-h-11 items-center justify-center rounded-2xl border border-white/8 bg-white/5 px-4 text-sm font-semibold text-slate-500">
                        Prethodna
                    </span>
                @else
                    <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before" class="inline-flex min-h-11 items-center justify-center rounded-2xl border border-white/12 bg-white/8 px-4 text-sm font-semibold text-slate-100 transition hover:border-amber-300/35 hover:bg-amber-400/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-amber-300/30 disabled:cursor-not-allowed disabled:opacity-60">
                        Prethodna
                    </button>
                @endif

                @if ($paginator->hasMorePages())
                    <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before" class="inline-flex min-h-11 items-center justify-center rounded-2xl bg-amber-400 px-4 text-sm font-bold text-slate-950 transition hover:bg-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300/40 disabled:cursor-not-allowed disabled:opacity-60">
                        Sledeća
                    </button>
                @else
                    <span aria-disabled="true" aria-label="Sledeća strana" class="inline-flex min-h-11 items-center justify-center rounded-2xl border border-white/8 bg-white/5 px-4 text-sm font-semibold text-slate-500">
                        Sledeća
                    </span>
                @endif
            </div>

            <div class="hidden sm:flex sm:items-center sm:justify-end">
                <div class="inline-flex items-center gap-1 rounded-2xl border border-white/10 bg-slate-950/50 p-1 shadow-[0_16px_45px_rgba(2,6,23,0.28)]">
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Prethodna strana" class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-slate-600">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    @else
                        <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after" class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-slate-300 transition hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-amber-300/30 disabled:cursor-not-allowed disabled:opacity-60" aria-label="Prethodna strana">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    @endif

                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span aria-disabled="true" class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl px-3 text-sm font-semibold text-slate-500">
                                {{ $element }}
                            </span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                    @if ($page == $paginator->currentPage())
                                        <span aria-current="page" class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl bg-amber-400 px-3 text-sm font-bold text-slate-950 shadow-[0_10px_28px_rgba(245,158,11,0.32)]">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <button type="button" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl px-3 text-sm font-semibold text-slate-300 transition hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-amber-300/30 disabled:cursor-not-allowed disabled:opacity-60" aria-label="Idi na stranu {{ $page }}">
                                            {{ $page }}
                                        </button>
                                    @endif
                                </span>
                            @endforeach
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after" class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-slate-300 transition hover:bg-amber-400 hover:text-slate-950 focus:outline-none focus:ring-2 focus:ring-amber-300/40 disabled:cursor-not-allowed disabled:opacity-60" aria-label="Sledeća strana">
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
