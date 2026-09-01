<?php

namespace App\Livewire\Admin\Employee;

use App\Models\Employee;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Employees'])]
class Index extends Component
{
    public array $employees = [];

    public string $search = '';

    public string $statusFilter = 'all';

    public int $perPage = 10;

    public int $currentPage = 1;

    public int $totalEmployees = 0;

    public bool $showModal = false;

    public bool $showDeleteModal = false;

    public ?int $editingEmployeeId = null;

    public ?int $deletingEmployeeId = null;

    public string $name = '';

    public string $position = '';

    public string $contactNumber = '';

    public string $dailyRate = '';

    public string $dateStarted = '';

    public string $status = 'active';

    public string $notes = '';

    public function mount(): void
    {
        $this->dateStarted = today()->toDateString();
        $this->loadEmployees();
    }

    public function loadEmployees(): void
    {
        $query = Employee::query()->orderByDesc('created_at')->orderByDesc('id');

        if ($this->search !== '') {
            $needle = strtolower(trim($this->search));

            $query->where(function ($q) use ($needle) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . $needle . '%'])
                    ->orWhereRaw('LOWER(position) LIKE ?', ['%' . $needle . '%'])
                    ->orWhereRaw('LOWER(contact_number) LIKE ?', ['%' . $needle . '%']);
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $this->employees = $query->get()->map(function (Employee $employee) {
            return [
                'id' => $employee->id,
                'name' => $employee->name,
                'position' => $employee->position ?? '—',
                'contact_number' => $employee->contact_number ?? '—',
                'daily_rate' => (float) $employee->daily_rate,
                'status' => ucfirst($employee->status ?? 'active'),
                'date_started' => $employee->date_started?->format('Y-m-d'),
                'notes' => $employee->notes ?? '',
                'initials' => strtoupper(substr($employee->name, 0, 1) . (str_contains($employee->name, ' ') ? substr(strrchr($employee->name, ' '), 1, 1) : '')),
            ];
        })->toArray();

        $this->totalEmployees = count($this->employees);
        $totalPages = max(1, (int) ceil($this->totalEmployees / $this->perPage));
        $this->currentPage = max(1, min($this->currentPage, $totalPages));

        $offset = ($this->currentPage - 1) * $this->perPage;
        $this->employees = array_slice($this->employees, $offset, $this->perPage);
    }

    public function updatedSearch(): void
    {
        $this->currentPage = 1;
        $this->loadEmployees();
    }

    public function updatedStatusFilter(): void
    {
        $this->currentPage = 1;
        $this->loadEmployees();
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

    public function openEditModal(int $employeeId): void
    {
        $employee = Employee::findOrFail($employeeId);

        $this->editingEmployeeId = $employee->id;
        $this->name = $employee->name;
        $this->position = $employee->position ?? '';
        $this->contactNumber = $employee->contact_number ?? '';
        $this->dailyRate = (string) $employee->daily_rate;
        $this->dateStarted = $employee->date_started?->format('Y-m-d') ?? today()->toDateString();
        $this->status = $employee->status ?? 'active';
        $this->notes = $employee->notes ?? '';

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function openDeleteModal(int $employeeId): void
    {
        $this->deletingEmployeeId = $employeeId;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingEmployeeId = null;
    }

    public function saveEmployee(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'contactNumber' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\s\-+()]*$/'],
            'dailyRate' => ['required', 'numeric', 'min:0'],
            'dateStarted' => ['required', 'date'],
            'status' => ['required', 'in:active,inactive'],
            'notes' => ['nullable', 'string'],
        ]);

        $payload = [
            'name' => trim($this->name),
            'position' => trim($this->position),
            'contact_number' => trim($this->contactNumber) !== '' ? trim($this->contactNumber) : null,
            'daily_rate' => (float) $this->dailyRate,
            'date_started' => $this->dateStarted,
            'status' => $this->status,
            'notes' => trim($this->notes) !== '' ? trim($this->notes) : null,
        ];

        if ($this->editingEmployeeId) {
            $employee = Employee::findOrFail($this->editingEmployeeId);
            $employee->update($payload);
            session()->flash('success', 'Employee updated successfully.');
            $this->dispatch('toast', message: 'Employee updated successfully.');
        } else {
            Employee::create($payload);
            session()->flash('success', 'Employee created successfully.');
            $this->dispatch('toast', message: 'Employee created successfully.');
        }

        $this->closeModal();
        $this->loadEmployees();
    }

    public function confirmDelete(): void
    {
        if (! $this->deletingEmployeeId) {
            return;
        }

        $employee = Employee::find($this->deletingEmployeeId);

        if ($employee) {
            $employee->delete();
            session()->flash('success', 'Employee deleted successfully.');
            $this->dispatch('toast', message: 'Employee deleted successfully.');
        }

        $this->closeDeleteModal();
        $this->loadEmployees();
    }

    public function previousPage(): void
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
            $this->loadEmployees();
        }
    }

    public function nextPage(): void
    {
        $totalPages = max(1, (int) ceil($this->totalEmployees / $this->perPage));

        if ($this->currentPage < $totalPages) {
            $this->currentPage++;
            $this->loadEmployees();
        }
    }

    public function goToPage(int $page): void
    {
        $totalPages = max(1, (int) ceil($this->totalEmployees / $this->perPage));
        $this->currentPage = max(1, min($page, $totalPages));
        $this->loadEmployees();
    }

    protected function resetForm(): void
    {
        $this->editingEmployeeId = null;
        $this->name = '';
        $this->position = '';
        $this->contactNumber = '';
        $this->dailyRate = '';
        $this->dateStarted = today()->toDateString();
        $this->status = 'active';
        $this->notes = '';
    }

    public function render()
    {
        $totalPages = max(1, (int) ceil($this->totalEmployees / $this->perPage));
        $startItem = $this->totalEmployees === 0 ? 0 : (($this->currentPage - 1) * $this->perPage) + 1;
        $endItem = min($this->currentPage * $this->perPage, $this->totalEmployees);

        $activeCount = Employee::query()->where('status', 'active')->count();
        $inactiveCount = Employee::query()->where('status', 'inactive')->count();
        $avgRate = Employee::query()->avg('daily_rate') ?? 0;

        return view('livewire.admin.employee.index', [
            'employees' => $this->employees,
            'totalEmployees' => $this->totalEmployees,
            'currentPage' => $this->currentPage,
            'totalPages' => $totalPages,
            'startItem' => $startItem,
            'endItem' => $endItem,
            'activeCount' => $activeCount,
            'inactiveCount' => $inactiveCount,
            'avgRate' => $avgRate,
            'deleteEmployee' => $this->deletingEmployeeId ? Employee::find($this->deletingEmployeeId) : null,
        ]);
    }
}
