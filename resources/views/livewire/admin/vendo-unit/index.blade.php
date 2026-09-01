<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Vendo Units</h1>
            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                {{ $totalUnits }} unit(s) in total
            </p>
        </div>

        <button type="button" wire:click="openCreateModal()"
            class="flex min-h-[44px] items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Unit
        </button>
    </div>

    <section aria-label="Vendo unit summary">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-900/20" aria-hidden="true">
                        <svg class="h-5 w-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 5h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Total</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $totalUnits }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-900/20" aria-hidden="true">
                        <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Ready</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ collect($units)->where('status', 'ready')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 dark:bg-violet-900/20" aria-hidden="true">
                        <svg class="h-5 w-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Assigned</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ collect($units)->where('status', 'assigned')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-900/20" aria-hidden="true">
                        <svg class="h-5 w-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L1.5 3l1.5-1.5L7.5 4.5v1.409l4.26 4.26m0 0L15.75 7.5" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Repair</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ collect($units)->where('status', 'repair')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-700" aria-hidden="true">
                        <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 3h5a1 1 0 011 1v3h-7V4a1 1 0 011-1z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Retired</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ collect($units)->where('status', 'retired')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($showCreateModal || $showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/55 p-4">
            <div class="w-full max-w-lg overflow-y-auto rounded-[1.25rem] bg-white p-0 shadow-card dark:bg-gray-800" style="max-block-size:min(90dvh,42rem)">
                <div class="sticky top-0 z-10 flex items-center justify-between rounded-t-[1.25rem] border-b border-gray-100 bg-white px-6 py-5 dark:border-gray-700 dark:bg-gray-800">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $showEditModal ? 'Edit Vendo Unit' : 'Add New Vendo Unit' }}</h2>
                        <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">{{ $showEditModal ? "Update this physical unit's details" : 'New units start out Ready and unassigned' }}</p>
                    </div>
                    <button type="button" wire:click="{{ $showEditModal ? 'closeEditModal' : 'closeCreateModal' }}()" class="rounded-xl p-2 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-700" aria-label="Close dialog">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4 px-6 py-5">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="vu-name" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Unit Name <span class="text-red-500">*</span></label>
                            <input id="vu-name" type="text" wire:model="unitName" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-brand-400 focus:ring-2 focus:ring-brand-100 @error('unitName') border-red-300 ring-red-100 @enderror" placeholder="e.g. VENDO-007" required>
                            @error('unitName')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="vu-key" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Key <span class="text-red-500">*</span></label>
                            <input id="vu-key" type="text" wire:model="unitKey" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-brand-400 focus:ring-2 focus:ring-brand-100 @error('unitKey') border-red-300 ring-red-100 @enderror" placeholder="e.g. VU-2K9F71" required>
                            @error('unitKey')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="vu-desc" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Description</label>
                        <textarea id="vu-desc" rows="2" wire:model="unitDescription" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-brand-400 focus:ring-2 focus:ring-brand-100 resize-none @error('unitDescription') border-red-300 ring-red-100 @enderror" placeholder="e.g. 6-slot WiFi vending unit, outdoor-rated"></textarea>
                    </div>

                    <div>
                        <label for="vu-condition" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Condition Notes</label>
                        <textarea id="vu-condition" rows="2" wire:model="unitConditionNotes" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-brand-400 focus:ring-2 focus:ring-brand-100 resize-none @error('unitConditionNotes') border-red-300 ring-red-100 @enderror" placeholder="e.g. Coin slot serviced Aug 2026"></textarea>
                    </div>

                    <p class="rounded-xl bg-gray-50 p-3 text-xs text-gray-400 dark:bg-gray-900/40 dark:text-gray-400">
                        This unit will be created with status <strong class="text-emerald-600">Ready</strong> and no assigned partner.
                    </p>
                </div>

                <div class="sticky bottom-0 flex items-center justify-end gap-3 rounded-b-[1.25rem] border-t border-gray-100 bg-white px-6 py-4 dark:border-gray-700 dark:bg-gray-800">
                    <button type="button" wire:click="{{ $showEditModal ? 'closeEditModal' : 'closeCreateModal' }}()" class="min-h-[44px] rounded-xl px-5 py-2.5 text-sm font-semibold text-gray-500 transition-colors hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <button type="button" wire:click="saveUnit()" class="min-h-[44px] rounded-xl bg-brand-600 px-6 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand-700 shadow-sm">
                        {{ $showEditModal ? 'Save Changes' : 'Add Vendo Unit' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($showViewModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/55 p-4">
            <div class="w-full max-w-lg overflow-y-auto rounded-[1.25rem] bg-white shadow-card dark:bg-gray-800" style="max-block-size:min(90dvh,42rem)">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5 dark:border-gray-700">
                    <div class="flex min-w-0 items-center gap-3">
                        <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-900/20 dark:text-brand-300">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 5h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h2 class="truncate text-lg font-bold text-gray-900 dark:text-white">{{ $unitName ?: '—' }}</h2>
                            <p class="truncate  text-xs text-gray-400">{{ $unitKey ?: 'No key assigned' }}</p>
                        </div>
                    </div>
                    <button type="button" wire:click="closeViewModal()" class="flex-shrink-0 rounded-xl p-2 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600" aria-label="Close dialog">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4 px-6 py-5">
                    <div class="flex items-center justify-between rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</span>
                        <span class="inline-flex items-center gap-2 rounded-full px-2.5 py-1 text-xs font-semibold
                            @if($unitStatus === 'ready') bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400
                            @elseif($unitStatus === 'assigned') bg-brand-50 text-brand-700 dark:bg-brand-900/20 dark:text-brand-400
                            @elseif($unitStatus === 'repair') bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-400
                            @else bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 @endif">
                            <span class="h-1.5 w-1.5 rounded-full
                                @if($unitStatus === 'ready') bg-emerald-500
                                @elseif($unitStatus === 'assigned') bg-brand-500
                                @elseif($unitStatus === 'repair') bg-amber-500
                                @else bg-gray-400 @endif"></span>
                            {{ ucfirst($unitStatus ?: 'Ready') }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">Assigned Partner</p>
                            <p class="font-medium text-gray-800 dark:text-gray-200">{{ $unitStatus === 'assigned' ? 'Partner assigned' : 'Unassigned' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">Area</p>
                            <p class="font-medium text-gray-800 dark:text-gray-200">—</p>
                        </div>
                    </div>

                    <div>
                        <p class="mb-1 text-[11px] font-semibold uppercase tracking-wide text-gray-400">Description</p>
                        <p class="text-sm text-gray-700 dark:text-gray-200">{{ $unitDescription ?: '—' }}</p>
                    </div>

                    <div>
                        <p class="mb-1 text-[11px] font-semibold uppercase tracking-wide text-gray-400">Condition Notes</p>
                        <p class="text-sm text-gray-700 dark:text-gray-200">{{ $unitConditionNotes ?: '—' }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-gray-100 px-6 py-4 dark:border-gray-700">
                    <button type="button" wire:click="closeViewModal()" class="min-h-[44px] rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand-700">
                        Close
                    </button>
                    <button type="button" wire:click="openEditModal({{ $viewingUnitId }})" class="min-h-[44px] rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 transition-colors hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                        Edit
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($showAssignModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/55 p-4">
            <div class="w-full max-w-md rounded-[1.25rem] bg-white shadow-card dark:bg-gray-800">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5 dark:border-gray-700">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Assign Vendo Unit</h2>
                        <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">Assigning unit to a partner</p>
                    </div>
                    <button type="button" wire:click="closeAssignModal()" class="rounded-xl p-2 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600" aria-label="Close dialog">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4 px-6 py-5">
                    <div>
                        <label for="assign-partner" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Partner <span class="text-red-500">*</span></label>
                        <select id="assign-partner" wire:model="assignPartnerId" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 outline-none transition focus:border-brand-400 focus:ring-2 focus:ring-brand-100 @error('assignPartnerId') border-red-300 ring-red-100 @enderror">
                            <option value="">Select a partner…</option>
                            @foreach ($availablePartners as $partner)
                                <option value="{{ $partner['id'] }}">{{ $partner['name'] }} — {{ $partner['area'] }}</option>
                            @endforeach
                        </select>
                        @if ($assignError)
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $assignError }}</p>
                        @endif
                        @if (empty($availablePartners))
                            <p class="mt-2 text-xs text-gray-400">No partners are currently available — every partner already has a Vendo unit assigned.</p>
                        @endif
                    </div>

                    <p class="rounded-xl bg-gray-50 p-3 text-xs text-gray-400 dark:bg-gray-900/40 dark:text-gray-400">
                        Only partners without an existing Vendo unit are shown. This unit's status will change to <strong class="text-brand-600">Assigned</strong>.
                    </p>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-gray-100 px-6 py-4 dark:border-gray-700">
                    <button type="button" wire:click="closeAssignModal()" class="min-h-[44px] rounded-xl px-5 py-2.5 text-sm font-semibold text-gray-500 transition-colors hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <button type="button" wire:click="confirmAssign()" class="min-h-[44px] rounded-xl bg-brand-600 px-6 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand-700 shadow-sm disabled:opacity-40 disabled:cursor-not-allowed" @disabled(empty($availablePartners))>
                        Assign Unit
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($deleteUnitId)
        <div class="fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/55 p-4">
            <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-card dark:bg-gray-800">
                <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/20" aria-hidden="true">
                    <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 3h5a1 1 0 011 1v3h-7V4a1 1 0 011-1z" />
                    </svg>
                </div>
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Delete vendo unit?</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This will permanently remove <strong class="text-gray-800 dark:text-gray-200">{{ $deleteUnit?->name ?? 'this unit' }}</strong> from your records.</p>
                @if ($deleteErrorMessage)
                    <p class="mt-3 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700 dark:border-red-900/40 dark:bg-red-900/10 dark:text-red-400">{{ $deleteErrorMessage }}</p>
                @endif
                <div class="mt-5 flex justify-end gap-3">
                    <button type="button" wire:click="closeDeleteModal()" class="min-h-[44px] rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <button type="button" wire:click="confirmDelete()" class="min-h-[44px] rounded-xl bg-red-500 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-red-600">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif

    <section aria-label="Vendo unit list" class="rounded-2xl bg-white shadow-card dark:bg-gray-800">
        <div class="flex flex-col gap-3 border-b border-gray-100 p-5 dark:border-gray-700 lg:flex-row lg:items-center">
            <div class="relative max-w-sm flex-1">
                <svg class="pointer-events-none absolute start-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                </svg>
                <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search unit, key, or partner…" class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2.5 ps-9 pe-4 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200" aria-label="Search vendo units">
            </div>
        </div>

        <div class="tbl-wrap" tabindex="0" role="region" aria-label="Vendo unit table">
            <table class="w-full text-sm" style="min-inline-size:64rem">
                <caption class="sr-only">Vendo unit list</caption>
                <thead>
                    <tr class="border-b border-gray-100 text-left dark:border-gray-700">
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Unit</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Key</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Status</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Assigned Partner</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Description</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Condition Notes</th>
                        <th scope="col" class="px-5 py-3 text-end text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse ($units as $unit)
                        <tr class="transition-colors hover:bg-gray-50/60 dark:hover:bg-gray-700/40">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-50 text-brand-600 dark:bg-brand-900/20 dark:text-brand-300" aria-hidden="true">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 5h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                        </svg>
                                    </div>
                                    <button type="button" wire:click="openViewModal({{ $unit['id'] }})" class="truncate text-left font-medium text-gray-800 hover:text-brand-600 dark:text-gray-200">
                                        {{ $unit['name'] }}
                                    </button>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-xs  text-gray-500 dark:text-gray-400">{{ $unit['key'] ?: '—' }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-semibold
                                    @if($unit['status'] === 'ready') bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400
                                    @elseif($unit['status'] === 'assigned') bg-brand-50 text-brand-600 dark:bg-brand-900/20 dark:text-brand-400
                                    @elseif($unit['status'] === 'repair') bg-amber-50 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400
                                    @else bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 @endif">
                                    {{ $unit['status_label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-gray-500 dark:text-gray-400">
                                @if ($unit['partner_name'])
                                    <span>{{ $unit['partner_name'] }}</span>
                                @else
                                    <span class="italic text-gray-400">Unassigned</span>
                                @endif
                            </td>
                            <td class="max-w-[16rem] truncate px-5 py-3 text-gray-500 dark:text-gray-400" title="{{ $unit['description'] }}">{{ $unit['description'] ?: '—' }}</td>
                            <td class="max-w-[14rem] truncate px-5 py-3 text-gray-500 dark:text-gray-400" title="{{ $unit['condition_notes'] }}">{{ $unit['condition_notes'] ?: '—' }}</td>
                            <td class="px-5 py-3 text-end">
                                <div class="flex items-center justify-end gap-1">
                                    <button type="button" wire:click="openViewModal({{ $unit['id'] }})" class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-violet-50 hover:text-violet-600" aria-label="View {{ $unit['name'] }}">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178zM15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                    <button type="button" wire:click="openEditModal({{ $unit['id'] }})" class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-brand-50 hover:text-brand-600" aria-label="Edit {{ $unit['name'] }}">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    @if ($unit['status'] === 'ready')
                                        <button type="button" wire:click="openAssignModal({{ $unit['id'] }})" class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-brand-50 hover:text-brand-600" aria-label="Assign {{ $unit['name'] }}">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </button>
                                    @elseif ($unit['status'] === 'assigned')
                                        <button type="button" wire:click="unassignUnit({{ $unit['id'] }})" class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600" aria-label="Unassign {{ $unit['name'] }}">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                        </button>
                                    @elseif ($unit['status'] === 'repair')
                                        <button type="button" wire:click="markReady({{ $unit['id'] }})" class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-emerald-50 hover:text-emerald-600" aria-label="Mark {{ $unit['name'] }} ready">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 3" />
                                            </svg>
                                        </button>
                                    @else
                                        <button type="button" class="rounded-lg p-2 text-gray-400 opacity-60" aria-label="Retired {{ $unit['name'] }}" disabled>
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 105.636 5.636a9 9 0 0012.728 12.728zM9 9l6 6m0-6l-6 6" />
                                            </svg>
                                        </button>
                                    @endif

                                    <button type="button" wire:click="@if($unit['status'] === 'ready') openAssignModal({{ $unit['id'] }}) @elseif($unit['status'] === 'assigned') markRepair({{ $unit['id'] }}) @else openDeleteModal({{ $unit['id'] }}) @endif" class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600" aria-label="More actions for {{ $unit['name'] }}">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01" />
                                        </svg>
                                    </button>

                                    <button type="button" wire:click="openDeleteModal({{ $unit['id'] }})" class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-red-50 hover:text-red-500" aria-label="Delete {{ $unit['name'] }}">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 3h5a1 1 0 011 1v3h-7V4a1 1 0 011-1z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center">
                                <div class="flex flex-col items-center justify-center gap-3 text-gray-400 dark:text-gray-500">
                                    <svg class="h-12 w-12 text-gray-300 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No vendo units match your filters.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($totalUnits > 0)
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-5 py-4 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400">Showing <strong>{{ $startItem }}</strong>–<strong>{{ $endItem }}</strong> of <strong>{{ $totalUnits }}</strong></p>
                <div class="flex items-center gap-1">
                    <button type="button" wire:click="previousPage" @disabled($currentPage <= 1) class="min-h-[36px] rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 transition-colors hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                        ← Prev
                    </button>

                    @for ($page = 1; $page <= $totalPages; $page++)
                        <button type="button" wire:click="goToPage({{ $page }})" class="h-9 w-9 rounded-lg text-xs font-semibold {{ $page === $currentPage ? 'bg-brand-600 text-white' : 'border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            {{ $page }}
                        </button>
                    @endfor

                    <button type="button" wire:click="nextPage" @disabled($currentPage >= $totalPages) class="min-h-[36px] rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 transition-colors hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                        Next →
                    </button>
                </div>
            </div>
        @endif
    </section>
</div>

