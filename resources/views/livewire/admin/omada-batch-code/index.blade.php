 <div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <a href="{{ route('omada.index') }}" class="inline-flex items-center gap-1.5 mb-3 text-sm font-semibold text-gray-500 hover:text-brand-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Partners
            </a>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Batch Codes</h1>
            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                {{ $batches->count() }} of {{ $totalBatches }} batches for <strong>{{ $partner->name }}</strong>
            </p>
        </div>

        <button type="button" wire:click="openCreateModal()"
            class="flex min-h-[44px] items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Batch
        </button>
    </div>

    <section aria-label="Batch summary">
        <div class="grid grid-cols-2 gap-4 xl:grid-cols-4">
            <div class="stat-card rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-900/20" aria-hidden="true">
                        <svg class="h-5 w-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Total Batches</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $totalBatches }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-900/20" aria-hidden="true">
                        <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Total Codes</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $batches->sum(fn($b) => $b->requested_qty + $b->bonus_qty) }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-900/20" aria-hidden="true">
                        <svg class="h-5 w-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Total Value</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">₱{{ number_format($batches->sum(fn($b) => $b->requested_qty * $b->price_per_voucher), 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-900/20" aria-hidden="true">
                        <svg class="h-5 w-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Pending</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $batches->where('status', 'pending')->count() }}</p>
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
                            {{ $showEditModal ? 'Edit Batch' : 'Add New Batch' }}
                        </h2>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                            {{ $showEditModal ? 'Update the batch details below.' : 'Fill in the details to generate a voucher batch.' }}
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
                    <div class="rounded-xl border border-brand-100 bg-brand-50/60 p-3 dark:border-brand-500/20 dark:bg-brand-500/5">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.12em] text-brand-600 dark:text-brand-300">Partner</p>
                        <div class="mt-1 flex items-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-600 text-[10px] font-bold text-white">
                                {{ strtoupper(substr($partner->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $partner->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $partner->area->name ?? 'No area assigned' }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="batch-type" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Voucher Type <span class="text-red-500">*</span></label>
                        <select id="batch-type" wire:model="type" class="field @error('type') error @enderror">
                            <option value="SALE">SALE</option>
                            <option value="FREE">FREE</option>
                            <option value="SALE + FREE">SALE + FREE</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="batch-qty" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Requested QTY <span class="text-red-500">*</span></label>
                            <input id="batch-qty" type="number" min="1" step="1" wire:model.number="requestedQty" class="field @error('requestedQty') error @enderror" placeholder="e.g. 100">
                            @error('requestedQty')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="batch-bonus" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Bonus QTY <span class="text-red-500">*</span></label>
                            <input id="batch-bonus" type="number" min="0" step="1" wire:model.number="bonusQty" class="field @error('bonusQty') error @enderror" placeholder="e.g. 5">
                            @error('bonusQty')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="batch-price" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Price Per Code <span class="text-red-500">*</span></label>
                        <input id="batch-price" type="number" step="0.01" min="0" wire:model.number="pricePerVoucher" class="field @error('pricePerVoucher') error @enderror" placeholder="e.g. 10">
                        @error('pricePerVoucher')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="batch-remarks" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Remarks</label>
                        <textarea id="batch-remarks" rows="2" wire:model="remarks" class="field @error('remarks') error @enderror" placeholder="e.g. Promo giveaway"></textarea>
                        @error('remarks')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" wire:click="{{ $showEditModal ? 'closeEditModal' : 'closeCreateModal' }}()"
                        class="min-h-[44px] rounded-xl bg-gray-700 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-gray-800">
                        Cancel
                    </button>
                    <button type="button" wire:click="saveBatch()" wire:loading.attr="disabled" wire:target="saveBatch"
                        class="flex min-h-[44px] items-center justify-center gap-2 rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand-700 disabled:opacity-70">
                        <svg wire:loading wire:target="saveBatch" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="saveBatch">{{ $showEditModal ? 'Save Changes' : 'Add Batch' }}</span>
                        <span wire:loading wire:target="saveBatch">Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($deleteBatchId)
        <x-delete-modal
            name="batch"
            :show="true"
            :item-name="$deleteBatch?->batch_code"
        />
    @endif

    @if ($statusBatchId && $statusBatch)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="w-full max-w-lg rounded-[1.25rem] bg-white p-5 shadow-card dark:bg-gray-800">
                <div class="mb-5 flex items-start justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Update Batch Status</h2>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Choose the next status for this batch.</p>
                    </div>

                    <button type="button" wire:click="closeStatusModal()"
                        class="rounded-xl p-2 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600"
                        aria-label="Close dialog">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Batch Code</label>
                        <input type="text" value="{{ $statusBatch->batch_code }}" readonly class="field cursor-not-allowed bg-gray-100 dark:bg-gray-900/50">
                    </div>

                    <div>
                        <label for="status-value" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Status <span class="text-red-500">*</span></label>
                        <select id="status-value" wire:model="statusValue" class="field @error('statusValue') error @enderror">
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        @error('statusValue')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" wire:click="closeStatusModal()"
                        class="min-h-[44px] rounded-xl bg-gray-700 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-gray-800">
                        Cancel
                    </button>
                    <button type="button" wire:click="saveStatus()" wire:loading.attr="disabled" wire:target="saveStatus"
                        class="flex min-h-[44px] items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-emerald-700 disabled:opacity-70">
                        <svg wire:loading wire:target="saveStatus" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="saveStatus">Save Status</span>
                        <span wire:loading wire:target="saveStatus">Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($paymentBatchId && $paymentBatch)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="w-full max-w-lg rounded-[1.25rem] bg-white p-5 shadow-card dark:bg-gray-800">
                <div class="mb-5 flex items-start justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Pay Batch</h2>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Record the collection for this delivered batch.</p>
                    </div>

                    <button type="button" wire:click="closePaymentModal()"
                        class="rounded-xl p-2 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600"
                        aria-label="Close dialog">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Batch Code</label>
                        <input type="text" value="{{ $paymentBatch->batch_code }}" readonly class="field cursor-not-allowed bg-gray-100 dark:bg-gray-900/50">
                    </div>

                    <div>
                        <label for="payment-total-amount" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Total Amount <span class="text-red-500">*</span></label>
                        <input id="payment-total-amount" type="number" step="0.01" min="0" wire:model="paymentTotalAmount" class="field @error('paymentTotalAmount') error @enderror">
                        @error('paymentTotalAmount')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment-collection-date" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Collection Date <span class="text-red-500">*</span></label>
                        <input id="payment-collection-date" type="date" wire:model="paymentCollectionDate" class="field @error('paymentCollectionDate') error @enderror">
                        @error('paymentCollectionDate')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment-remarks" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Remarks</label>
                        <textarea id="payment-remarks" rows="3" wire:model="paymentRemarks" class="field @error('paymentRemarks') error @enderror" placeholder="Optional note for this collection"></textarea>
                        @error('paymentRemarks')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" wire:click="closePaymentModal()"
                        class="min-h-[44px] rounded-xl bg-gray-700 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-gray-800">
                        Cancel
                    </button>
                    <button type="button" wire:click="savePayment()" wire:loading.attr="disabled" wire:target="savePayment"
                        class="flex min-h-[44px] items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-emerald-700 disabled:opacity-70">
                        <svg wire:loading wire:target="savePayment" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="savePayment">Pay Batch</span>
                        <span wire:loading wire:target="savePayment">Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <section aria-label="Batch list" class="rounded-2xl bg-white shadow-card dark:bg-gray-800">
        <div class="flex flex-col gap-3 border-b border-gray-100 p-5 dark:border-gray-700 lg:flex-row lg:items-center">
            <div class="relative flex-1 max-w-sm">
                <svg class="pointer-events-none absolute start-3 top-1/2 w-4 h-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                </svg>
                <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search batch code…"
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2.5 ps-9 pe-4 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200"
                    aria-label="Search batches">
            </div>

            <select wire:model.live="typeFilter" class="field !w-auto min-w-[180px] py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200" aria-label="Filter by type">
                <option value="">All types</option>
                <option value="SALE">SALE</option>
                <option value="FREE">FREE</option>
                <option value="SALE + FREE">SALE + FREE</option>
            </select>

            <select wire:model.live="statusFilter" class="field !w-auto min-w-[180px] py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200" aria-label="Filter by status">
                <option value="">All statuses</option>
                <option value="pending">Pending</option>
                <option value="delivered">Delivered</option>
                <option value="paid">Paid</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <div class="tbl-wrap" tabindex="0" role="region" aria-label="Batch table">
            <table class="w-full text-sm" style="min-inline-size:62rem">
                <caption class="sr-only">Batch list</caption>
                <thead>
                    <tr class="border-b border-gray-100 text-left dark:border-gray-700">
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Batch Code</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Type</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Requested</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Bonus</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Price/Code</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Generated</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Status</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse ($batches as $batch)
                        <tr class="transition-colors hover:bg-gray-50/60 dark:hover:bg-gray-700/40">
                            <td class="px-5 py-3 font-medium text-gray-800 dark:text-gray-100 whitespace-nowrap">{{ $batch->batch_code }}</td>
                            <td class="px-5 py-3">
                                @php
                                    $typeStyles = [
                                        'SALE' => ['bg' => 'bg-brand-50', 'text' => 'text-brand-600', 'dot' => 'bg-brand-500'],
                                        'FREE' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'dot' => 'bg-emerald-500'],
                                        'SALE + FREE' => ['bg' => 'bg-violet-50', 'text' => 'text-violet-600', 'dot' => 'bg-violet-400'],
                                    ];
                                    $style = $typeStyles[$batch->type] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'dot' => 'bg-gray-400'];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2 py-1 text-xs font-medium {{ $style['bg'] }} {{ $style['text'] }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $style['dot'] }}" aria-hidden="true"></span>
                                    {{ $batch->type }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-end text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $batch->requested_qty }}</td>
                            <td class="px-5 py-3 text-end text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $batch->bonus_qty }}</td>
                            <td class="px-5 py-3 text-end text-gray-700 dark:text-gray-300 whitespace-nowrap">₱{{ number_format($batch->price_per_voucher, 2) }}</td>
                            <td class="px-5 py-3 text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $batch->generated_date->format('M d, Y') }}</td>
                            <td class="px-5 py-3">
                                @php
                                    $statusStyles = [
                                        'pending' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'dot' => 'bg-amber-400'],
                                        'delivered' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'dot' => 'bg-blue-400'],
                                        'paid' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'dot' => 'bg-emerald-500'],
                                        'cancelled' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'dot' => 'bg-red-400'],
                                    ];
                                    $status = $statusStyles[$batch->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'dot' => 'bg-gray-400'];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2 py-1 text-xs font-medium {{ $status['bg'] }} {{ $status['text'] }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $status['dot'] }}" aria-hidden="true"></span>
                                    {{ ucfirst($batch->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-end">
                                <div class="flex items-center justify-end gap-1">
                                    @if ($batch->status === 'delivered')
                                        <button type="button" title="Pay" wire:click="openPaymentModal({{ $batch->id }})"
                                            class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-emerald-50 hover:text-emerald-600"
                                            aria-label="Pay {{ $batch->batch_code }}">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    @endif

                                    @if ($batch->status === 'pending')
                                        <button type="button" title="Status" wire:click="openStatusModal({{ $batch->id }})"
                                            class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-amber-50 hover:text-amber-600"
                                            aria-label="Status for {{ $batch->batch_code }}">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0ZM17.25 18.75a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0ZM8.25 18.75h6m-9 0H3.75a1.5 1.5 0 0 1-1.5-1.5V6.75A1.5 1.5 0 0 1 3.75 5.25h9a1.5 1.5 0 0 1 1.5 1.5v12m0-3h4.5a1.5 1.5 0 0 0 1.5-1.5v-3.879a1.5 1.5 0 0 0-.44-1.06l-2.621-2.621a1.5 1.5 0 0 0-1.06-.44h-1.879" />
                                            </svg>
                                        </button>
                                    @endif

                                    <button type="button" title="Edit" wire:click="openEditModal({{ $batch->id }})"
                                        class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-brand-50 hover:text-brand-600"
                                        aria-label="Edit {{ $batch->batch_code }}">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button type="button" title="Delete" wire:click="openDeleteModal({{ $batch->id }})"
                                        class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-red-50 hover:text-red-500"
                                        aria-label="Delete {{ $batch->batch_code }}">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 3h5a1 1 0 011 1v3h-7V4a1 1 0 011-1z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-10 text-center">
                                <div class="flex flex-col items-center justify-center gap-3 text-gray-400 dark:text-gray-500">
                                    <svg class="h-12 w-12 text-gray-300 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No batches match your filters.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($batches->count() > 0)
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-5 py-4 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400">Showing
                    <strong>{{ $batches->firstItem() ?? 0 }}</strong>–<strong>{{ $batches->lastItem() ?? 0 }}</strong> of <strong>{{ $totalBatches }}</strong>
                </p>
                <div class="flex items-center gap-1">
                    {{ $batches->links('pagination::tailwind') }}
                </div>
            </div>
        @endif
    </section>

    <section aria-label="Collection history" class="rounded-2xl bg-white shadow-card dark:bg-gray-800">
        <div class="flex flex-col gap-1 border-b border-gray-100 p-5 dark:border-gray-700 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Payment Collections</h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Recent collections for this partner</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300">Latest 10 per page</span>
        </div>

        <div class="tbl-wrap" tabindex="0" role="region" aria-label="Collection table">
            <table class="w-full text-sm" style="min-inline-size:56rem">
                <caption class="sr-only">Collection list</caption>
                <thead>
                    <tr class="border-b border-gray-100 text-left dark:border-gray-700">
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Batch Code</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Collection Date</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Total Amount</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse ($collections as $collection)
                        <tr class="transition-colors hover:bg-gray-50/60 dark:hover:bg-gray-700/40">
                            <td class="px-5 py-3 font-medium text-gray-800 dark:text-gray-100 whitespace-nowrap">{{ $collection->voucherBatch?->batch_code ?? 'N/A' }}</td>
                            <td class="px-5 py-3 text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $collection->collection_date->format('M d, Y') }}</td>
                            <td class="px-5 py-3 text-end text-gray-700 dark:text-gray-300 whitespace-nowrap font-semibold">₱{{ number_format($collection->total_amount, 2) }}</td>
                            <td class="px-5 py-3 text-gray-600 dark:text-gray-400 max-w-xs truncate">{{ $collection->remarks ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-10 text-center">
                                <div class="flex flex-col items-center justify-center gap-3 text-gray-400 dark:text-gray-500">
                                    <svg class="h-12 w-12 text-gray-300 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No collection records yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($collections->count() > 0)
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-5 py-4 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400">Showing <strong>{{ $collections->firstItem() ?? 0 }}</strong>–<strong>{{ $collections->lastItem() ?? 0 }}</strong> of <strong>{{ $collections->total() }}</strong></p>
                <div class="flex items-center gap-1">
                    {{ $collections->links('pagination::tailwind') }}
                </div>
            </div>
        @endif

        @if ($collectionTotal > 0)
            <div class="border-t border-gray-100 bg-gray-50/50 px-5 py-4 dark:border-gray-700 dark:bg-gray-900/30">
                <div class="flex items-center justify-between">
                    <p class="text-xs text-gray-600 dark:text-gray-400">Total Collections</p>
                    <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">₱{{ number_format($collectionTotal, 2) }}</p>
                </div>
            </div>
        @endif
    </section>
</div>
