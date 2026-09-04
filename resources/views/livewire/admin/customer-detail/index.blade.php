<div class="space-y-6">
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div class="flex min-w-0 items-center gap-4">
            <a href="{{ route('customers.index') }}" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-gray-200 text-gray-500 transition-colors hover:border-brand-200 hover:bg-brand-50 hover:text-brand-600" aria-label="Back to customers">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>

            <div class="min-w-0">
                <h1 class="truncate text-xl font-bold text-gray-900 sm:text-2xl">{{ $customer->name }}</h1>
                <div class="mt-1 flex items-center gap-2">

                    <span class="text-sm text-gray-400">{{ $customer->area?->name ?? 'No area' }}</span>
                </div>
            </div>
        </div>
    </div>

    <section class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div class="rounded-2xl bg-white p-5 shadow-card lg:col-span-2">
            <h2 class="mb-3 text-sm font-bold text-gray-900">Customer Information</h2>
            <dl class="grid grid-cols-1 gap-x-6 gap-y-3 text-sm sm:grid-cols-2">
                <div><dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Area</dt><dd class="mt-0.5 font-medium text-gray-800">{{ $customer->area?->name ?? '—' }}</dd></div>
                <div><dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Monthly Price</dt><dd class="mt-0.5 font-medium text-gray-800">₱{{ number_format((float) $customer->monthly_price, 2) }}</dd></div>
                <div><dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Billing Cycle</dt><dd class="mt-0.5 font-medium text-gray-800">{{ $customer->billing_cycle_days }} days</dd></div>
                <div><dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Contact Number</dt><dd class="mt-0.5 font-medium text-gray-800">{{ $customer->contact_number ?: '—' }}</dd></div>
                <div><dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Billing Start Date</dt><dd class="mt-0.5 font-medium text-gray-800">{{ $customer->latest_billing_date?->format('M d, Y') ?? '—' }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Address</dt><dd class="mt-0.5 font-medium text-gray-800">{{ $customer->address ?: '—' }}</dd></div>
                @if ($customer->remarks)
                    <div class="sm:col-span-2"><dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Remarks</dt><dd class="mt-0.5 text-gray-600">{{ $customer->remarks }}</dd></div>
                @endif
            </dl>
        </div>
        <div class="grid grid-cols-1 gap-4">
            @foreach ([['Current Billing', $currentBilling ? '₱'.number_format($currentBilling['amount_due'], 2) : '—', 'bg-brand-50', 'text-brand-600'], ['Current Balance', $currentBilling ? '₱'.number_format($currentBilling['balance'], 2) : '₱0.00', 'bg-red-50', 'text-red-500'], ['Billing Due', $currentBilling['due_date'] ?? '—', 'bg-amber-50', 'text-amber-600']] as [$label, $value, $background, $color])
                <div class="flex items-center gap-3 rounded-2xl bg-white p-4 shadow-card"><div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $background }} {{ $color }}"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg></div><div><p class="text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $label }}</p><p class="text-lg font-bold text-gray-900">{{ $value }}</p></div></div>
            @endforeach
        </div>
    </section>

    <section class="rounded-2xl bg-white shadow-card">
        <div class="border-b border-gray-100 px-5 py-4"><h2 class="text-sm font-bold text-gray-900">Billing History</h2><p class="mt-0.5 text-xs text-gray-400">Every billing cycle for this customer.</p></div>
        <div class="tbl-wrap" tabindex="0" role="region" aria-label="Billing history">
            <table class="w-full text-sm" style="min-inline-size: 44rem"><thead><tr class="border-b border-gray-100 text-left"><th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Billing Period</th><th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Due Date</th><th class="px-5 py-3 text-end text-xs font-semibold uppercase tracking-wider text-gray-500">Amount Due</th><th class="px-5 py-3 text-end text-xs font-semibold uppercase tracking-wider text-gray-500">Paid</th><th class="px-5 py-3 text-end text-xs font-semibold uppercase tracking-wider text-gray-500">Balance</th><th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th></tr></thead>
                <tbody class="divide-y divide-gray-50">@forelse ($billings as $billing)<tr class="transition-colors hover:bg-gray-50/60"><td class="whitespace-nowrap px-5 py-3 text-gray-700">{{ $billing['period_start'] }} – {{ $billing['period_end'] }}</td><td class="whitespace-nowrap px-5 py-3 text-gray-700">{{ $billing['due_date'] }}</td><td class="px-5 py-3 text-end font-semibold text-gray-700">₱{{ number_format($billing['amount_due'], 2) }}</td><td class="px-5 py-3 text-end text-gray-700">₱{{ number_format($billing['amount_paid'], 2) }}</td><td class="px-5 py-3 text-end font-semibold {{ $billing['balance'] > 0 ? 'text-gray-900' : 'text-gray-400' }}">₱{{ number_format($billing['balance'], 2) }}</td><td class="px-5 py-3"><span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $billing['status'] === 'Paid' ? 'bg-emerald-50 text-emerald-700' : ($billing['status'] === 'Partial' ? 'bg-amber-50 text-amber-700' : ($billing['status'] === 'Overdue' ? 'bg-red-50 text-red-700' : ($billing['status'] === 'Due' ? 'bg-orange-50 text-orange-700' : 'bg-emerald-50 text-emerald-700'))) }}"><span class="h-1.5 w-1.5 rounded-full {{ $billing['status'] === 'Paid' ? 'bg-emerald-500' : ($billing['status'] === 'Partial' ? 'bg-amber-500' : ($billing['status'] === 'Overdue' ? 'bg-red-500' : ($billing['status'] === 'Due' ? 'bg-orange-500' : 'bg-emerald-500'))) }}"></span>{{ $billing['status'] }}</span></td></tr>@empty<tr><td colspan="6" class="px-5 py-10 text-center text-sm text-gray-400">No billing records yet.</td></tr>@endforelse</tbody>
            </table>
        </div>
        @if ($billingTotal > 0)
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-5 py-4">
                <p class="text-xs text-gray-500">Showing {{ (($billingPage - 1) * $perPage) + 1 }}–{{ min($billingPage * $perPage, $billingTotal) }} of {{ $billingTotal }}</p>
                <div class="flex items-center gap-1">
                    <button type="button" wire:click="previousBillingPage" @disabled($billingPage <= 1) class="min-h-[36px] rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40">Prev</button>
                    @for ($page = 1; $page <= $billingTotalPages; $page++)
                        <button type="button" wire:click="goToBillingPage({{ $page }})" class="h-9 w-9 rounded-lg text-xs font-semibold {{ $page === $billingPage ? 'bg-brand-600 text-white' : 'border border-gray-200 bg-white text-gray-600 hover:bg-gray-50' }}">{{ $page }}</button>
                    @endfor
                    <button type="button" wire:click="nextBillingPage" @disabled($billingPage >= $billingTotalPages) class="min-h-[36px] rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40">Next</button>
                </div>
            </div>
        @endif
    </section>

    <section class="rounded-2xl bg-white shadow-card">
        <div class="border-b border-gray-100 px-5 py-4"><h2 class="text-sm font-bold text-gray-900">Payment History</h2><p class="mt-0.5 text-xs text-gray-400">Actual payments received for this customer.</p></div>
        <div class="tbl-wrap" tabindex="0" role="region" aria-label="Payment history">
            <table class="w-full text-sm" style="min-inline-size: 44rem"><thead><tr class="border-b border-gray-100 text-left"><th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Payment Date</th><th class="px-5 py-3 text-end text-xs font-semibold uppercase tracking-wider text-gray-500">Amount</th><th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Applied Billing</th><th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Method</th><th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Reference</th><th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Remarks</th></tr></thead>
                <tbody class="divide-y divide-gray-50">@forelse ($payments as $payment)<tr class="transition-colors hover:bg-gray-50/60"><td class="whitespace-nowrap px-5 py-3 text-gray-700">{{ $payment['date'] }}</td><td class="px-5 py-3 text-end font-semibold text-gray-700">₱{{ number_format($payment['amount'], 2) }}</td><td class="whitespace-nowrap px-5 py-3 text-gray-700">{{ $payment['billing_period'] }}</td><td class="px-5 py-3 text-gray-700">{{ $payment['method'] }}</td><td class="px-5 py-3 text-gray-500">{{ $payment['reference'] ?: '—' }}</td><td class="px-5 py-3 text-gray-500">{{ $payment['remarks'] ?: '—' }}</td></tr>@empty<tr><td colspan="6" class="px-5 py-10 text-center text-sm text-gray-400">No payments recorded yet.</td></tr>@endforelse</tbody>
            </table>
        </div>
        @if ($paymentTotal > 0)
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-5 py-4">
                <p class="text-xs text-gray-500">Showing {{ (($paymentPage - 1) * $perPage) + 1 }}–{{ min($paymentPage * $perPage, $paymentTotal) }} of {{ $paymentTotal }}</p>
                <div class="flex items-center gap-1">
                    <button type="button" wire:click="previousPaymentPage" @disabled($paymentPage <= 1) class="min-h-[36px] rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40">Prev</button>
                    @for ($page = 1; $page <= $paymentTotalPages; $page++)
                        <button type="button" wire:click="goToPaymentPage({{ $page }})" class="h-9 w-9 rounded-lg text-xs font-semibold {{ $page === $paymentPage ? 'bg-brand-600 text-white' : 'border border-gray-200 bg-white text-gray-600 hover:bg-gray-50' }}">{{ $page }}</button>
                    @endfor
                    <button type="button" wire:click="nextPaymentPage" @disabled($paymentPage >= $paymentTotalPages) class="min-h-[36px] rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40">Next</button>
                </div>
            </div>
        @endif
    </section>
</div>
