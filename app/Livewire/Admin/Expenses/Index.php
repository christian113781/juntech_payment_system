<?php

namespace App\Livewire\Admin\Expenses;

use App\Models\CompanyExpense;
use App\Models\ExpenseCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Expenses'])]
class Index extends Component
{
    public array $expenses = [];

    public array $categories = [];

    public string $search = '';

    public string $dateRange = 'All Time';

    public string $categoryFilter = 'All Categories';

    public int $currentPage = 1;

    public int $perPage = 10;

    public int $totalExpenses = 0;

    public bool $showModal = false;

    public bool $showDeleteModal = false;

    public ?int $editingExpenseId = null;

    public ?int $deletingExpenseId = null;

    public string $expenseDate = '';

    public string $categoryId = '';

    public string $description = '';

    public string $amount = '';

    public string $paymentMethod = 'cash';

    public string $referenceNumber = '';

    public string $remarks = '';

    public function mount(): void
    {
        $this->expenseDate = today()->toDateString();
        $this->loadExpenses();
    }

    public function loadExpenses(): void
    {
        $query = CompanyExpense::query()->with('category')->orderByDesc('expense_date')->orderByDesc('id');

        if ($this->search !== '') {
            $needle = strtolower(trim($this->search));

            $query->where(function ($q) use ($needle) {
                $q->whereRaw('LOWER(description) LIKE ?', ['%' . $needle . '%'])
                    ->orWhereRaw('LOWER(reference_number) LIKE ?', ['%' . $needle . '%'])
                    ->orWhereHas('category', function ($categoryQuery) use ($needle) {
                        $categoryQuery->whereRaw('LOWER(name) LIKE ?', ['%' . $needle . '%']);
                    });
            });
        }

        if ($this->categoryFilter !== 'All Categories') {
            $query->whereHas('category', function ($q) {
                $q->where('name', $this->categoryFilter);
            });
        }

        if ($this->dateRange !== 'All Time') {
            $query->when($this->dateRange === 'This Month', function ($q) {
                $q->whereMonth('expense_date', now()->month)->whereYear('expense_date', now()->year);
            })->when($this->dateRange === 'Last Month', function ($q) {
                $q->whereMonth('expense_date', now()->subMonth()->month)->whereYear('expense_date', now()->subMonth()->year);
            })->when($this->dateRange === 'This Year', function ($q) {
                $q->whereYear('expense_date', now()->year);
            });
        }

        $this->expenses = $query->get()->map(function (CompanyExpense $expense) {
            return [
                'id' => $expense->id,
                'date' => $expense->expense_date?->format('Y-m-d'),
                'description' => $expense->description,
                'category' => $expense->category?->name ?? 'Other',
                'payment_method' => ucfirst($expense->payment_method),
                'amount' => (float) $expense->amount,
                'reference' => $expense->reference_number,
                'remarks' => $expense->remarks,
                'category_id' => $expense->expense_category_id,
            ];
        })->toArray();

        $this->totalExpenses = count($this->expenses);
        $this->categories = ExpenseCategory::query()->orderBy('name')->pluck('name')->toArray();

        $totalPages = max(1, (int) ceil($this->totalExpenses / $this->perPage));
        $this->currentPage = max(1, min($this->currentPage, $totalPages));

        $offset = ($this->currentPage - 1) * $this->perPage;
        $this->expenses = array_slice($this->expenses, $offset, $this->perPage);
    }

    public function updatedSearch(): void
    {
        $this->currentPage = 1;
        $this->loadExpenses();
    }

    public function updatedCategoryFilter(): void
    {
        $this->currentPage = 1;
        $this->loadExpenses();
    }

