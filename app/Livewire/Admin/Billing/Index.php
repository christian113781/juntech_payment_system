<?php

namespace App\Livewire\Admin\Billing;

use App\Models\Billing;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\PaymentAllocation;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Billings'])]
class Index extends Component
{
    public string $query = '';

    public string $statusFilter = '';

    public int $monthFilter;

    public int $yearFilter;

    public string $sortBy = 'due';

    public string $sortDir = 'asc';

    public int $perPage = 50;

    public int $currentPage = 1;

    public int $selectedBillingId = 0;

    public ?int $editingPaymentId = null;

    public bool $showModal = false;

    public array $payForm = [
        'billing_id' => null,
        'months' => 1,
        'amount' => '',
        'date' => '',
        'method' => 'Cash',
        'reference' => '',
        'remarks' => '',
    ];

    public array $payErrors = [];

    public function mount(): void
    {
        $this->monthFilter = now()->month;
        $this->yearFilter = now()->year;
        $this->payForm['date'] = today()->toDateString();
    }

    public function openRecordModal(?int $billingId = null): void
    {
        $this->showModal = true;
        $this->editingPaymentId = null;
        $this->selectedBillingId = $billingId ?? 0;
        $this->payErrors = [];

        $billing = $billingId ? Billing::with('customer')->find($billingId) : null;

        $this->payForm = [
            'billing_id' => $billing?->id,
            'months' => 1,
            'amount' => $billing ? (string) max(0, (float) $billing->amount_due) : '',
            'date' => today()->toDateString(),
            'method' => 'Cash',
            'reference' => '',
            'remarks' => '',
        ];
    }

    public function openEditPayment(int $billingId, int $paymentId): void
    {
        $allocation = PaymentAllocation::with('payment')->where('billing_id', $billingId)->where('payment_id', $paymentId)->first();

        if (! $allocation || ! $allocation->payment) {
            return;
        }

        $this->showModal = true;
        $this->editingPaymentId = $allocation->payment_id;
        $this->selectedBillingId = $billingId;
        $this->payErrors = [];
        $this->payForm = [
            'billing_id' => $billingId,
            'months' => 1,
            'amount' => (string) $allocation->amount,
            'date' => $allocation->payment->payment_date->toDateString(),
            'method' => ucfirst($allocation->payment->payment_method),
            'reference' => $allocation->payment->reference_number ?? '',
            'remarks' => $allocation->payment->remarks ?? '',
        ];
    }

