<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Vendo Partners</h1>
            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                {{ $totalPartners }} partner(s) in total
            </p>
        </div>

        <button type="button" wire:click="openCreateModal()"
            class="flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700 min-h-[44px]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Partner
        </button>
    </div>

    <section aria-label="Vendo partner summary">
        <div class="grid grid-cols-2 gap-4 xl:grid-cols-4">
            <div class="stat-card rounded-2xl bg-white p-4 text-left shadow-card transition-shadow dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-900/20" aria-hidden="true">
                        <svg class="h-5 w-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Total</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $totalPartners }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card rounded-2xl bg-white p-4 text-left shadow-card transition-shadow dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/20" aria-hidden="true">
                        <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 2a10 10 0 110 20A10 10 0 0112 2z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Overdue</p>
                        <p class="text-lg font-bold text-red-500">{{ collect($partners)->filter(fn ($partner) => isset($partner['days_left']) && $partner['days_left'] < 0)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card rounded-2xl bg-white p-4 text-left shadow-card transition-shadow dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-900/20" aria-hidden="true">
                        <svg class="h-5 w-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Due Soon</p>
                        <p class="text-lg font-bold text-amber-500">{{ collect($partners)->filter(fn ($partner) => isset($partner['days_left']) && $partner['days_left'] >= 0 && $partner['days_left'] <= 3)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card rounded-2xl bg-white p-4 text-left shadow-card transition-shadow dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-900/20" aria-hidden="true">
                        <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">On Track</p>
                        <p class="text-lg font-bold text-emerald-600">{{ collect($partners)->filter(fn ($partner) => isset($partner['days_left']) && $partner['days_left'] > 3)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($showCreateModal || $showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="w-full max-w-lg overflow-y-auto rounded-[1.25rem] bg-white shadow-card dark:bg-gray-800" style="max-height: min(90dvh, 42rem);">
                <div class="sticky top-0 z-10 flex items-start justify-between gap-3 rounded-t-[1.25rem] border-b border-gray-100 bg-white px-6 py-5">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $showEditModal ? 'Edit Partner' : 'Add New Partner' }}
                        </h2>
                        @if (! $showEditModal)
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">New partner will start as Unassigned</p>
                        @else
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Update the partner details below.</p>
                        @endif
                    </div>

                    <button type="button" wire:click="{{ $showEditModal ? 'closeEditModal' : 'closeCreateModal' }}()"
                        class="rounded-xl p-2 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600"
                        aria-label="Close dialog">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4 px-6 py-5">
                    <div>
                        <label for="partner-name" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Partner Name <span class="text-red-500">*</span></label>
                        <input id="partner-name" type="text" wire:model="partnerName" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 @error('partnerName') border-red-300 ring-red-100 @enderror" placeholder="e.g. Juan Store">
                        @error('partnerName')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="partner-area" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Area <span class="text-red-500">*</span></label>
                            <select id="partner-area" wire:model="partnerAreaId" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 @error('partnerAreaId') border-red-300 ring-red-100 @enderror">
                                <option value="">Select area...</option>
                                @foreach ($areaOptions as $area)
                                    <option value="{{ $area['id'] }}">{{ $area['name'] }}</option>
                                @endforeach
                            </select>
                            @error('partnerAreaId')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="partner-contact" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Contact Number</label>
                            <input id="partner-contact" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="20" wire:model="partnerContactNumber" oninput="this.value = this.value.replace(/\D/g, '')" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 @error('partnerContactNumber') border-red-300 ring-red-100 @enderror" placeholder="e.g. 09171234567">
                            @error('partnerContactNumber')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="partner-address" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Address</label>
                        <textarea id="partner-address" rows="2" wire:model="partnerAddress" class="w-full resize-none rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 @error('partnerAddress') border-red-300 ring-red-100 @enderror" placeholder="e.g. 123 Rizal St., Brgy. 1"></textarea>
                        @error('partnerAddress')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="partner-unit" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Assign Vendo Unit</label>
                        <select id="partner-unit" wire:model="partnerUnitId" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 @error('partnerUnitId') border-red-300 ring-red-100 @enderror">
                            <option value="">None (Unassigned)</option>
                            @foreach ($assignableUnits as $unit)
                                <option value="{{ $unit['id'] }}">{{ $unit['name'] }}{{ !empty($unit['key']) ? ' — ' . $unit['key'] : '' }}</option>
                            @endforeach
                        </select>
                       <p class="mt-1.5 text-xs text-gray-400">Only Ready units are shown. Assigning a unit will set it to <strong class="text-brand-600">Assigned</strong>.</p>
                        @error('partnerUnitId')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="partner-status" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Status <span class="text-red-500">*</span></label>
                            <select id="partner-status" wire:model="partnerStatus" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 @error('partnerStatus') border-red-300 ring-red-100 @enderror">
                                <option value="active">Active</option>
                                <option value="unassigned">Unassigned</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            @error('partnerStatus')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="partner-share-rate" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Share Rate (%) <span class="text-red-500">*</span></label>
                            <input id="partner-share-rate" type="number" min="0" max="100" step="0.01" wire:model="partnerShareRate" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 @error('partnerShareRate') border-red-300 ring-red-100 @enderror" placeholder="30">
                            @error('partnerShareRate')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 rounded-b-[1.25rem] border-t border-gray-100 bg-white px-6 py-4">
                    <button type="button" wire:click="{{ $showEditModal ? 'closeEditModal' : 'closeCreateModal' }}()"
                        class="min-h-[44px] rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-600 transition-colors hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="button" wire:click="savePartner()" wire:loading.attr="disabled" wire:target="savePartner"
                        class="flex min-h-[44px] items-center justify-center gap-2 rounded-xl bg-brand-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700 disabled:opacity-70">
                        <svg wire:loading wire:target="savePartner" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="savePartner">{{ $showEditModal ? 'Save Changes' : 'Add Partner' }}</span>
                        <span wire:loading wire:target="savePartner">Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($showCollectModal)
        @php
            $collectingShareRate = (float) ($collectingPartner?->share_rate ?? 0);
            $collectionTotal = max(0, (float) $collectionAmount);
            $partnerShare = round($collectionTotal * ($collectingShareRate / 100), 2);
            $ownerAmount = round($collectionTotal - $partnerShare, 2);
        @endphp

        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/55 p-4">
            <div class="w-full max-w-md overflow-hidden rounded-[1.25rem] bg-white shadow-card">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">
                    <div>
                        <h2 class="text-[1.05rem] font-bold text-gray-900">Record Collection</h2>
                        <p class="mt-1 text-sm text-gray-500">{{ $collectingPartner?->name ?? 'Partner' }}</p>
                    </div>

                    <button type="button" wire:click="closeCollectModal()"
                        class="rounded-xl p-2 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600"
                        aria-label="Close dialog">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4 px-6 py-5">
                    <div>
                        <label for="collection-date" class="mb-1.5 block text-xs font-semibold text-gray-600">Collection Date <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input id="collection-date" type="date" wire:model="collectionDate" max="{{ now()->toDateString() }}" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 pr-10 text-sm text-gray-700 outline-none transition focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 @error('collectionDate') border-red-300 ring-red-100 @enderror">
                            <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @error('collectionDate')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="collection-amount" class="mb-1.5 block text-xs font-semibold text-gray-600">Total Amount (₱) <span class="text-red-500">*</span></label>
                        <input id="collection-amount" type="number" min="0.01" step="0.01" wire:model.live="collectionAmount" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 @error('collectionAmount') border-red-300 ring-red-100 @enderror" placeholder="e.g. 1500">
                        @error('collectionAmount')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2 rounded-xl border border-gray-200 bg-gray-50 p-3 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Share Rate</span>
                            <span class="font-semibold text-gray-700">{{ number_format($collectingShareRate, 0) }}%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Partner Share</span>
                            <span class="font-semibold text-brand-700">₱{{ number_format($partnerShare, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 pt-2">
                            <span class="font-semibold text-gray-600">Owner Amount</span>
                            <span class="font-bold text-gray-900">₱{{ number_format($ownerAmount, 2) }}</span>
                        </div>
                    </div>

                    <div>
                        <label for="collection-remarks" class="mb-1.5 block text-xs font-semibold text-gray-600">Remarks</label>
                        <textarea id="collection-remarks" rows="2" wire:model="collectionRemarks" class="w-full resize-none rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100" placeholder="Optional notes..."></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-gray-100 bg-white px-6 py-4">
                    <button type="button" wire:click="closeCollectModal()"
                        class="min-h-[44px] rounded-xl px-5 py-2.5 text-sm font-semibold text-gray-500 transition-colors hover:bg-gray-100">
                        Cancel
                    </button>
                    <button type="button" wire:click="saveCollection()"
                        class="min-h-[44px] rounded-xl bg-brand-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700">
                        Record Collection
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($deletePartnerId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-card dark:bg-gray-800">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/20" aria-hidden="true">
                    <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 3h5a1 1 0 011 1v3h-7V4a1 1 0 011-1z" />
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Delete partner?</h2>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    This will permanently remove <strong class="text-gray-800 dark:text-gray-200">{{ $deletePartner?->name ?? 'this partner' }}</strong> from the Vendo partners list.
                </p>
                @if ($deleteErrorMessage)
                    <p class="mt-3 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700 dark:border-red-900/40 dark:bg-red-900/10 dark:text-red-400">
                        {{ $deleteErrorMessage }}
                    </p>
                @endif
                <div class="mt-6 flex justify-end gap-3">
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

    <section aria-label="Vendo partner list" class="rounded-2xl bg-white shadow-card dark:bg-gray-800">
        <div class="flex flex-col gap-3 border-b border-gray-100 p-5 dark:border-gray-700 lg:flex-row lg:items-center">
            <div class="relative max-w-sm flex-1">
                <svg class="pointer-events-none absolute start-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                </svg>
                <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search partner, area, or unit…"
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2.5 ps-9 pe-4 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200"
                    aria-label="Search partners">
            </div>

            <div class="flex flex-wrap items-center gap-1.5" role="group" aria-label="Filter by collection status">
                @php
                    $collectionFilters = [
                        ['value' => 'all', 'label' => 'All'],
                        ['value' => 'overdue', 'label' => 'Overdue'],
                        ['value' => 'due_today', 'label' => 'Due Today'],
                        ['value' => 'due_soon', 'label' => 'Ready for Collection'],
                        ['value' => 'active', 'label' => 'Active'],
                        ['value' => 'unassigned', 'label' => 'Unassigned'],
                    ];
                @endphp
                @foreach ($collectionFilters as $filter)
                    <button type="button" wire:click="$set('collectionFilter', '{{ $filter['value'] }}')"
                        class="min-h-[36px] rounded-lg px-3 py-1.5 text-xs font-semibold transition-colors {{ $collectionFilter === $filter['value'] ? 'bg-brand-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                        aria-pressed="{{ $collectionFilter === $filter['value'] ? 'true' : 'false' }}">
                        {{ $filter['label'] }}
                    </button>
                @endforeach
            </div>

            <select wire:model.live="sortBy" class="!w-auto min-h-[2.75rem] rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700 outline-none transition focus:border-brand-400 focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 lg:ms-auto" aria-label="Sort partners">
                <option value="urgency">Sort: Most Urgent</option>
                <option value="name">Sort: Name (A–Z)</option>
                <option value="area">Sort: Area</option>
            </select>
        </div>

        <div class="tbl-wrap" tabindex="0" role="region" aria-label="Vendo partner table">
            <table class="w-full text-sm" style="min-inline-size:72rem">
                <caption class="sr-only">Vendo partner list</caption>
                <thead>
                    <tr class="border-b border-gray-100 text-left dark:border-gray-700">
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Partner</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Area</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Contact</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Assigned Unit</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Vendo Status</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Share Rate</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Last Collection</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Collection Status</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse ($partners as $partner)
                        @php
                            $daysLeft = $partner['days_left'];
                            $isOverdue = $daysLeft !== null && $daysLeft < 0;
                            $isDueToday = $daysLeft === 0;
                            $isDueSoon = $daysLeft !== null && $daysLeft >= 0 && $daysLeft <= 7;
                            $isOnTrack = $daysLeft !== null && $daysLeft > 7;
                            $statusColor = match (($partner['status'] ?? 'active')) {
                                'active' => 'bg-emerald-50 text-emerald-700',
                                'inactive' => 'bg-gray-100 text-gray-600',
                                'unassigned' => 'bg-amber-50 text-amber-700',
                                default => 'bg-gray-100 text-gray-600',
                            };
                            $statusIconColor = match (($partner['status'] ?? 'active')) {
                                'active' => 'bg-emerald-100 text-emerald-700',
                                'inactive' => 'bg-gray-200 text-gray-600',
                                'unassigned' => 'bg-amber-100 text-amber-700',
                                default => 'bg-gray-200 text-gray-600',
                            };
                            $collectionStatusColor = $isOverdue ? 'bg-red-100 text-red-700' : ($isDueToday ? 'bg-orange-100 text-orange-700' : ($isDueSoon ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700'));
                            $collectionStatusLabel = $daysLeft === null ? 'No collection recorded' : ($isOverdue ? abs($daysLeft) . ' ' . (abs($daysLeft) === 1 ? 'day' : 'days') . ' overdue' : ($isDueToday ? 'Due today' : ($daysLeft === 1 ? '1 day left' : $daysLeft . ' days left')) );
                            $unitStatus = $partner['unit_name'] ? 'Assigned' : 'Unassigned';
                            $unitStatusColor = $partner['unit_name'] ? 'bg-brand-50 text-brand-700' : 'bg-gray-100 text-gray-600';
                        @endphp
                        <tr class="transition-colors hover:bg-gray-50/60 dark:hover:bg-gray-700/40 {{ $isOverdue ? 'bg-red-50/40 hover:bg-red-50/60' : '' }}">
                            <td class="px-5 py-3">
                                <div class="flex min-w-[10rem] items-center gap-2.5">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-xs font-bold {{ $statusIconColor }}">
                                        {{ strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $partner['name']) ?? '', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold text-gray-800 dark:text-gray-200">{{ $partner['name'] }}</p>
                                        <span class="inline-flex rounded-full px-1.5 py-0.5 text-[10px] font-medium {{ $statusColor }}">{{ ucfirst($partner['status']) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap text-gray-600 dark:text-gray-300">{{ $partner['area_name'] ?: '—' }}</td>
                            <td class="px-5 py-3 whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $partner['contact_number'] ?: '—' }}</td>
                            <td class="px-5 py-3">
                                @if ($partner['unit_name'])
                                    <span class="font-medium  text-xs text-gray-700 dark:text-gray-200">{{ $partner['unit_name'] }}</span>
                                @else
                                    <span class="text-xs italic text-gray-400">Unassigned</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                @if ($partner['unit_name'])
                                    <span class="inline-flex rounded-full bg-brand-50 px-2 py-1 text-[10px] font-semibold text-brand-700">Assigned</span>
                                @else
                                    <span class="text-xs italic text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-end font-semibold text-gray-700 dark:text-gray-200">{{ number_format((float) $partner['share_rate'], 0) }}%</td>
                            <td class="px-5 py-3 whitespace-nowrap text-gray-600 dark:text-gray-300">{{ $partner['last_collected_at'] ?: '—' }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex rounded-full px-2 py-1 text-[10px] font-semibold {{ $collectionStatusColor }}">{{ $collectionStatusLabel }}</span>
                            </td>
                            <td class="px-5 py-3 text-end">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('vendo-collections.index', ['partner' => $partner['id']]) }}"
                                        class="inline-flex rounded-lg p-2 text-gray-400 transition-colors hover:bg-brand-50 hover:text-brand-600"
                                        aria-label="View collections for {{ $partner['name'] }}">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                    </a>
                                    <button type="button" wire:click="openEditModal({{ $partner['id'] }})"
                                        class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-brand-50 hover:text-brand-600"
                                        aria-label="Edit {{ $partner['name'] }}">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    <div class="relative" x-data="{ open: false }" @keydown.escape="open = false" @click.outside="open = false">
                                        <button type="button" @click.stop="open = !open"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600"
                                            aria-haspopup="true" :aria-expanded="open.toString()"
                                            :aria-label="'More actions for {{ $partner['name'] }}'">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01" /></svg>
                                        </button>
                                        <div x-show="open" x-transition @click.outside="open = false" class="absolute right-0 top-full z-30 mt-2 w-48 overflow-hidden rounded-xl border border-gray-200 bg-white p-1 text-left shadow-lg ring-1 ring-black/5" style="display:none">
                                            <button type="button" wire:click="openCollectModal({{ $partner['id'] }})" @click="open = false" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-50">
                                                <svg class="h-4 w-4 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                Record Collection
                                            </button>
                                            <div class="my-1 border-t border-gray-100"></div>
                                            <button type="button" wire:click="openDeleteModal({{ $partner['id'] }})" @click="open = false" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-red-500 transition-colors hover:bg-red-50">
                                                <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 3h5a1 1 0 011 1v3h-7V4a1 1 0 011-1z" /></svg>
                                                Delete Partner
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-10 text-center">
                                <div class="flex flex-col items-center justify-center gap-3 text-gray-400 dark:text-gray-500">
                                    <svg class="h-12 w-12 text-gray-300 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No partners match your search or filter.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($totalPartners > 0)
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-5 py-4 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400">Showing
                    <strong>{{ $startItem }}</strong>–<strong>{{ $endItem }}</strong> of <strong>{{ $totalPartners }}</strong>
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
