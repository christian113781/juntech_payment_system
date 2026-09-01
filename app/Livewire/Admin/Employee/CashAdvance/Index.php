<?php

namespace App\Livewire\Admin\Employee\CashAdvance;

use App\Models\Employee;
use App\Models\EmployeeCashAdvance;
use App\Models\EmployeeCashAdvancePayment;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Cash Advances'])]
class Index extends Component
{
    public Employee $employee;

    public $allAdvances;

    public int $perPage = 10;

    public int $currentPage = 1;

    public int $totalCashAdvances = 0;

    public int $totalPages = 1;

    public int $startItem = 0;

    public int $endItem = 0;

    public $advanceId = null;

    public string $advanceDate = '';

    public string $amount = '';

    public bool $showEditModal = false;

    public bool $showDeleteModal = false;

    public ?int $deletingAdvanceId = null;

    public string $search = '';

    public function mount(Employee $employee): void
    {
        $this->employee = $employee;
        $this->advanceDate = today()->toDateString();
        $this->refreshAdvances();
    }

    public function updatedSearch(): void
    {
        $this->currentPage = 1;
    }

    public function refreshAdvances(): void
    {
        $this->allAdvances = $this->employee->cashAdvances()->orderByDesc('advance_date')->orderByDesc('id')->get()->map(function (EmployeeCashAdvance $advance) {
            $paidAmount = $advance->payments()->sum('amount');
            $balance = max(0, (float) $advance->amount - (float) $paidAmount);

            $advance->amount_paid = $paidAmount;
            $advance->balance = $balance;
            $advance->save();

            return $advance;
        });
    }

    public function openCreateModal(): void
    {
        $this->showEditModal = true;
        $this->advanceId = null;
        $this->advanceDate = today()->toDateString();
        $this->amount = '';
    }

    public function openEditModal(int $advanceId): void
    {
        $advance = $this->employee->cashAdvances()->findOrFail($advanceId);

        $this->advanceId = $advance->id;
        $this->advanceDate = $advance->advance_date?->format('Y-m-d') ?? today()->toDateString();
        $this->amount = (string) $advance->amount;
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->advanceId = null;
        $this->advanceDate = today()->toDateString();
        $this->amount = '';
    }

    public function openDeleteModal(int $advanceId): void
    {
        $this->deletingAdvanceId = $advanceId;
        $this->showDeleteModal = true;
    }

    public function deleteAdvance(int $advanceId): void
    {
        $this->openDeleteModal($advanceId);
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingAdvanceId = null;
    }