    public function updatedDateRange(): void
    {
        $this->currentPage = 1;
        $this->loadExpenses();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal(int $expenseId): void
    {
        $expense = CompanyExpense::findOrFail($expenseId);

        $this->editingExpenseId = $expense->id;
        $this->expenseDate = $expense->expense_date?->format('Y-m-d') ?? today()->toDateString();
        $this->categoryId = (string) ($expense->expense_category_id ?? '');
        $this->description = $expense->description;
        $this->amount = (string) $expense->amount;
        $this->paymentMethod = $expense->payment_method;
        $this->referenceNumber = $expense->reference_number ?? '';
        $this->remarks = $expense->remarks ?? '';
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function saveExpense(): void
    {
        $this->validate([
            'expenseDate' => ['required', 'date'],
            'categoryId' => ['required', 'exists:expense_categories,id'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'paymentMethod' => ['required', 'in:cash,gcash,bank'],
            'referenceNumber' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
        ]);

        $payload = [
            'expense_category_id' => (int) $this->categoryId,
            'expense_date' => $this->expenseDate,
            'description' => trim($this->description),
            'amount' => (float) $this->amount,
            'payment_method' => $this->paymentMethod,
            'reference_number' => trim($this->referenceNumber) !== '' ? trim($this->referenceNumber) : null,
            'remarks' => trim($this->remarks) !== '' ? trim($this->remarks) : null,
            'created_by' => auth()->id(),
        ];

        if ($this->editingExpenseId) {
            $expense = CompanyExpense::findOrFail($this->editingExpenseId);
            $expense->update($payload);
            session()->flash('success', 'Expense updated successfully.');
            $this->dispatch('toast', message: 'Expense updated successfully.');
        } else {
            CompanyExpense::create($payload);
            session()->flash('success', 'Expense created successfully.');
            $this->dispatch('toast', message: 'Expense created successfully.');
        }

        $this->closeModal();
        $this->loadExpenses();
    }

    public function openDeleteModal(int $expenseId): void
    {
        $this->deletingExpenseId = $expenseId;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingExpenseId = null;
    }

    public function confirmDelete(): void
    {
        if (! $this->deletingExpenseId) {
            return;
        }

        $expense = CompanyExpense::find($this->deletingExpenseId);

        if ($expense) {
            $expense->delete();
            session()->flash('success', 'Expense deleted successfully.');
            $this->dispatch('toast', message: 'Expense deleted successfully.');
        }

        $this->closeDeleteModal();
        $this->loadExpenses();
    }

    public function previousPage(): void
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
            $this->loadExpenses();
        }
    }

    public function nextPage(): void
    {
        $totalPages = max(1, (int) ceil($this->totalExpenses / $this->perPage));

        if ($this->currentPage < $totalPages) {
            $this->currentPage++;
            $this->loadExpenses();
        }
    }

    public function goToPage(int $page): void
    {
        $totalPages = max(1, (int) ceil($this->totalExpenses / $this->perPage));
        $this->currentPage = max(1, min($page, $totalPages));
        $this->loadExpenses();
    }

    protected function resetForm(): void
    {
        $this->editingExpenseId = null;
        $this->expenseDate = today()->toDateString();
        $this->categoryId = '';
        $this->description = '';
        $this->amount = '';
        $this->paymentMethod = 'cash';
        $this->referenceNumber = '';
        $this->remarks = '';
    }

    public function render()
    {
        $totalPages = max(1, (int) ceil($this->totalExpenses / $this->perPage));
        $startItem = $this->totalExpenses === 0 ? 0 : (($this->currentPage - 1) * $this->perPage) + 1;
        $endItem = min($this->currentPage * $this->perPage, $this->totalExpenses);

        $filteredTotal = array_sum(array_map(fn (array $expense) => $expense['amount'], $this->expenses));
        $topCategory = '';

        if (! empty($this->expenses)) {
            $totals = [];

            foreach ($this->expenses as $expense) {
                $totals[$expense['category']] = ($totals[$expense['category']] ?? 0) + $expense['amount'];
            }

            if ($totals !== []) {
                arsort($totals);
                $topCategory = (string) array_key_first($totals);
            }
        }

        $averageExpense = $this->totalExpenses > 0 ? $filteredTotal / $this->totalExpenses : 0;

        return view('livewire.admin.expenses.index', [
            'expenses' => $this->expenses,
            'totalExpenses' => $this->totalExpenses,
            'currentPage' => $this->currentPage,
            'totalPages' => $totalPages,
            'startItem' => $startItem,
            'endItem' => $endItem,
            'filteredTotal' => $filteredTotal,
            'topCategory' => $topCategory ?: '—',
            'averageExpense' => $averageExpense,
            'deleteExpense' => $this->deletingExpenseId ? CompanyExpense::with('category')->find($this->deletingExpenseId) : null,
        ]);
    }
}