    public function savePayment(): void
    {
        $this->payErrors = [];

        if (! $this->payForm['billing_id']) {
            $this->payErrors['billing'] = 'Please select a billing record.';
        }

        $amount = (float) ($this->payForm['amount'] ?? 0);
        $selectedMonths = max(1, (int) ($this->payForm['months'] ?? 1));
        $billing = Billing::with('customer')->find($this->payForm['billing_id']);
        $previousAmountPaid = $billing ? (float) $billing->amount_paid : 0;

        if (! $billing) {
            $this->payErrors['billing'] = 'Billing record not found.';
        }

        if ($amount <= 0) {
            $this->payErrors['amount'] = 'Payment amount must be greater than zero.';
        }

        $remainingBalance = $billing
            ? round(max(0, (float) $billing->amount_due - (float) $billing->amount_paid), 2)
            : 0;

        if ($billing && $amount > $remainingBalance) {
            $this->payErrors['amount'] = 'Payment amount cannot exceed the remaining balance of '.number_format($remainingBalance, 2).'.';
        }

        if ($selectedMonths < 1 || $selectedMonths > 12) {
            $this->payErrors['months'] = 'Please select a valid billing period.';
        }

        if (empty($this->payForm['date'])) {
            $this->payErrors['date'] = 'Please select a payment date.';
        }

        if ($this->payErrors !== []) {
            return;
        }

        if ($this->editingPaymentId) {
            $payment = Payment::findOrFail($this->editingPaymentId);
            $allocation = PaymentAllocation::where('payment_id', $payment->id)
                ->where('billing_id', $billing->id)
                ->firstOrFail();

            $oldAmount = (float) $allocation->amount;
            $payment->update([
                'customer_id' => $billing->customer_id,
                'amount_paid' => $amount,
                'payment_date' => $this->payForm['date'],
                'payment_method' => strtolower($this->payForm['method']),
                'reference_number' => $this->payForm['reference'],
                'remarks' => $this->payForm['remarks'],
            ]);

            $allocation->update(['amount' => $amount]);
            $billing->amount_paid = round((float) $billing->amount_paid - $oldAmount + $amount, 2);
            $billing->balance = round(max(0, (float) $billing->amount_due - (float) $billing->amount_paid), 2);
            $billing->status = $this->resolveBillingStatus((float) $billing->amount_due, (float) $billing->amount_paid, $billing->due_date);
            $billing->save();
        } else {
            $currentBillingPaid = min((float) $billing->amount_due, (float) $amount);
            $billing->amount_paid = round($currentBillingPaid, 2);
            $billing->balance = round(max(0, (float) $billing->amount_due - (float) $billing->amount_paid), 2);
            $billing->status = $this->resolveBillingStatus((float) $billing->amount_due, (float) $billing->amount_paid, $billing->due_date);
            $billing->save();

            $currentPayment = Payment::create([
                'customer_id' => $billing->customer_id,
                'amount_paid' => $currentBillingPaid,
                'payment_date' => $this->payForm['date'],
                'payment_method' => strtolower($this->payForm['method']),
                'reference_number' => $this->payForm['reference'],
                'remarks' => $this->payForm['remarks'],
            ]);

            PaymentAllocation::create([
                'payment_id' => $currentPayment->id,
                'billing_id' => $billing->id,
                'amount' => $currentBillingPaid,
            ]);

            if ($previousAmountPaid > 0) {
                $billing->amount_paid = round($previousAmountPaid + $currentBillingPaid, 2);
                $billing->balance = round(max(0, (float) $billing->amount_due - (float) $billing->amount_paid), 2);
                $billing->status = $this->resolveBillingStatus((float) $billing->amount_due, (float) $billing->amount_paid, $billing->due_date);
                $billing->save();

                $this->closeModal();

                return;
            }

            $basePeriodStart = Carbon::parse($billing->period_start);

            for ($monthOffset = 1; $monthOffset <= $selectedMonths; $monthOffset++) {
                $nextPeriodStart = $basePeriodStart->copy()->addMonths($monthOffset);
                $nextPeriodEnd = $nextPeriodStart->copy()->addMonth()->subDay();
                $nextDueDate = $nextPeriodEnd->copy();

                $nextBilling = Billing::create([
                    'customer_id' => $billing->customer_id,
                    'period_start' => $nextPeriodStart->toDateString(),
                    'period_end' => $nextPeriodEnd->toDateString(),
                    'amount_due' => max(0, (float) $billing->amount_due),
                    'amount_paid' => 0,
                    'balance' => max(0, (float) $billing->amount_due),
                    'due_date' => $nextDueDate->toDateString(),
                    'status' => 'unpaid',
                    'remarks' => 'Auto-generated billing after payment',
                ]);

                if ($selectedMonths > 1 && $monthOffset < $selectedMonths) {
                    $nextBilling->amount_paid = round(min((float) $nextBilling->amount_due, (float) $amount), 2);
                    $nextBilling->balance = round(max(0, (float) $nextBilling->amount_due - (float) $nextBilling->amount_paid), 2);
                    $nextBilling->status = $this->resolveBillingStatus((float) $nextBilling->amount_due, (float) $nextBilling->amount_paid, $nextBilling->due_date);
                    $nextBilling->save();

                    $futurePayment = Payment::create([
                        'customer_id' => $nextBilling->customer_id,
                        'amount_paid' => $nextBilling->amount_paid,
                        'payment_date' => $this->payForm['date'],
                        'payment_method' => strtolower($this->payForm['method']),
                        'reference_number' => $this->payForm['reference'],
                        'remarks' => $this->payForm['remarks'],
                    ]);

                    PaymentAllocation::create([
                        'payment_id' => $futurePayment->id,
                        'billing_id' => $nextBilling->id,
                        'amount' => $nextBilling->amount_paid,
                    ]);
                } else {
                    $nextBilling->balance = round(max(0, (float) $nextBilling->amount_due - (float) $nextBilling->amount_paid), 2);
                    $nextBilling->status = $this->resolveBillingStatus((float) $nextBilling->amount_due, (float) $nextBilling->amount_paid, $nextBilling->due_date);
                    $nextBilling->save();
                }
            }
        }

        $this->closeModal();
    }

