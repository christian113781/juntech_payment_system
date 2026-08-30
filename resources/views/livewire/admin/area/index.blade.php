<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Areas</h1>
            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                {{ $totalAreas }} area(s) in total
            </p>
        </div>

        <button type="button" wire:click="openCreateModal()"
            class="flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700 min-h-[44px]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Area
        </button>
    </div>

    <section aria-label="Area summary">
        <div class="grid grid-cols-2 gap-4">
            <div class="stat-card rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-900/20" aria-hidden="true">
                        <svg class="h-5 w-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7.5L12 3l8 4.5v9L12 21l-8-4.5v-9zm8 4.5l8-4.5M12 12v9M4 7.5l8 4.5" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Total</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $totalAreas }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-900/20" aria-hidden="true">
                        <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v20m9-9H3" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Active</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $totalAreas }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($showCreateModal || $showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="w-full max-w-lg rounded-[1.25rem] bg-white p-5 shadow-card dark:bg-gray-800">
                <div class="mb-5 flex items-start justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ $showEditModal ? 'Edit Area' : 'Create Area' }}
                        </h2>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                            {{ $showEditModal ? 'Update the area details below.' : 'Fill in the area details below.' }}
                        </p>
                    </div>

                    <button type="button" wire:click="{{ $showEditModal ? 'closeEditModal' : 'closeCreateModal' }}()"
                        class="rounded-xl p-2 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600"
                        aria-label="Close dialog">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="area-code" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Code <span class="text-red-500">*</span></label>
                        <input id="area-code" type="text" wire:model="areaCode" class="field @error('areaCode') error @enderror" placeholder="Enter area code">
                        @error('areaCode')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="area-name" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Name <span class="text-red-500">*</span></label>
                        <input id="area-name" type="text" wire:model="areaName" class="field @error('areaName') error @enderror" placeholder="Enter area name">
                        @error('areaName')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" wire:click="{{ $showEditModal ? 'closeEditModal' : 'closeCreateModal' }}()"
                        class="min-h-[44px] rounded-xl bg-gray-700 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-gray-800">
                        Cancel
                    </button>
                    <button type="button" wire:click="saveArea()" wire:loading.attr="disabled" wire:target="saveArea"
                        class="flex min-h-[44px] items-center justify-center gap-2 rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand-700 disabled:opacity-70">
                        <svg wire:loading wire:target="saveArea" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="saveArea">{{ $showEditModal ? 'Save Changes' : 'Create Area' }}</span>
                        <span wire:loading wire:target="saveArea">Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($deleteAreaId)
        <x-delete-modal
            name="area"
            :show="true"
            :item-name="$deleteArea?->name"
        />
    @endif

    <section aria-label="Area list" class="rounded-2xl bg-white shadow-card dark:bg-gray-800">
        <div class="flex flex-col gap-3 border-b border-gray-100 p-5 dark:border-gray-700 lg:flex-row lg:items-center">
            <div class="relative flex-1 max-w-sm">
                <svg class="pointer-events-none absolute start-3 top-1/2 w-4 h-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                </svg>
                <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search code or name…"
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2.5 ps-9 pe-4 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200"
                    aria-label="Search areas">
            </div>
        </div>

        <div class="tbl-wrap" tabindex="0" role="region" aria-label="Area table">
            <table class="w-full text-sm" style="min-inline-size:56rem">
                <caption class="sr-only">Area list</caption>
                <thead>
                    <tr class="border-b border-gray-100 text-left dark:border-gray-700">
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Code</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Name</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse ($areas as $area)
                        <tr class="transition-colors hover:bg-gray-50/60 dark:hover:bg-gray-700/40">
                            <td class="px-5 py-3 font-medium text-gray-700 dark:text-gray-200">{{ $area['code'] }}</td>
                            <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ $area['name'] }}</td>
                            <td class="px-5 py-3 text-end">
                                <div class="flex items-center justify-end gap-1">
                                    <button type="button" title="Edit" wire:click="openEditModal({{ $area['id'] }})"
                                        class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-brand-50 hover:text-brand-600"
                                        aria-label="Edit {{ $area['name'] }}">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button type="button" title="Delete" wire:click="openDeleteModal({{ $area['id'] }})"
                                        class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-red-50 hover:text-red-500"
                                        aria-label="Delete {{ $area['name'] }}">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 3h5a1 1 0 011 1v3h-7V4a1 1 0 011-1z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-10 text-center">
                                <div class="flex flex-col items-center justify-center gap-3 text-gray-400 dark:text-gray-500">
                                    <svg class="h-12 w-12 text-gray-300 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No areas match your filters.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($totalAreas > 0)
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-5 py-4 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400">Showing
                    <strong>{{ $startItem }}</strong>–<strong>{{ $endItem }}</strong> of <strong>{{ $totalAreas }}</strong>
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
