<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Stock Movements</h1>
            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                <span>{{ $totalMovements }}</span> of <span>{{ $allMovementsCount }}</span> movements
            </p>
        </div>
    </div>

    <section aria-label="Movement summary">
        <div class="grid grid-cols-2 xl:grid-cols-3 gap-4">
            <div class="stat-card rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-900/20" aria-hidden="true">
                        <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Stock In</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stockInTotal }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/20" aria-hidden="true">
                        <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Stock Out</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stockOutTotal }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 dark:bg-violet-900/20" aria-hidden="true">
                        <svg class="h-5 w-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Adjustments</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $adjustmentCount }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section aria-label="Stock movement list" class="rounded-2xl bg-white shadow-card dark:bg-gray-800">
        <div class="flex flex-col gap-3 border-b border-gray-100 p-5 dark:border-gray-700 lg:flex-row lg:items-center">
            <div class="relative flex-1 max-w-sm">
                <svg class="pointer-events-none absolute start-3 top-1/2 w-4 h-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                </svg>
                <input
                    type="search"
                    wire:model.live.debounce.200ms="search"
                    placeholder="Search product or remarks…"
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2.5 ps-9 pe-4 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200"
                    aria-label="Search stock movements"
                >
            </div>

            <select wire:model.live="typeFilter" class="field !w-auto min-h-[2.75rem] py-2 text-sm" aria-label="Filter by movement type">
                <option value="">All types</option>
                <option value="in">Stock In</option>
                <option value="out">Stock Out</option>
                <option value="adjustment">Adjustment</option>
            </select>

            <select wire:model.live="sort" class="field !w-auto min-h-[2.75rem] py-2 text-sm lg:ms-auto" aria-label="Sort movements">
                <option value="date_desc">Sort: Date (newest)</option>
                <option value="date_asc">Sort: Date (oldest)</option>
                <option value="qty_desc">Sort: Quantity (high → low)</option>
                <option value="qty_asc">Sort: Quantity (low → high)</option>
            </select>
        </div>

        <div class="tbl-wrap" tabindex="0" role="region" aria-label="Stock movement table">
            <table class="w-full text-sm" style="min-inline-size:58rem">
                <caption class="sr-only">Stock movement list</caption>
                <thead>
                    <tr class="border-b border-gray-100 text-left dark:border-gray-700">
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Product</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Type</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Qty</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Date</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Remarks</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">User</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse ($movements as $movement)
                        <tr class="transition-colors hover:bg-gray-50/60 dark:hover:bg-gray-700/40">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2.5">
                                    @if (!empty($movement['product_image']))
                                        <img src="{{ $movement['product_image'] }}" alt="{{ $movement['product'] }}" class="h-8 w-8 rounded-lg object-cover border border-gray-200 dark:border-gray-700" />
                                    @else
                                        <div class="flex h-8 w-8 items-center justify-center rounded-lg text-[10px] font-bold {{ $movement['badge_bg'] }} {{ $movement['badge_text'] }}">
                                            {{ $movement['initials'] }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="truncate font-medium text-gray-800 dark:text-gray-100">{{ $movement['product'] }}</p>
                                        <p class="truncate text-[11px] text-gray-400 dark:text-gray-500">{{ $movement['remarks'] !== '—' ? $movement['remarks'] : 'No remarks' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <span class="badge {{ match($movement['type']) { 'in' => 'bg-emerald-50 text-emerald-700', 'out' => 'bg-red-50 text-red-600', 'adjustment' => 'bg-violet-50 text-violet-700', default => 'bg-gray-100 text-gray-600' } }}">
                                    <span class="h-1.5 w-1.5 rounded-full flex-shrink-0 {{ match($movement['type']) { 'in' => 'bg-emerald-500', 'out' => 'bg-red-400', 'adjustment' => 'bg-violet-500', default => 'bg-gray-400' } }}" aria-hidden="true"></span>
                                    {{ $movement['type_label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-end font-semibold {{ $movement['type'] === 'out' ? 'text-red-500' : ($movement['type'] === 'in' ? 'text-emerald-600' : 'text-violet-600') }}">
                                {{ $movement['type'] === 'out' ? '-' : '+' }}{{ $movement['quantity'] }}
                            </td>
                            <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ $movement['date'] }}</td>
                            <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ $movement['remarks'] }}</td>
                            <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ $movement['user'] }}</td>
                            <td class="px-5 py-3">
                                <span class="badge bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 flex-shrink-0" aria-hidden="true"></span>
                                    {{ $movement['status'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center">
                                <div class="flex flex-col items-center justify-center gap-3 text-gray-400 dark:text-gray-500">
                                    <svg class="h-12 w-12 text-gray-300 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No stock movements match your filters.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (!empty($movements))
            @php
                $totalPages = max(1, (int) ceil($totalMovements / max(1, $perPage)));
                $startItem = $totalMovements === 0 ? 0 : (($currentPage - 1) * $perPage) + 1;
                $endItem = min($currentPage * $perPage, $totalMovements);
            @endphp

            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-5 py-4 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400">Showing
                    <strong>{{ $startItem }}</strong>–<strong>{{ $endItem }}</strong> of <strong>{{ $totalMovements }}</strong>
                </p>
                <div class="flex items-center gap-1">
                    <button type="button" wire:click="previousPage" @disabled($currentPage <= 1)
                        class="min-h-[36px] rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 transition-colors hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                        ← Prev
                    </button>

                    @for ($page = 1; $page <= $totalPages; $page++)
                        <button type="button" wire:click="goToPage({{ $page }})"
                            class="h-9 w-9 rounded-lg text-xs font-semibold {{ $page === $currentPage ? 'bg-brand-600 text-white' : 'border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            {{ $page }}
                        </button>
                    @endfor

                    <button type="button" wire:click="nextPage" @disabled($currentPage >= $totalPages)
                        class="min-h-[36px] rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 transition-colors hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                        Next →
                    </button>
                </div>
            </div>
        @endif
    </section>
</div>