    public function updatedPayFormMethod(string $method): void
    {
        if ($method === 'Cash') {
            $this->payForm['reference'] = '';
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetPayForm();
    }

    public function clearFilters(): void
    {
        $this->query = '';
        $this->statusFilter = '';
        $this->monthFilter = now()->month;
        $this->yearFilter = now()->year;
        $this->currentPage = 1;
    }

    public function updatedQuery(): void
    {
        $this->currentPage = 1;
    }

    public function updatedStatusFilter(): void
    {
        $this->currentPage = 1;
    }

    public function updatedMonthFilter(): void
    {
        $this->currentPage = 1;
    }

    public function updatedYearFilter(): void
    {
        $this->currentPage = 1;
    }

    public function previousPage(): void
    {
        $this->currentPage = max(1, $this->currentPage - 1);
    }

    public function nextPage(int $totalPages): void
    {
        $this->currentPage = min($totalPages, $this->currentPage + 1);
    }

    public function goToPage(int $page, int $totalPages): void
    {
        $this->currentPage = max(1, min($page, $totalPages));
    }

    public function toggleSort(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDir = 'asc';
        }

        $this->currentPage = 1;
    }

    public function render()
    {
        $billings = Billing::query()
            ->with(['customer.area', 'paymentAllocations.payment'])
            ->get()
            ->map(function (Billing $billing) {
                $amountPaid = (float) $billing->paymentAllocations->sum('amount');
                $status = $this->resolveBillingStatus((float) $billing->amount_due, $amountPaid, $billing->due_date);

                return [
                    'id' => $billing->id,
                    'customer_id' => $billing->customer_id,
                    'name' => $billing->customer?->name ?? 'Unknown',
                    'area' => $billing->customer?->area?->name ?? '—',
                    'contact_number' => $billing->customer?->contact_number ?? '',
                    'period_start' => $billing->period_start?->toDateString(),
                    'period_end' => $billing->period_end?->toDateString(),
                    'due_date' => $billing->due_date?->toDateString(),
                    'amount_due' => (float) $billing->amount_due,
                    'amount_paid' => $amountPaid,
                    'balance' => round(max(0, (float) $billing->amount_due - $amountPaid), 2),
                    'status' => $status,
                    'status_label' => $this->resolveBillingLabel($status, $billing->due_date),
                    'payments' => $billing->paymentAllocations->map(fn ($allocation) => [
                        'id' => $allocation->payment?->id,
                        'amount' => (float) $allocation->amount,
                        'date' => $allocation->payment?->payment_date?->toDateString(),
                        'method' => ucfirst($allocation->payment?->payment_method ?? 'cash'),
                        'reference' => $allocation->payment?->reference_number ?? '',
                    ])->values()->all(),
                ];
            })->all();

        $filteredBillings = array_values(array_filter($billings, function (array $billing) {
            $search = strtolower(trim($this->query));
            $customerName = strtolower($billing['name']);
            $area = strtolower($billing['area']);
            $contact = strtolower($billing['contact_number']);
            $status = strtolower($billing['status']);

            $matchesQuery = $search === '' || str_contains($customerName, $search) || str_contains($area, $search) || str_contains($contact, $search) || str_contains($status, $search);
            $matchesStatus = $this->statusFilter === '' || $this->normalizeStatus($billing['status']) === $this->normalizeStatus($this->statusFilter);
            $dueDate = Carbon::parse($billing['due_date'] ?? now()->toDateString());
            $matchesMonth = $this->monthFilter === 0 || (int) $dueDate->month === $this->monthFilter;
            $matchesYear = $this->yearFilter === 0 || (int) $dueDate->year === $this->yearFilter;

            return $matchesQuery && $matchesStatus && $matchesMonth && $matchesYear;
        }));

        usort($filteredBillings, function (array $a, array $b) {
            if ($this->sortBy === 'customer') {
                $comparison = strcmp($a['name'], $b['name']);
            } elseif ($this->sortBy === 'amount') {
                $comparison = $a['amount_due'] <=> $b['amount_due'];
            } elseif ($this->sortBy === 'status') {
                $statusOrder = ['paid' => 4, 'partial' => 3, 'overdue' => 2, 'unpaid' => 1];
                $comparison = ($statusOrder[$a['status']] ?? 0) <=> ($statusOrder[$b['status']] ?? 0);
            } else {
                $comparison = strcmp($a['due_date'] ?? '9999-12-31', $b['due_date'] ?? '9999-12-31');
            }

            return $this->sortDir === 'asc' ? $comparison : -$comparison;
        });

        $summary = [
            'count' => count($filteredBillings),
            'totalBilling' => array_sum(array_map(fn (array $billing) => $billing['amount_due'], $filteredBillings)),
            'totalPaid' => array_sum(array_map(fn (array $billing) => $billing['amount_paid'], $filteredBillings)),
            'outstanding' => array_sum(array_map(fn (array $billing) => $billing['balance'], $filteredBillings)),
        ];

        $totalPages = max(1, (int) ceil(count($filteredBillings) / $this->perPage));
        $this->currentPage = max(1, min($this->currentPage, $totalPages));
        $offset = ($this->currentPage - 1) * $this->perPage;
        $paginatedBillings = array_slice($filteredBillings, $offset, $this->perPage);

        return view('livewire.admin.billing.index', [
            'filteredBillings' => $paginatedBillings,
            'summary' => $summary,
            'selectedBilling' => $this->selectedBillingId ? collect($paginatedBillings)->firstWhere('id', $this->selectedBillingId) : null,
            'totalPages' => $totalPages,
            'totalFilteredBillings' => count($filteredBillings),
            'monthOptions' => [
                ['value' => 1, 'label' => 'January'], ['value' => 2, 'label' => 'February'], ['value' => 3, 'label' => 'March'],
                ['value' => 4, 'label' => 'April'], ['value' => 5, 'label' => 'May'], ['value' => 6, 'label' => 'June'],
                ['value' => 7, 'label' => 'July'], ['value' => 8, 'label' => 'August'], ['value' => 9, 'label' => 'September'],
                ['value' => 10, 'label' => 'October'], ['value' => 11, 'label' => 'November'], ['value' => 12, 'label' => 'December'],
            ],
            'yearOptions' => [2025, 2026, 2027],
        ]);
    }

