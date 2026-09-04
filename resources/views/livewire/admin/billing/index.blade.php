<div class="space-y-6">
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" wire:click.self="closeModal()">
            <div class="w-full max-w-2xl rounded-[1.25rem] bg-white shadow-card">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">{{ $editingPaymentId ? 'Edit Payment' : 'Record Payment' }}</h2>
                        <p class="mt-0.5 text-xs text-gray-400">{{ $editingPaymentId ? 'Correct the last payment on this billing' : 'Apply a payment against a customer\'s billing' }}</p>
                    </div>
                    <button type="button" wire:click="closeModal()" class="rounded-xl p-2 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600" aria-label="Close dialog">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form wire:submit.prevent="savePayment" class="space-y-4 px-6 py-5">
                    @php $selectedBilling = $selectedBillingId ? collect($filteredBillings)->firstWhere('id', $selectedBillingId) : null; @endphp

                    @if ($selectedBilling)
                        @php
                            $previewMonths = max(1, (int) ($payForm['months'] ?? 1));
                            $previewStart = \Illuminate\Support\Carbon::parse($selectedBilling['period_start']);
                            $previewEnd = $previewStart->copy()->addMonths($previewMonths)->subDay();
                            $previewDueDate = $previewStart->copy()->addMonths($previewMonths)->subDay();
                            $previewAmountDue = (float) ($selectedBilling['amount_due'] ?? 0) * $previewMonths;
                            $previewRemainingBalance = max(0, $previewAmountDue - (float) ($selectedBilling['amount_paid'] ?? 0));
                        @endphp

                        <div class="rounded-xl bg-gray-50 px-4 py-3.5">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-bold text-gray-900">{{ $selectedBilling['name'] ?? 'Customer' }}</p>
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $selectedBilling['status'] === 'paid' ? 'bg-emerald-50 text-emerald-700' : ($selectedBilling['status'] === 'partial' ? 'bg-amber-50 text-amber-700' : ($selectedBilling['status'] === 'overdue' ? 'bg-red-50 text-red-700' : ($selectedBilling['status_label'] === 'Due' ? 'bg-orange-50 text-orange-700' : 'bg-emerald-50 text-emerald-700'))) }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $selectedBilling['status'] === 'paid' ? 'bg-emerald-500' : ($selectedBilling['status'] === 'partial' ? 'bg-amber-500' : ($selectedBilling['status'] === 'overdue' ? 'bg-red-500' : ($selectedBilling['status_label'] === 'Due' ? 'bg-orange-500' : 'bg-emerald-500'))) }}" aria-hidden="true"></span>
                                    {{ $selectedBilling['status_label'] ?? 'Due' }}
                                </span>
                            </div>
                            <dl class="mt-3 grid grid-cols-2 gap-x-4 gap-y-2 text-xs">
                                <dt class="text-gray-500">Billing Period</dt>
                                <dd class="text-end font-medium text-gray-800">{{ $previewStart->format('M d, Y') }} – {{ $previewEnd->format('M d, Y') }}</dd>
                                <dt class="text-gray-500">Due Date</dt>
                                <dd class="text-end font-medium text-gray-800">{{ $previewDueDate->format('M d, Y') }}</dd>
                                <dt class="text-gray-500">Amount Due</dt>
                                <dd class="text-end font-medium text-gray-800">₱{{ number_format($previewAmountDue, 2) }}</dd>
                                <dt class="text-gray-500">Remaining Balance</dt>
                                <dd class="text-end font-bold text-gray-900">₱{{ number_format($previewRemainingBalance, 2) }}</dd>
                            </dl>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="pay-amount" class="mb-1.5 block text-xs font-semibold text-gray-600">Monthly Amount <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="pointer-events-none absolute start-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">₱</span>
                                <input id="pay-amount" type="number" min="0.01" step="0.01" wire:model="payForm.amount" class="w-full rounded-xl border border-gray-200 bg-gray-50 ps-7 pe-4 py-2.5 text-sm text-gray-700 outline-none transition-colors focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 {{ isset($payErrors['amount']) ? 'border-red-300 bg-red-50' : '' }}" placeholder="0.00">
                            </div>
                            @if (isset($payErrors['amount']))
                                <p class="mt-1 text-xs text-red-500">{{ $payErrors['amount'] }}</p>
                            @endif
                            @error('payForm.amount') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="pay-date" class="mb-1.5 block text-xs font-semibold text-gray-600">Payment Date <span class="text-red-500">*</span></label>
                            <input id="pay-date" type="date" wire:model="payForm.date" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 @error('payForm.date') border-red-300 bg-red-50 @enderror">
                            @error('payForm.date') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="rounded-xl border border-dashed border-brand-200 bg-brand-50 px-3 py-2.5 text-sm text-brand-700">
                        Total: <span class="font-bold">₱{{ number_format((float) ($payForm['amount'] ?? 0), 2) }}</span>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="pay-method" class="mb-1.5 block text-xs font-semibold text-gray-600">Payment Method</label>
                            <select id="pay-method" wire:model.live="payForm.method" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100">
                                <option value="Cash">Cash</option>
                                <option value="GCash">GCash</option>
                            </select>
                        </div>

                        <div>
                            <label for="pay-ref" class="mb-1.5 block text-xs font-semibold text-gray-600">Reference Number</label>
                            <input id="pay-ref" type="text" wire:model="payForm.reference" @disabled(($payForm['method'] ?? 'Cash') === 'Cash') class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-400" placeholder="e.g. GC-88213">
                        </div>
                    </div>

                    <div>
                        <label for="pay-remarks" class="mb-1.5 block text-xs font-semibold text-gray-600">Remarks</label>
                        <textarea id="pay-remarks" rows="2" wire:model="payForm.remarks" class="w-full resize-none rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100" placeholder="Optional notes about this payment…"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-4">
                        <button type="button" wire:click="closeModal()" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-gray-500 transition-colors hover:bg-gray-100">Cancel</button>
                        <button type="submit" class="rounded-xl bg-brand-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700">{{ $editingPaymentId ? 'Save Changes' : 'Save Payment' }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-900 sm:text-2xl">Billing</h1>
            <p class="mt-0.5 text-sm text-gray-500">See who's billed, who's paid, and who still owes for the selected period</p>
        </div>
    </div>

    <section aria-label="Billing summary" class="grid grid-cols-2 gap-4 xl:grid-cols-4">
        <div class="rounded-2xl bg-white p-4 shadow-card">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-600" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" /></svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Billing Records</p>
                    <p class="text-lg font-bold text-gray-900">{{ $summary['count'] }}</p>
                </div>
            </div>
        </div>
        <div class="rounded-2xl bg-white p-4 shadow-card">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-9c-1.11 0-2.08.402-2.599 1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Total Billing</p>
                    <p class="text-lg font-bold text-gray-900">₱{{ number_format($summary['totalBilling'], 2) }}</p>
                </div>
            </div>
        </div>
        <div class="rounded-2xl bg-white p-4 shadow-card">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Total Paid</p>
                    <p class="text-lg font-bold text-gray-900">₱{{ number_format($summary['totalPaid'], 2) }}</p>
                </div>
            </div>
        </div>
        <div class="rounded-2xl bg-white p-4 shadow-card">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-red-500" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Outstanding</p>
                    <p class="text-lg font-bold text-gray-900">₱{{ number_format($summary['outstanding'], 2) }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="rounded-2xl bg-white shadow-card">
        <div class="space-y-3 border-b border-gray-100 p-5">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
                <select wire:model.live="monthFilter" class="min-h-[2.75rem] rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700 outline-none transition-colors focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100">
                    @foreach ($monthOptions as $month)
                        <option value="{{ $month['value'] }}">{{ $month['label'] }}</option>
                    @endforeach
                </select>

                <select wire:model.live="yearFilter" class="min-h-[2.75rem] rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700 outline-none transition-colors focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100">
                    @foreach ($yearOptions as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>

                <select wire:model.live="statusFilter" class="min-h-[2.75rem] rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700 outline-none transition-colors focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100">
                    <option value="">All</option>
                    <option value="Due">Due</option>
                    <option value="Partial">Partial</option>
                    <option value="Paid">Paid</option>
                    <option value="Overdue">Overdue</option>
                </select>

                <div class="relative flex-1 max-w-sm">
                    <svg class="pointer-events-none absolute start-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" /></svg>
                    <input type="search" wire:model.live.debounce.200ms="query" placeholder="Search customer, area, contact…" class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2.5 ps-9 pe-4 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100">
                </div>

                <button type="button" wire:click="clearFilters()" class="text-xs font-semibold text-gray-500 transition-colors hover:text-brand-600 lg:ms-auto">Clear Filters</button>
            </div>
        </div>

        <div class="tbl-wrap" tabindex="0" role="region" aria-label="Billing table">
            <table class="w-full text-sm" style="min-inline-size: 52rem;">
                <thead>
                    <tr class="border-b border-gray-100 text-left">
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500">
                            <button type="button" wire:click="toggleSort('customer')" class="flex items-center gap-1 hover:text-brand-600">
                                Customer
                                @if ($sortBy === 'customer')
                                    <span class="text-[9px]">{{ $sortDir === 'asc' ? '▲' : '▼' }}</span>
                                @endif
                            </button>
                        </th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500">Billing Period</th>
                        <th scope="col" class="px-5 py-3 text-end text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500">
                            <button type="button" wire:click="toggleSort('amount')" class="ms-auto flex items-center justify-end gap-1 hover:text-brand-600">
                                Amount Due
                                @if ($sortBy === 'amount')
                                    <span class="text-[9px]">{{ $sortDir === 'asc' ? '▲' : '▼' }}</span>
                                @endif
                            </button>
                        </th>
                        <th scope="col" class="px-5 py-3 text-end text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500">Amount Paid</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500">
                            <button type="button" wire:click="toggleSort('status')" class="flex items-center gap-1 hover:text-brand-600">
                                Status
                                @if ($sortBy === 'status')
                                    <span class="text-[9px]">{{ $sortDir === 'asc' ? '▲' : '▼' }}</span>
                                @endif
                            </button>
                        </th>
                        <th scope="col" class="px-5 py-3 text-end text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($filteredBillings as $billing)
                        @php $lastPayment = collect($billing['payments'])->last(); @endphp
                        <tr class="transition-colors hover:bg-gray-50/60">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-50 text-[10px] font-bold text-brand-600" aria-hidden="true">{{ strtoupper(substr($billing['name'], 0, 1) . (str_contains($billing['name'], ' ') ? substr(strrchr($billing['name'], ' '), 1, 1) : '')) }}</div>
                                    <div class="min-w-0">
                                        <p class="truncate font-medium text-gray-800">{{ $billing['name'] }}</p>
                                        <p class="truncate text-[11px] text-gray-400">{{ $billing['area'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap text-gray-700">{{ \Illuminate\Support\Carbon::parse($billing['period_start'])->format('M d, Y') }} – {{ \Illuminate\Support\Carbon::parse($billing['period_end'])->format('M d, Y') }}</td>
                            <td class="px-5 py-3 text-end font-semibold text-gray-700">₱{{ number_format($billing['amount_due'], 2) }}</td>
                            <td class="px-5 py-3 text-end text-gray-700">₱{{ number_format($billing['amount_paid'], 2) }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $billing['status'] === 'paid' ? 'bg-emerald-50 text-emerald-700' : ($billing['status'] === 'partial' ? 'bg-amber-50 text-amber-700' : ($billing['status'] === 'overdue' ? 'bg-red-50 text-red-700' : ($billing['status_label'] === 'Due' ? 'bg-orange-50 text-orange-700' : 'bg-emerald-50 text-emerald-700'))) }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $billing['status'] === 'paid' ? 'bg-emerald-500' : ($billing['status'] === 'partial' ? 'bg-amber-500' : ($billing['status'] === 'overdue' ? 'bg-red-500' : ($billing['status_label'] === 'Due' ? 'bg-orange-500' : 'bg-emerald-500'))) }}" aria-hidden="true"></span>
                                    {{ $billing['status_label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-end">
                                <div class="flex items-center justify-end gap-1">
                                    @if ($billing['status'] !== 'paid')
                                        <button type="button" wire:click="openRecordModal({{ $billing['id'] }})" class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-emerald-50 hover:text-emerald-600" aria-label="Record payment for {{ $billing['name'] }}">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-9c-1.11 0-2.08.402-2.599 1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </button>
                                    @endif
                                    <button type="button" wire:click="openEditPayment({{ $billing['id'] }}, {{ $lastPayment['id'] ?? 0 }})" @disabled(!$lastPayment) class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-brand-50 hover:text-brand-600 disabled:cursor-not-allowed disabled:opacity-30" aria-label="Edit payment for {{ $billing['name'] }}">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14.25h6m-6-3h6m2.25-7.5H6.75A2.25 2.25 0 004.5 6v12a2.25 2.25 0 002.25 2.25h10.5A2.25 2.25 0 0019.5 18V6a2.25 2.25 0 00-2.25-2.25zM9 7.5h.008v.008H9V7.5z" />
                                    </svg>
                                    <span>No billing records match your filters.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($totalFilteredBillings > 0)
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-5 py-4 text-xs text-gray-500">
                <p>Showing <strong>{{ (($currentPage - 1) * $perPage) + 1 }}–{{ min($currentPage * $perPage, $totalFilteredBillings) }}</strong> of <strong>{{ $totalFilteredBillings }}</strong> billing records for <strong>{{ \Illuminate\Support\Carbon::create($yearFilter, $monthFilter, 1)->translatedFormat('F Y') }}</strong></p>
                <div class="flex items-center gap-1">
                    <button type="button" wire:click="previousPage" @disabled($currentPage <= 1) class="min-h-[36px] rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40">Prev</button>
                    @for ($page = 1; $page <= $totalPages; $page++)
                        <button type="button" wire:click="goToPage({{ $page }}, {{ $totalPages }})" class="h-9 w-9 rounded-lg text-xs font-semibold {{ $page === $currentPage ? 'bg-brand-600 text-white' : 'border border-gray-200 bg-white text-gray-600 hover:bg-gray-50' }}">{{ $page }}</button>
                    @endfor
                    <button type="button" wire:click="nextPage({{ $totalPages }})" @disabled($currentPage >= $totalPages) class="min-h-[36px] rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40">Next</button>
                </div>
            </div>
        @else
            <div class="border-t border-gray-100 px-5 py-4 text-xs text-gray-500">
                No billing records to display for <strong>{{ \Illuminate\Support\Carbon::create($yearFilter, $monthFilter, 1)->translatedFormat('F Y') }}</strong>
            </div>
        @endif
    </section>
</div>
