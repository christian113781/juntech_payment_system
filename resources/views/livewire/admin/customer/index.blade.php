<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Customers</h1>
            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Manage customer accounts and billing cycles.</p>
        </div>

        <button type="button" wire:click="openCreateModal()"
            class="flex min-h-[44px] items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Customer
        </button>
    </div>

    <section aria-label="Customer summary">
        <div class="grid grid-cols-2 gap-4 xl:grid-cols-4">
            <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-900/20" aria-hidden="true">
                        <svg class="h-5 w-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Total</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $totalCustomers }}</p>
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
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Active</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $activeCount }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-700" aria-hidden="true">
                        <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Disconnected</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $disconnectedCount }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 dark:bg-violet-900/20" aria-hidden="true">
                        <svg class="h-5 w-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-9c-1.11 0-2.08.402-2.599 1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Active Revenue</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">₱{{ number_format($activeRevenue, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/55 p-4">
            <div class="my-auto max-h-[90dvh] w-full max-w-2xl overflow-y-auto rounded-[1.25rem] bg-white shadow-card dark:bg-gray-800" role="dialog" aria-modal="true" aria-labelledby="customer-modal-title">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5 dark:border-gray-700">
                    <div>
                        <h2 id="customer-modal-title" class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $editingCustomerId ? 'Edit Customer' : 'Add New Customer' }}
                        </h2>
                        <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">
                            {{ $editingCustomerId ? 'Update the customer account details.' : 'Register a new customer and start their billing cycle.' }}
                        </p>
                    </div>

                    <button type="button" wire:click="closeModal()"
                        class="rounded-xl p-2 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600"
                        aria-label="Close dialog">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="saveCustomer" class="space-y-4 px-6 py-5">
                    <div>
                        <label for="customer-name" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Customer Name <span class="text-red-500">*</span></label>
                        <input id="customer-name" type="text" wire:model="name" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 @error('name') border-red-300 bg-red-50 @enderror" placeholder="e.g. Juan Dela Cruz">
                        @error('name')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="customer-area" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Area <span class="text-red-500">*</span></label>
                            <select id="customer-area" wire:model="areaId" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 @error('areaId') border-red-300 bg-red-50 @enderror">
                                <option value="">Select area</option>
                                @foreach ($areaOptions as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                            @error('areaId')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="customer-status" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Status</label>
                            <select id="customer-status" wire:model="status" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 @error('status') border-red-300 bg-red-50 @enderror">
                                <option value="active">Active</option>
                                <option value="disconnected">Disconnected</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="customer-contact" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Contact Number</label>
                            <input id="customer-contact" type="text" wire:model="contactNumber" maxlength="20" oninput="this.value = this.value.replace(/\D/g, '')" inputmode="numeric" pattern="[0-9]*" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 @error('contactNumber') border-red-300 bg-red-50 @enderror" placeholder="0917 555 0142">
                            @error('contactNumber')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="customer-cycle" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Billing Cycle (days) <span class="text-red-500">*</span></label>
                            <input id="customer-cycle" type="number" min="1" step="1" wire:model="billingCycleDays" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 @error('billingCycleDays') border-red-300 bg-red-50 @enderror" placeholder="32">
                            @error('billingCycleDays')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="customer-address" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Address</label>
                        <input id="customer-address" type="text" wire:model="address" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 @error('address') border-red-300 bg-red-50 @enderror" placeholder="e.g. Purok 3, Mankilam, Tagum City">
                        @error('address')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="customer-price" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Monthly Price <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3 text-sm text-gray-400">₱</span>
                                <input id="customer-price" type="number" min="0" step="0.01" wire:model="monthlyPrice" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 ps-8 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 @error('monthlyPrice') border-red-300 bg-red-50 @enderror" placeholder="600.00">
                            </div>
                            @error('monthlyPrice')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="customer-start" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Billing Start Date <span class="text-red-500">*</span></label>
                            <input id="customer-start" type="date" wire:model="billingStartDate" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 @error('billingStartDate') border-red-300 bg-red-50 @enderror">
                            @error('billingStartDate')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="customer-remarks" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Remarks</label>
                        <textarea id="customer-remarks" rows="2" wire:model="remarks" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 @error('remarks') border-red-300 bg-red-50 @enderror" placeholder="Optional notes about this customer…"></textarea>
                        @error('remarks')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" wire:click="closeModal()" class="min-h-[44px] rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 transition-colors hover:bg-gray-200">Cancel</button>
                        <button type="submit" class="min-h-[44px] rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand-700" wire:loading.attr="disabled" wire:target="saveCustomer">
                            <span wire:loading.remove wire:target="saveCustomer">{{ $editingCustomerId ? 'Save Changes' : 'Add Customer' }}</span>
                            <span wire:loading wire:target="saveCustomer">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/55 p-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-card dark:bg-gray-800">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-500 dark:bg-red-500/10" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 4h5a1 1 0 011 1v2h-7V5a1 1 0 011-1z" />
                    </svg>
                </div>

                <h2 class="text-base font-bold text-gray-900 dark:text-white">Delete {{ $deleteCustomer?->name ?? 'customer' }}?</h2>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-300">
                    This customer will be permanently removed from the system and cannot be restored.
                </p>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="closeDeleteModal()" class="min-h-[44px] rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-600 transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="button" wire:click="confirmDelete()" class="min-h-[44px] rounded-xl bg-red-500 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-red-600">
                        Delete Customer
                    </button>
                </div>
            </div>
        </div>
    @endif

    <section aria-label="Customer list" class="rounded-2xl bg-white shadow-card dark:bg-gray-800">
        <div class="flex flex-col gap-3 border-b border-gray-100 p-5 dark:border-gray-700 lg:flex-row lg:items-center">
            <div class="relative flex-1 max-w-md">
                <svg class="pointer-events-none absolute start-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                </svg>
                <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search customer, area, contact…"
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2.5 ps-9 pe-4 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200"
                    aria-label="Search customers">
            </div>

            <select wire:model.live="areaFilter" class="min-h-[2.75rem] rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700 outline-none transition-colors focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200" aria-label="Filter by area">
                <option value="">All areas</option>
                @foreach ($areaOptions as $area)
                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                @endforeach
            </select>

            <select wire:model.live="statusFilter" class="min-h-[2.75rem] rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700 outline-none transition-colors focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200" aria-label="Filter by status">
                <option value="">All statuses</option>
                <option value="active">Active</option>
                <option value="disconnected">Disconnected</option>
            </select>

            <select wire:model.live="sortBy" class="min-h-[2.75rem] rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700 outline-none transition-colors focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 lg:ms-auto" aria-label="Sort customers">
                <option value="name">Sort: Name (A–Z)</option>
                <option value="due-asc">Sort: Billing Start (soonest)</option>
                <option value="price-desc">Sort: Monthly Price (high → low)</option>
            </select>
        </div>

        <div class="tbl-wrap" tabindex="0" role="region" aria-label="Customer table">
            <table class="w-full text-sm" style="min-inline-size: 70rem;">
                <caption class="sr-only">Customer list</caption>
                <thead>
                    <tr class="border-b border-gray-100 text-left dark:border-gray-700">
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Customer</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Area</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Contact</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Monthly Price</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Cycle Start</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Remarks</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Status</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse ($customers as $customer)
                        <tr class="transition-colors hover:bg-gray-50/60 dark:hover:bg-gray-700/40">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-50 text-[10px] font-bold text-brand-600" aria-hidden="true">{{ $customer['initials'] }}</div>
                                    <div class="min-w-0">
                                        <p class="truncate font-medium text-gray-800 dark:text-gray-100">{{ $customer['name'] }}</p>
                                        <p class="truncate text-[11px] text-gray-400">{{ $customer['address'] ?: 'No address' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-gray-700 dark:text-gray-300">{{ $customer['area_name'] }}</td>
                            <td class="px-5 py-3 text-gray-700 dark:text-gray-300">{{ $customer['contact_number'] ?: 'No contact' }}</td>
                            <td class="px-5 py-3 text-end font-semibold text-gray-700 dark:text-gray-200">₱{{ number_format($customer['monthly_price'], 2) }}</td>
                            <td class="px-5 py-3 text-gray-700 dark:text-gray-300">{{ $customer['billing_start_formatted'] }}</td>
                            <td class="max-w-xs px-5 py-3 text-gray-700 dark:text-gray-300" title="{{ $customer['remarks'] }}">
                                <span class="block truncate">{{ $customer['remarks'] ?: 'No remarks' }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $customer['status'] === 'Active' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $customer['status'] === 'Active' ? 'bg-emerald-500' : 'bg-gray-400' }}" aria-hidden="true"></span>
                                    {{ $customer['status'] }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-end">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('customer-details.index', ['customer' => $customer['id']]) }}" class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-brand-50 hover:text-brand-600" aria-label="View {{ $customer['name'] }}">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <button type="button" wire:click="openEditModal({{ $customer['id'] }})" class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-brand-50 hover:text-brand-600" aria-label="Edit {{ $customer['name'] }}">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button type="button" wire:click="openDeleteModal({{ $customer['id'] }})" class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-red-50 hover:text-red-500" aria-label="Delete {{ $customer['name'] }}">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 4h5a1 1 0 011 1v2h-7V5a1 1 0 011-1z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-10 text-center text-sm text-gray-400">No customers match your filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($totalCustomers > 0)
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-5 py-4 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Showing <strong>{{ $startItem }}</strong>–<strong>{{ $endItem }}</strong> of <strong>{{ $totalCustomers }}</strong>
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