    protected function normalizeStatus(string $status): string
    {
        return match (strtolower($status)) {
            'due' => 'unpaid',
            'paid' => 'paid',
            'partial' => 'partial',
            'overdue' => 'overdue',
            default => strtolower($status),
        };
    }

    protected function resolveBillingStatus(float $amountDue, float $amountPaid, mixed $dueDate): string
    {
        if ($amountPaid >= $amountDue) {
            return 'paid';
        }

        if ($amountPaid > 0) {
            return 'partial';
        }

        $due = $dueDate ? Carbon::parse($dueDate) : null;

        if ($due && $due->lt(Carbon::today())) {
            return 'overdue';
        }

        if ($due && $due->equalTo(Carbon::today())) {
            return 'unpaid';
        }

        return 'unpaid';
    }

    protected function resolveBillingLabel(string $status, mixed $dueDate): string
    {
        if ($status === 'paid') {
            return 'Paid';
        }

        if ($status === 'partial') {
            return 'Partial';
        }

        if ($status === 'overdue') {
            return 'Overdue';
        }

        if (! $dueDate) {
            return 'Due';
        }

        $due = Carbon::parse($dueDate);
        $today = Carbon::today();

        if ($due->lt($today)) {
            return 'Overdue';
        }

        if ($due->equalTo($today)) {
            return 'Due';
        }

        return 'On Time';
    }

    protected function getAllocationAmountForEdit(int $paymentId, int $billingId): float
    {
        $allocation = PaymentAllocation::where('payment_id', $paymentId)
            ->where('billing_id', $billingId)
            ->first();

        return $allocation ? (float) $allocation->amount : 0.0;
    }

    protected function resetPayForm(): void
    {
        $this->editingPaymentId = null;
        $this->selectedBillingId = 0;
        $this->payForm = [
            'billing_id' => null,
            'months' => 1,
            'amount' => '',
            'date' => today()->toDateString(),
            'method' => 'Cash',
            'reference' => '',
            'remarks' => '',
        ];
        $this->payErrors = [];
    }
}
