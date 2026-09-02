<?php

namespace App\Livewire\Admin\CustomerDetail;

use App\Models\Billing;
use App\Models\Customer;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Customer Details'])]
class Index extends Component
{
    public Customer $customer;

    public int $billingPage = 1;

    public int $paymentPage = 1;

    public int $perPage = 10;

    public function mount(Customer $customer): void
    {
        $this->customer = $customer->load(['area', 'billings.paymentAllocations.payment']);
    }

    public function render()
    {
        $billings = $this->customer->billings
            ->sortBy('period_start')
            ->map(fn (Billing $billing) => [
                'id' => $billing->id,
                'period_start' => $billing->period_start?->format('M d, Y') ?? '—',
                'period_end' => $billing->period_end?->format('M d, Y') ?? '—',
                'due_date' => $billing->due_date?->format('M d, Y') ?? '—',
                'amount_due' => (float) $billing->amount_due,
                'amount_paid' => (float) $billing->paymentAllocations->sum('amount'),
                'balance' => (float) $billing->balance,
                'status' => $this->statusLabel($billing),
            ])->values();

        $currentBilling = $billings->first(fn (array $billing) => $billing['balance'] > 0) ?? $billings->last();
        $payments = $this->customer->payments()
            ->with('allocations.billing')
            ->latest('payment_date')
            ->get()
            ->map(fn ($payment) => [
                'date' => $payment->payment_date?->format('M d, Y') ?? '—',
                'amount' => (float) $payment->amount_paid,
                'billing_period' => $payment->allocations->first()?->billing
                    ? $payment->allocations->first()->billing->period_start->format('M d, Y').' – '.$payment->allocations->first()->billing->period_end->format('M d, Y')
                    : '—',
                'method' => ucfirst($payment->payment_method ?? 'Cash'),
                'reference' => $payment->reference_number,
                'remarks' => $payment->remarks,
            ]);

        $billingTotal = $billings->count();
        $paymentTotal = $payments->count();
        $billingTotalPages = max(1, (int) ceil($billingTotal / $this->perPage));
        $paymentTotalPages = max(1, (int) ceil($paymentTotal / $this->perPage));
        $this->billingPage = min($this->billingPage, $billingTotalPages);
        $this->paymentPage = min($this->paymentPage, $paymentTotalPages);

        return view('livewire.admin.customer-detail.index', [
            'billings' => $billings->forPage($this->billingPage, $this->perPage),
            'payments' => $payments->forPage($this->paymentPage, $this->perPage),
            'currentBilling' => $currentBilling,
            'billingTotal' => $billingTotal,
            'paymentTotal' => $paymentTotal,
            'billingTotalPages' => $billingTotalPages,
            'paymentTotalPages' => $paymentTotalPages,
        ]);
    }

    public function previousBillingPage(): void
    {
        $this->billingPage = max(1, $this->billingPage - 1);
    }

    public function nextBillingPage(): void
    {
        $this->billingPage++;
    }

    public function goToBillingPage(int $page): void
    {
        $this->billingPage = max(1, min($page, $this->billingTotalPages()));
    }

    public function previousPaymentPage(): void
    {
        $this->paymentPage = max(1, $this->paymentPage - 1);
    }

    public function nextPaymentPage(): void
    {
        $this->paymentPage++;
    }

    public function goToPaymentPage(int $page): void
    {
        $this->paymentPage = max(1, min($page, $this->paymentTotalPages()));
    }

    protected function billingTotalPages(): int
    {
        return max(1, (int) ceil($this->customer->billings->count() / $this->perPage));
    }

    protected function paymentTotalPages(): int
    {
        return max(1, (int) ceil($this->customer->payments()->count() / $this->perPage));
    }

    protected function statusLabel(Billing $billing): string
    {
        $amountPaid = (float) $billing->paymentAllocations->sum('amount');
        $balance = max(0, (float) $billing->amount_due - $amountPaid);

        if ($balance <= 0) {
            return 'Paid';
        }

        if ($amountPaid > 0) {
            return 'Partial';
        }

        return $billing->due_date && Carbon::parse($billing->due_date)->lt(today()) ? 'Overdue' : 'Due';
    }
}
