<?php

namespace App\Livewire\Admin\VendoUnit;

use App\Models\VendoPartner;
use App\Models\VendoUnit;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Vendo Units'])]
class Index extends Component
{
    public array $units = [];

    public array $availablePartners = [];

    public string $search = '';

    public int $perPage = 10;

    public int $currentPage = 1;

    public int $totalUnits = 0;

    public ?int $deleteUnitId = null;

    public ?string $deleteErrorMessage = null;

    public bool $showCreateModal = false;

    public bool $showEditModal = false;

    public bool $showViewModal = false;

    public bool $showAssignModal = false;

    public bool $showBulkCreateModal = false;

    public ?int $editingUnitId = null;

    public ?int $viewingUnitId = null;

    public ?int $assigningUnitId = null;

    public ?int $assignPartnerId = null;

    public ?string $assignError = null;

    public string $unitName = '';

    public string $unitKey = '';

    public string $unitStatus = 'ready';

    public string $unitDescription = '';

    public string $unitConditionNotes = '';

    public array $bulkUnits = [];

    public string $bulkNamePrefix = '';

    public string $bulkKeyPrefix = '';

    public string $bulkStartNumber = '001';

    public int $bulkQuantity = 1;

    public function mount(): void
    {
        $this->loadUnits();
    }