    public function saveAdvance(): void
    {
        $this->validate([
            'advanceDate' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $payload = [
            'employee_id' => $this->employee->id,
            'advance_date' => $this->advanceDate,
            'amount' => (float) $this->amount,
            'amount_paid' => 0,
            'balance' => (float) $this->amount,
        ];

        if ($this->advanceId) {
            $advance = $this->employee->cashAdvances()->findOrFail($this->advanceId);
            $paidSoFar = $advance->payments()->sum('amount');
            $payload['amount_paid'] = (float) $paidSoFar;
            $payload['balance'] = max(0, (float) $this->amount - (float) $paidSoFar);
            $advance->update($payload);
            session()->flash('success', 'Cash advance updated successfully.');
            $this->dispatch('toast', message: 'Cash advance updated successfully.');
        } else {
            EmployeeCashAdvance::create($payload);
            session()->flash('success', 'Cash advance recorded successfully.');
            $this->dispatch('toast', message: 'Cash advance recorded successfully.');
        }

        $this->closeEditModal();
        $this->refreshAdvances();
    }

    public function confirmDelete(): void
    {
        if (! $this->deletingAdvanceId) {
            return;
        }

        $advance = $this->employee->cashAdvances()->find($this->deletingAdvanceId);

        if ($advance) {
            $advance->payments()->delete();
            $advance->delete();
            session()->flash('success', 'Cash advance deleted successfully.');
            $this->dispatch('toast', message: 'Cash advance deleted successfully.');
        }

        $this->closeDeleteModal();
        $this->refreshAdvances();
    }

    public function recordPayment(int $advanceId, float $amount, string $remarks = ''): void
    {
        $advance = $this->employee->cashAdvances()->findOrFail($advanceId);
        $paidSoFar = (float) $advance->payments()->sum('amount');
        $remaining = max(0, (float) $advance->amount - $paidSoFar);

        if ($amount <= 0 || $amount > $remaining) {
            session()->flash('error', 'Payment amount exceeds the available balance.');
            return;
        }

        EmployeeCashAdvancePayment::create([
            'cash_advance_id' => $advance->id,
            'amount' => $amount,
            'payment_date' => today()->toDateString(),
            'remarks' => trim($remarks) !== '' ? trim($remarks) : null,
        ]);

        $paidSoFar = (float) $advance->payments()->sum('amount');
        $balance = max(0, (float) $advance->amount - $paidSoFar);

        $advance->update([
            'amount_paid' => $paidSoFar,
            'balance' => $balance,
        ]);

        session()->flash('success', 'Payment recorded successfully.');
        $this->dispatch('toast', message: 'Payment recorded successfully.');
        $this->refreshAdvances();
    }

    public function previousPage(): void
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    public function nextPage(): void
    {
        if ($this->currentPage < $this->totalPages) {
            $this->currentPage++;
        }
    }

    public function goToPage(int $page): void
    {
        $this->currentPage = max(1, min($page, $this->totalPages));
    }

    public function render()
    {
        $totalAdvance = (float) $this->employee->cashAdvances()->sum('amount');
        $totalPaid = (float) $this->employee->cashAdvances()->with('payments')->get()->sum(function ($advance) {
            return (float) $advance->payments()->sum('amount');
        });
        $balance = max(0, $totalAdvance - $totalPaid);

        $query = $this->employee->cashAdvances()->with('payments')->orderByDesc('advance_date')->orderByDesc('id');

        $search = trim($this->search);
        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query->where('amount', 'like', "%{$search}%")
                    ->orWhereDate('advance_date', $search)
                    ->orWhere('remarks', 'like', "%{$search}%");
            });
        }

        $allAdvances = $query->get()->map(function (EmployeeCashAdvance $advance) {
            $paidAmount = (float) $advance->payments()->sum('amount');
            $advance->amount_paid = $paidAmount;
            $advance->balance = max(0, (float) $advance->amount - $paidAmount);

            return $advance;
        });

        $this->totalCashAdvances = $allAdvances->count();
        $this->totalPages = max(1, (int) ceil($this->totalCashAdvances / $this->perPage));
        $this->currentPage = max(1, min($this->currentPage, $this->totalPages));
        $this->startItem = $this->totalCashAdvances === 0 ? 0 : (($this->currentPage - 1) * $this->perPage) + 1;
        $this->endItem = min($this->currentPage * $this->perPage, $this->totalCashAdvances);

        $advances = $allAdvances->slice(($this->currentPage - 1) * $this->perPage, $this->perPage)->values();

        return view('livewire.admin.employee.cash-advance.index', [
            'employee' => $this->employee,
            'advances' => $advances,
            'totalAdvance' => $totalAdvance,
            'totalPaid' => $totalPaid,
            'balance' => $balance,
            'advanceCount' => $this->employee->cashAdvances()->count(),
            'activeBorrowers' => $this->employee->cashAdvances()->whereNotNull('amount')->count(),
            'deleteAdvance' => $this->deletingAdvanceId ? $this->employee->cashAdvances()->find($this->deletingAdvanceId) : null,
            'totalCashAdvances' => $this->totalCashAdvances,
            'currentPage' => $this->currentPage,
            'totalPages' => $this->totalPages,
            'startItem' => $this->startItem,
            'endItem' => $this->endItem,
        ]);
    }
}
