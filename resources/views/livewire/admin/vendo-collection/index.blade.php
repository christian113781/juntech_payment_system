<div class="space-y-6">
    <div>
        <a href="{{ route('vendo-partners.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-500 transition-colors hover:text-brand-600">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Vendo Partners
        </a>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-900 sm:text-2xl dark:text-white">{{ $partner->name }}</h1>
            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Collection history and schedule for this partner</p>
        </div>

        <button type="button" wire:click="openCollectModal()" class="flex min-h-[44px] items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Record Collection
        </button>
    </div>

    <section class="rounded-2xl bg-white p-5 shadow-card dark:bg-gray-800 sm:p-6" aria-labelledby="summary-heading">
        <h2 id="summary-heading" class="sr-only">Collection summary</h2>
        <div class="grid grid-cols-2 gap-x-4 gap-y-5 lg:grid-cols-4">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">Assigned Vendo Unit</p>
                <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $partner->unit?->name ?? 'Unassigned' }}</p>
            </div>
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">Area</p>
                <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $partner->area?->name ?? '—' }}</p>
            </div>
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">Share Rate</p>
                <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $partner->share_rate }}%</p>
            </div>
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">Partner Status</p>
                <span class="mt-1 inline-flex rounded-full px-2.5 py-1 text-[10px] font-semibold
                    @if($partner->status === 'active') bg-emerald-50 text-emerald-700
                    @elseif($partner->status === 'inactive') bg-gray-100 text-gray-500
                    @else bg-amber-50 text-amber-700 @endif">
                    {{ ucfirst($partner->status) }}
                </span>
            </div>

            <div class="col-span-2 lg:col-span-1">
                <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">Last Collection</p>
                <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $partner->last_collected_at ? $partner->last_collected_at->format('M d, Y') : 'None' }}</p>
            </div>
            <div class="col-span-2 lg:col-span-1">
                <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">Next Collection</p>
                <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-gray-200">
                    @if($partner->last_collected_at)
                        {{ $partner->last_collected_at->copy()->addDays(max(1, (int) ($partner->collection_interval_days ?: 32)))->format('M d, Y') }}
                    @else
                        —
                    @endif
                </p>
            </div>
            <div class="col-span-2 lg:col-span-2">
                <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">Collection Status</p>
                <div class="mt-1">
                    @php
                        $interval = max(1, (int) ($partner->collection_interval_days ?: 32));
                        $daysLeft = $partner->last_collected_at ? (int) now()->startOfDay()->diffInDays($partner->last_collected_at->copy()->addDays($interval), false) : null;
                    @endphp
                    <span class="inline-flex rounded-full px-2.5 py-1 text-sm font-semibold
                        @if($daysLeft === null) bg-gray-100 text-gray-500
                        @elseif($daysLeft < 0) bg-red-100 text-red-700
                        @elseif($daysLeft === 0) bg-orange-100 text-orange-700
                        @elseif($daysLeft <= 7) bg-amber-50 text-amber-700
                        @else bg-emerald-50 text-emerald-700 @endif">
                        @if($daysLeft === null)
                            No collection recorded
                        @elseif($daysLeft < 0)
                            {{ abs($daysLeft) }} {{ abs($daysLeft) === 1 ? 'day' : 'days' }} overdue
                        @elseif($daysLeft === 0)
                            Due today
                        @elseif($daysLeft === 1)
                            1 day left
                        @else
                            {{ $daysLeft }} days left
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </section>

    <section aria-labelledby="totals-heading">
        <h2 id="totals-heading" class="sr-only">Collection totals</h2>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
            <div class="flex items-center gap-3 rounded-2xl bg-white p-4 shadow-card">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50" aria-hidden="true">
                    <svg class="h-5 w-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Collections</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ count($collections) }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-2xl bg-white p-4 shadow-card">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50" aria-hidden="true">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Collected</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">₱{{ number_format($totalAmount, 2) }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-2xl bg-white p-4 shadow-card">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50" aria-hidden="true">
                    <svg class="h-5 w-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Partner Share</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">₱{{ number_format($shareAmount, 2) }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-2xl bg-white p-4 shadow-card">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50" aria-hidden="true">
                    <svg class="h-5 w-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Owner Amount</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">₱{{ number_format($ownerAmount, 2) }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="rounded-2xl bg-white shadow-card dark:bg-gray-800" aria-labelledby="history-heading">
        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-gray-700">
            <h2 id="history-heading" class="text-base font-bold text-gray-900 dark:text-white">Collection History</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400"><span>{{ $totalCollections }}</span> record{{ $totalCollections === 1 ? '' : 's' }}</p>
        </div>

        <div class="tbl-wrap" tabindex="0" role="region" aria-labelledby="history-heading">
            <table class="w-full text-sm" style="min-inline-size:48rem">
                <caption class="sr-only">Collection history for this partner</caption>
                <thead>
                    <tr class="border-b border-gray-100 text-left dark:border-gray-700">
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500">Collection Date</th>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 text-end">Total Amount</th>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 text-end">Partner Share</th>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 text-end">Owner Amount</th>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500">Remarks</th>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse ($paginatedCollections as $collection)
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/40">
                            <td class="px-5 py-3 font-medium text-gray-800 dark:text-gray-200 whitespace-nowrap">{{ \Carbon\Carbon::parse($collection['collection_date'])->format('M d, Y') }}</td>
                            <td class="px-5 py-3 text-end font-semibold text-gray-800 dark:text-gray-200">₱{{ number_format((float) $collection['total_amount'], 2) }}</td>
                            <td class="px-5 py-3 text-end font-semibold text-brand-700">₱{{ number_format((float) $collection['share_amount'], 2) }}</td>
                            <td class="px-5 py-3 text-end font-bold text-gray-900 dark:text-white">₱{{ number_format((float) $collection['owner_amount'], 2) }}</td>
                            <td class="px-5 py-3 text-gray-500 dark:text-gray-400 max-w-[12rem] truncate" title="{{ $collection['remarks'] ?? '' }}">{{ $collection['remarks'] ?: '—' }}</td>
                            <td class="px-5 py-3 text-end">
                                <div class="flex items-center justify-end gap-1">
                                    <button type="button" wire:click="openDeleteModal({{ $collection['id'] }})" class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-red-50 hover:text-red-500" aria-label="Delete collection from {{ \Carbon\Carbon::parse($collection['collection_date'])->format('M d, Y') }}">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 3h5a1 1 0 011 1v3h-7V4a1 1 0 011-1z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-400">No collections recorded yet for this partner.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($totalCollections > 0)
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-5 py-4 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Showing <strong>{{ $startItem }}</strong>–<strong>{{ $endItem }}</strong> of <strong>{{ $totalCollections }}</strong>
                </p>

                <div class="flex items-center gap-1">
                    <button type="button" wire:click="previousPage" @disabled($currentPage <= 1)
                        class="min-h-[36px] rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 transition-colors hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40">
                        ← Prev
                    </button>

                    @for ($page = 1; $page <= $totalPages; $page++)
                        <button type="button" wire:click="goToPage({{ $page }})"
                            class="h-9 w-9 rounded-lg text-xs font-semibold {{ $page === $currentPage ? 'bg-brand-600 text-white' : 'border border-gray-200 bg-white text-gray-600 hover:bg-gray-50' }}">
                            {{ $page }}
                        </button>
                    @endfor

                    <button type="button" wire:click="nextPage" @disabled($currentPage >= $totalPages)
                        class="min-h-[36px] rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 transition-colors hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40">
                        Next →
                    </button>
                </div>
            </div>
        @endif
    </section>

    @if ($showCollectModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="w-full max-w-md rounded-[1.25rem] bg-white p-5 shadow-card dark:bg-gray-800" style="max-block-size:min(90dvh,42rem)">
                <div class="mb-5 flex items-start justify-between gap-3 border-b border-gray-100 pb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Record Collection</h2>
                        <p class="mt-0.5 text-xs text-gray-400">{{ $partner->name }}</p>
                    </div>

                    <button type="button" wire:click="closeCollectModal()" class="rounded-xl p-2 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600" aria-label="Close dialog">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="collection-date" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Collection Date <span class="text-red-500">*</span></label>
                        <input id="collection-date" type="date" wire:model="collectionDate" max="{{ now()->toDateString() }}" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 outline-none transition focus:border-brand-400 focus:ring-2 focus:ring-brand-100 @error('collectionDate') border-red-300 ring-red-100 @enderror">
                        @error('collectionDate')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="collection-amount" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Total Amount (₱) <span class="text-red-500">*</span></label>
                        <input id="collection-amount" type="number" min="0.01" step="0.01" wire:model="collectionAmount" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-brand-400 focus:ring-2 focus:ring-brand-100 @error('collectionAmount') border-red-300 ring-red-100 @enderror" placeholder="e.g. 1500">
                        @error('collectionAmount')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rounded-xl bg-gray-50 p-4 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Share Rate</span>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $partner->share_rate }}%</span>
                        </div>
                        @php
                            $shareAmountPreview = (float) $collectionAmount * ((float) $partner->share_rate / 100);
                            $ownerAmountPreview = (float) $collectionAmount - $shareAmountPreview;
                        @endphp
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-gray-500">Partner Share</span>
                            <span class="font-semibold text-brand-700">₱{{ number_format($shareAmountPreview, 2) }}</span>
                        </div>
                        <div class="mt-2 flex items-center justify-between border-t border-gray-200 pt-2">
                            <span class="font-semibold text-gray-500">Owner Amount</span>
                            <span class="font-bold text-gray-900">₱{{ number_format($ownerAmountPreview, 2) }}</span>
                        </div>
                    </div>

                    <div>
                        <label for="collection-remarks" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Remarks</label>
                        <textarea id="collection-remarks" rows="2" wire:model="collectionRemarks" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-brand-400 focus:ring-2 focus:ring-brand-100 resize-none @error('collectionRemarks') border-red-300 ring-red-100 @enderror" placeholder="Optional notes…"></textarea>
                        @error('collectionRemarks')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-100 pt-4">
                    <button type="button" wire:click="closeCollectModal()" class="min-h-[44px] rounded-xl bg-gray-700 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-gray-800">Cancel</button>
                    <button type="button" wire:click="saveCollection()" class="min-h-[44px] rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand-700">Record Collection</button>
                </div>
            </div>
        </div>
    @endif

    @if ($deletingCollectionId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-card dark:bg-gray-800">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/20" aria-hidden="true">
                    <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 3h5a1 1 0 011 1v3h-7V4a1 1 0 011-1z"/></svg>
                </div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Delete collection?</h2>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">This will permanently remove this collection entry from this partner.</p>
                <div class="mt-5 flex justify-end gap-3">
                    <button type="button" wire:click="closeDeleteModal()" class="min-h-[44px] rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700">Cancel</button>
                    <button type="button" wire:click="confirmDelete()" class="min-h-[44px] rounded-xl bg-red-500 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-red-600">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