    public function loadUnits(): void
    {
        $query = VendoUnit::query()
            ->with(['partner' => fn ($query) => $query->with('area')])
            ->orderBy('name');

        if ($this->search !== '') {
            $needle = strtolower(trim($this->search));

            $query->where(function ($q) use ($needle) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . $needle . '%'])
                    ->orWhereRaw('LOWER(key) LIKE ?', ['%' . $needle . '%'])
                    ->orWhereRaw('LOWER(status) LIKE ?', ['%' . $needle . '%'])
                    ->orWhereHas('partner', function ($partnerQuery) use ($needle) {
                        $partnerQuery->whereRaw('LOWER(name) LIKE ?', ['%' . $needle . '%']);
                    });
            });
        }

        $this->units = $query->get()->map(function (VendoUnit $unit) {
            $partner = $unit->partner;

            return [
                'id' => $unit->id,
                'name' => $unit->name,
                'key' => $unit->key,
                'status' => $unit->status,
                'status_label' => ucfirst($unit->status),
                'description' => $unit->description ?: 'No description',
                'condition_notes' => $unit->condition_notes ?: 'No notes',
                'partner_id' => $partner?->id,
                'partner_name' => $partner?->name,
                'partner_area' => $partner?->area?->name,
                'partner_count' => $partner ? 1 : 0,
            ];
        })->toArray();

        $this->totalUnits = count($this->units);
        $this->perPage = max(1, $this->perPage);

        $totalPages = max(1, (int) ceil($this->totalUnits / $this->perPage));
        $this->currentPage = max(1, min((int) $this->currentPage, $totalPages));

        $offset = ($this->currentPage - 1) * $this->perPage;
        $this->units = array_slice($this->units, $offset, $this->perPage);
    }

    public function updatedSearch(): void
    {
        $this->currentPage = 1;
        $this->loadUnits();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openBulkCreateModal(): void
    {
        $this->bulkUnits = [];
        $this->bulkNamePrefix = '';
        $this->bulkKeyPrefix = '';
        $this->bulkStartNumber = '001';
        $this->bulkQuantity = 1;
        $this->resetValidation();
        $this->showBulkCreateModal = true;
    }

    public function closeBulkCreateModal(): void
    {
        $this->showBulkCreateModal = false;
        $this->bulkUnits = [];
        $this->bulkNamePrefix = '';
        $this->bulkKeyPrefix = '';
        $this->bulkStartNumber = '001';
        $this->bulkQuantity = 1;
        $this->resetValidation();
    }

    public function generateBulkUnits(): void
    {
        $this->validate([
            'bulkNamePrefix' => ['required', 'string', 'max:240'],
            'bulkKeyPrefix' => ['required', 'string', 'max:240'],
            'bulkStartNumber' => ['required', 'regex:/^\d{1,9}$/'],
            'bulkQuantity' => ['required', 'integer', 'min:1', 'max:500'],
        ]);

        $numberWidth = strlen($this->bulkStartNumber);
        $startNumber = (int) $this->bulkStartNumber;
        $this->bulkUnits = collect(range($startNumber, $startNumber + $this->bulkQuantity - 1))
            ->map(fn (int $number) => [
                'name' => trim($this->bulkNamePrefix) . str_pad((string) $number, $numberWidth, '0', STR_PAD_LEFT),
                'key' => trim($this->bulkKeyPrefix) . str_pad((string) $number, $numberWidth, '0', STR_PAD_LEFT),
            ])
            ->all();
        $this->resetValidation();
    }

    public function addBulkUnitRow(): void
    {
        $this->bulkUnits[] = ['name' => '', 'key' => ''];
    }

    public function removeBulkUnitRow(int $index): void
    {
        if (count($this->bulkUnits) <= 1) {
            return;
        }

        unset($this->bulkUnits[$index]);
        $this->bulkUnits = array_values($this->bulkUnits);
    }

    public function saveBulkUnits(): void
    {
        $this->validate([
            'bulkUnits' => ['required', 'array', 'min:1'],
            'bulkUnits.*.name' => ['required', 'string', 'max:255', 'distinct', 'unique:vendo_units,name'],
            'bulkUnits.*.key' => ['required', 'string', 'max:255', 'distinct', 'unique:vendo_units,key'],
        ]);

        DB::transaction(function (): void {
            foreach ($this->bulkUnits as $bulkUnit) {
                VendoUnit::create([
                    'name' => trim($bulkUnit['name']),
                    'key' => trim($bulkUnit['key']),
                    'status' => 'ready',
                ]);
            }
        });

        $count = count($this->bulkUnits);
        $message = $count . ' vendo unit' . ($count === 1 ? '' : 's') . ' added successfully.';
        session()->flash('success', $message);
        $this->dispatch('toast', message: $message);
        $this->closeBulkCreateModal();
        $this->loadUnits();
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function openViewModal(int $unitId): void
    {
        $unit = VendoUnit::with(['partner' => fn ($q) => $q->with('area')])->findOrFail($unitId);

        $this->viewingUnitId = $unit->id;
        $this->editingUnitId = null;
        $this->unitName = $unit->name;
        $this->unitKey = $unit->key ?? '';
        $this->unitStatus = $unit->status;
        $this->unitDescription = $unit->description ?? '';
        $this->unitConditionNotes = $unit->condition_notes ?? '';
        $this->showViewModal = true;
        $this->showEditModal = false;
    }

    public function closeViewModal(): void
    {
        $this->showViewModal = false;
        $this->viewingUnitId = null;
        $this->resetForm();
    }

    public function openEditModal(int $unitId): void
    {
        $unit = VendoUnit::findOrFail($unitId);

        $this->editingUnitId = $unit->id;
        $this->viewingUnitId = null;
        $this->unitName = $unit->name;
        $this->unitKey = $unit->key ?? '';
        $this->unitStatus = $unit->status;
        $this->unitDescription = $unit->description ?? '';
        $this->unitConditionNotes = $unit->condition_notes ?? '';
        $this->showEditModal = true;
        $this->showViewModal = false;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editingUnitId = null;
        $this->resetForm();
    }

    public function saveUnit(): void
    {
        if (! $this->showEditModal || ! $this->editingUnitId) {
            $this->unitStatus = 'ready';
        }

        $this->validate([
            'unitName' => ['required', 'string', 'max:255', $this->showEditModal && $this->editingUnitId ? 'unique:vendo_units,name,' . $this->editingUnitId : 'unique:vendo_units,name'],
            'unitKey' => ['nullable', 'string', 'max:255', $this->showEditModal && $this->editingUnitId ? 'unique:vendo_units,key,' . $this->editingUnitId : 'unique:vendo_units,key'],
            'unitStatus' => ['nullable', 'in:ready,assigned,repair,retired'],
            'unitDescription' => ['nullable', 'string'],
            'unitConditionNotes' => ['nullable', 'string'],
        ]);

        if ($this->showEditModal && $this->editingUnitId) {
            $unit = VendoUnit::findOrFail($this->editingUnitId);
            $unit->update([
                'name' => trim($this->unitName),
                'key' => trim($this->unitKey) !== '' ? trim($this->unitKey) : null,
                'status' => $this->unitStatus,
                'description' => trim($this->unitDescription) !== '' ? trim($this->unitDescription) : null,
                'condition_notes' => trim($this->unitConditionNotes) !== '' ? trim($this->unitConditionNotes) : null,
            ]);

            session()->flash('success', 'Vendo unit updated successfully.');
            $this->dispatch('toast', message: 'Vendo unit updated successfully.');
        } else {
            VendoUnit::create([
                'name' => trim($this->unitName),
                'key' => trim($this->unitKey) !== '' ? trim($this->unitKey) : null,
                'status' => 'ready',
                'description' => trim($this->unitDescription) !== '' ? trim($this->unitDescription) : null,
                'condition_notes' => trim($this->unitConditionNotes) !== '' ? trim($this->unitConditionNotes) : null,
            ]);

            session()->flash('success', 'Vendo unit created successfully.');
            $this->dispatch('toast', message: 'Vendo unit created successfully.');
        }

        $this->closeCreateModal();
        $this->closeEditModal();
        $this->loadUnits();
    }

    public function openAssignModal(int $unitId): void
    {
        $unit = VendoUnit::findOrFail($unitId);

        $this->assigningUnitId = $unit->id;
        $this->assignPartnerId = null;
        $this->assignError = null;
        $this->availablePartners = VendoPartner::query()
            ->whereNull('vendo_unit_id')
            ->with('area')
            ->orderBy('name')
            ->get()
            ->map(function (VendoPartner $partner) {
                return [
                    'id' => $partner->id,
                    'name' => $partner->name,
                    'area' => $partner->area?->name ?? 'No area',
                ];
            })
            ->toArray();
        $this->showAssignModal = true;
    }

    public function closeAssignModal(): void
    {
        $this->showAssignModal = false;
        $this->assigningUnitId = null;
        $this->assignPartnerId = null;
        $this->assignError = null;
    }

    public function confirmAssign(): void
    {
        if (! $this->assigningUnitId || ! $this->assignPartnerId) {
            $this->assignError = 'Please select a partner.';
            return;
        }

        $unit = VendoUnit::with('partner')->findOrFail($this->assigningUnitId);
        $partner = VendoPartner::findOrFail($this->assignPartnerId);

        if ($unit->partner) {
            $unit->partner()->update(['vendo_unit_id' => null]);
        }

        $partner->update(['vendo_unit_id' => $unit->id]);
        $unit->update(['status' => 'assigned']);

        session()->flash('success', $unit->name . ' assigned to ' . $partner->name . '.');
        $this->dispatch('toast', message: $unit->name . ' assigned to ' . $partner->name . '.');

        $this->closeAssignModal();
        $this->loadUnits();
    }

    public function unassignUnit(int $unitId): void
    {
        $unit = VendoUnit::with('partner')->findOrFail($unitId);

        if ($unit->partner) {
            $unit->partner()->update(['vendo_unit_id' => null]);
        }

        $unit->update(['status' => 'ready']);

        session()->flash('success', $unit->name . ' was unassigned and marked as ready.');
        $this->dispatch('toast', message: $unit->name . ' was unassigned and marked as ready.');
        $this->loadUnits();
    }

    public function markRepair(int $unitId): void
    {
        $unit = VendoUnit::with('partner')->findOrFail($unitId);

        if ($unit->partner) {
            $unit->partner()->update(['vendo_unit_id' => null]);
        }

        $unit->update(['status' => 'repair']);

        session()->flash('success', $unit->name . ' marked as repair.');
        $this->dispatch('toast', message: $unit->name . ' marked as repair.');
        $this->loadUnits();
    }

    public function markReady(int $unitId): void
    {
        $unit = VendoUnit::with('partner')->findOrFail($unitId);

        if ($unit->partner) {
            $unit->partner()->update(['vendo_unit_id' => null]);
        }

        $unit->update(['status' => 'ready']);

        session()->flash('success', $unit->name . ' marked as ready.');
        $this->dispatch('toast', message: $unit->name . ' marked as ready.');
        $this->loadUnits();
    }

    public function retireUnit(int $unitId): void
    {
        $unit = VendoUnit::with('partner')->findOrFail($unitId);

        if ($unit->partner) {
            $unit->partner()->update(['vendo_unit_id' => null]);
        }

        $unit->update(['status' => 'retired']);

        session()->flash('success', $unit->name . ' has been retired.');
        $this->dispatch('toast', message: $unit->name . ' has been retired.');
        $this->loadUnits();
    }

    public function openDeleteModal(int $unitId): void
    {
        $this->deleteUnitId = $unitId;
        $this->deleteErrorMessage = null;
    }

    public function closeDeleteModal(): void
    {
        $this->deleteUnitId = null;
        $this->deleteErrorMessage = null;
    }

    public function confirmDelete(): void
    {
        if (! $this->deleteUnitId) {
            return;
        }

        $unit = VendoUnit::withCount('partner')->find($this->deleteUnitId);

        if ($unit && $unit->partner_count > 0) {
            $this->deleteErrorMessage = 'This vendo unit cannot be deleted because it is still assigned to a vendo partner.';
            session()->flash('error', $this->deleteErrorMessage);
            $this->dispatch('toast', message: $this->deleteErrorMessage);
            $this->deleteUnitId = null;
            return;
        }

        if ($unit) {
            $unit->delete();
            session()->flash('success', 'Vendo unit deleted successfully.');
            $this->dispatch('toast', message: 'Vendo unit deleted successfully.');
        }

        $this->deleteUnitId = null;
        $this->deleteErrorMessage = null;
        $this->loadUnits();
    }

    public function previousPage(): void
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
            $this->loadUnits();
        }
    }

    public function nextPage(): void
    {
        $totalPages = max(1, (int) ceil($this->totalUnits / $this->perPage));

        if ($this->currentPage < $totalPages) {
            $this->currentPage++;
            $this->loadUnits();
        }
    }

    public function goToPage(int $page): void
    {
        $totalPages = max(1, (int) ceil($this->totalUnits / $this->perPage));
        $this->currentPage = max(1, min($page, $totalPages));
        $this->loadUnits();
    }

    protected function resetForm(): void
    {
        $this->unitName = '';
        $this->unitKey = '';
        $this->unitStatus = 'ready';
        $this->unitDescription = '';
        $this->unitConditionNotes = '';
        $this->editingUnitId = null;
        $this->viewingUnitId = null;
    }

    public function render()
    {
        $totalPages = max(1, (int) ceil($this->totalUnits / $this->perPage));
        $startItem = $this->totalUnits === 0 ? 0 : (($this->currentPage - 1) * $this->perPage) + 1;
        $endItem = min($this->currentPage * $this->perPage, $this->totalUnits);

        return view('livewire.admin.vendo-unit.index', [
            'units' => $this->units,
            'totalUnits' => $this->totalUnits,
            'currentPage' => $this->currentPage,
            'startItem' => $startItem,
            'endItem' => $endItem,
            'totalPages' => $totalPages,
            'deleteUnit' => $this->deleteUnitId ? VendoUnit::find($this->deleteUnitId) : null,
        ]);
    }
}

