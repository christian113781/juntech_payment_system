<?php

namespace App\Livewire\Admin\Customer;

use App\Models\Area;
use App\Models\Billing;
use App\Models\Customer;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Customers'])]
class Index extends Component
{
    public array $customers = [];

    public $areaOptions = [];

    public string $search = '';

    public string $statusFilter = '';

    public string $areaFilter = '';

    public string $sortBy = 'name';

    public int $perPage = 50;

    public int $currentPage = 1;

    public int $totalCustomers = 0;

    public bool $showModal = false;

    public bool $showDeleteModal = false;

    public ?int $editingCustomerId = null;

    public ?int $deletingCustomerId = null;

    public string $name = '';

    public string $areaId = '';

    public string $contactNumber = '';

    public string $address = '';

    public string $monthlyPrice = '';

    public string $billingStartDate = '';

    public string $billingCycleDays = '32';

    public string $status = 'active';

    public string $remarks = '';

    public function mount(): void
    {
        $this->billingStartDate = today()->toDateString();
        $this->areaOptions = Area::query()->orderBy('name')->get();
        $this->loadCustomers();
    }

    public function loadCustomers(): void
    {
        $query = Customer::query()->with('area')->orderBy('name');

        if ($this->search !== '') {
            $needle = strtolower(trim($this->search));

            $query->where(function ($q) use ($needle) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . $needle . '%'])
                    ->orWhereRaw('LOWER(contact_number) LIKE ?', ['%' . $needle . '%'])
                    ->orWhereRaw('LOWER(address) LIKE ?', ['%' . $needle . '%'])
                    ->orWhereHas('area', fn ($areaQuery) => $areaQuery->whereRaw('LOWER(name) LIKE ?', ['%' . $needle . '%']));
            });
        }

        if ($this->statusFilter !== '') {
            $query->where('status', strtolower($this->statusFilter));
        }

        if ($this->areaFilter !== '') {
            $query->where('area_id', $this->areaFilter);
        }

        $customers = $query->get()->map(function (Customer $customer) {
            return [
                'id' => $customer->id,
                'name' => $customer->name,
                'area_id' => $customer->area_id,
                'area_name' => $customer->area?->name ?? '—',
                'contact_number' => $customer->contact_number ?? '',
                'address' => $customer->address ?? '',
                'monthly_price' => (float) $customer->monthly_price,
                'latest_billing_date' => $customer->latest_billing_date?->format('Y-m-d') ?? '',
                'latest_billing_formatted' => $customer->latest_billing_date ? $customer->latest_billing_date->format('M d, Y') : '—',
                'billing_cycle_days' => (int) $customer->billing_cycle_days,
                'status' => ucfirst($customer->status ?? 'active'),
                'remarks' => $customer->remarks ?? '',
                'initials' => strtoupper(substr($customer->name, 0, 1) . (str_contains($customer->name, ' ') ? substr(strrchr($customer->name, ' '), 1, 1) : '')),
            ];
        })->all();

        usort($customers, function (array $a, array $b): int {
            if ($this->sortBy === 'price-desc') {
                return $b['monthly_price'] <=> $a['monthly_price'];
            }

            if ($this->sortBy === 'due-asc') {
                $aDate = $a['latest_billing_date'] ?: '9999-12-31';
                $bDate = $b['latest_billing_date'] ?: '9999-12-31';

                return strcmp($aDate, $bDate);
            }

            return strcmp($a['name'], $b['name']);
        });

        $this->totalCustomers = count($customers);
        $totalPages = max(1, (int) ceil($this->totalCustomers / $this->perPage));
        $this->currentPage = max(1, min($this->currentPage, $totalPages));

        $offset = ($this->currentPage - 1) * $this->perPage;
        $this->customers = array_slice($customers, $offset, $this->perPage);
    }

    public function updatedSearch(): void
    {
        $this->currentPage = 1;
        $this->loadCustomers();
    }

    public function updatedStatusFilter(): void
    {
        $this->currentPage = 1;
        $this->loadCustomers();
    }

    public function updatedAreaFilter(): void
    {
        $this->currentPage = 1;
        $this->loadCustomers();
    }

    public function updatedContactNumber(): void
    {
        $this->contactNumber = preg_replace('/\D+/', '', $this->contactNumber) ?? '';
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal(int $customerId): void
    {
        $customer = Customer::findOrFail($customerId);

        $this->editingCustomerId = $customer->id;
        $this->name = $customer->name;
        $this->areaId = (string) $customer->area_id;
        $this->contactNumber = (string) ($customer->contact_number ?? '');
        $this->address = $customer->address ?? '';
        $this->monthlyPrice = (string) $customer->monthly_price;
        $latestBilling = $customer->billings()
            ->orderByDesc('period_start')
            ->orderByDesc('id')
            ->first();
        $this->billingStartDate = $latestBilling?->period_start?->format('Y-m-d')
            ?? $customer->latest_billing_date?->format('Y-m-d')
            ?? today()->toDateString();
        $this->billingCycleDays = (string) ($customer->billing_cycle_days ?? 32);
        $this->status = $customer->status ?? 'active';
        $this->remarks = $customer->remarks ?? '';
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function openDeleteModal(int $customerId): void
    {
        $this->deletingCustomerId = $customerId;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingCustomerId = null;
    }

    public function saveCustomer(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'areaId' => ['required', 'exists:areas,id'],
            'contactNumber' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\s\-+()]*$/'],
            'address' => ['nullable', 'string', 'max:255'],
            'monthlyPrice' => ['required', 'numeric', 'min:0'],
            'billingStartDate' => ['required', 'date'],
            'billingCycleDays' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:active,disconnected'],
            'remarks' => ['nullable', 'string'],
        ]);

        $payload = [
            'name' => trim($this->name),
            'area_id' => (int) $this->areaId,
            'contact_number' => trim($this->contactNumber) !== '' ? trim($this->contactNumber) : null,
            'address' => trim($this->address) !== '' ? trim($this->address) : null,
            'monthly_price' => (float) $this->monthlyPrice,
            'latest_billing_date' => $this->billingStartDate,
            'billing_cycle_days' => (int) $this->billingCycleDays,
            'status' => $this->status,
            'remarks' => trim($this->remarks) !== '' ? trim($this->remarks) : null,
        ];

        if ($this->editingCustomerId) {
            $customer = Customer::findOrFail($this->editingCustomerId);
            $customer->update($payload);
            $this->updateLatestBilling(
                $customer,
                (float) $payload['monthly_price'],
                Carbon::parse($payload['latest_billing_date']),
                (int) $payload['billing_cycle_days'],
            );
            session()->flash('success', 'Customer updated successfully.');
            $this->dispatch('toast', message: 'Customer updated successfully.');
        } else {
            $customer = Customer::create($payload);

            if ($customer->status === 'active') {
                $start = \Illuminate\Support\Carbon::parse($customer->latest_billing_date);
                $cycleDays = max(1, (int) $customer->billing_cycle_days);
                $end = $start->copy()->addDays($cycleDays - 1);

                \App\Models\Billing::create([
                    'customer_id' => $customer->id,
                    'period_start' => $start->toDateString(),
                    'period_end' => $end->toDateString(),
                    'amount_due' => $customer->monthly_price,
                    'amount_paid' => 0,
                    'balance' => $customer->monthly_price,
                    'due_date' => $end->toDateString(),
                    'status' => 'unpaid',
                    'remarks' => 'Auto-generated billing for new customer',
                ]);
            }

            session()->flash('success', 'Customer created successfully.');
            $this->dispatch('toast', message: 'Customer created successfully.');
        }

        $this->closeModal();
        $this->loadCustomers();
    }

    protected function updateLatestBilling(
        Customer $customer,
        float $monthlyPrice,
        Carbon $billingStartDate,
        int $billingCycleDays,
    ): void
    {
        $billings = $customer->billings()
            ->with('paymentAllocations')
            ->whereDate('period_end', '>=', today())
            ->orderByDesc('period_start')
            ->orderByDesc('id')
            ->limit(1)
            ->get();

        foreach ($billings as $index => $billing) {
            if ($index === 0) {
                $periodEnd = $billingStartDate->copy()->addDays($billingCycleDays - 1);
                $billing->period_start = $billingStartDate->toDateString();
                $billing->period_end = $periodEnd->toDateString();
                $billing->due_date = $periodEnd->toDateString();
            }

            $amountPaid = (float) $billing->paymentAllocations->sum('amount');
            $billing->amount_due = $monthlyPrice;
            $billing->amount_paid = round($amountPaid, 2);
            $billing->balance = round(max(0, $monthlyPrice - $amountPaid), 2);
            $billing->status = $this->resolveBillingStatus($billing, $amountPaid, $monthlyPrice);
            $billing->save();
        }
    }

    protected function resolveBillingStatus(Billing $billing, float $amountPaid, float $monthlyPrice): string
    {
        if ($amountPaid >= $monthlyPrice) {
            return 'paid';
        }

        if ($amountPaid > 0) {
            return 'partial';
        }

        return $billing->due_date && Carbon::parse($billing->due_date)->lt(today())
            ? 'overdue'
            : 'unpaid';
    }

    public function confirmDelete(): void
    {
        if (! $this->deletingCustomerId) {
            return;
        }

        $customer = Customer::find($this->deletingCustomerId);

        if ($customer) {
            $customer->delete();
            session()->flash('success', 'Customer deleted successfully.');
            $this->dispatch('toast', message: 'Customer deleted successfully.');
        }

        $this->closeDeleteModal();
        $this->loadCustomers();
    }

    public function previousPage(): void
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
            $this->loadCustomers();
        }
    }

    public function nextPage(): void
    {
        $totalPages = max(1, (int) ceil($this->totalCustomers / $this->perPage));

        if ($this->currentPage < $totalPages) {
            $this->currentPage++;
            $this->loadCustomers();
        }
    }

    public function goToPage(int $page): void
    {
        $totalPages = max(1, (int) ceil($this->totalCustomers / $this->perPage));
        $this->currentPage = max(1, min($page, $totalPages));
        $this->loadCustomers();
    }

    protected function resetForm(): void
    {
        $this->editingCustomerId = null;
        $this->name = '';
        $this->areaId = '';
        $this->contactNumber = '';
        $this->address = '';
        $this->monthlyPrice = '';
        $this->billingStartDate = today()->toDateString();
        $this->billingCycleDays = '32';
        $this->status = 'active';
        $this->remarks = '';
    }

    public function render()
    {
        $totalPages = max(1, (int) ceil($this->totalCustomers / $this->perPage));
        $startItem = $this->totalCustomers === 0 ? 0 : (($this->currentPage - 1) * $this->perPage) + 1;
        $endItem = min($this->currentPage * $this->perPage, $this->totalCustomers);

        $activeCount = Customer::query()->where('status', 'active')->count();
        $disconnectedCount = Customer::query()->where('status', 'disconnected')->count();
        $activeRevenue = Customer::query()->where('status', 'active')->sum('monthly_price');

        return view('livewire.admin.customer.index', [
            'customers' => $this->customers,
            'totalCustomers' => $this->totalCustomers,
            'currentPage' => $this->currentPage,
            'totalPages' => $totalPages,
            'startItem' => $startItem,
            'endItem' => $endItem,
            'activeCount' => $activeCount,
            'disconnectedCount' => $disconnectedCount,
            'activeRevenue' => $activeRevenue,
            'areaOptions' => $this->areaOptions ?: Area::query()->orderBy('name')->get(),
            'deleteCustomer' => $this->deletingCustomerId ? Customer::with('area')->find($this->deletingCustomerId) : null,
        ]);
    }
}

