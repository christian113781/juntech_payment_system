<?php

namespace App\Livewire\Admin\Area;

use App\Models\Area;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Areas'])]
class Index extends Component
{
    public array $areas = [];

    public string $search = '';

    public int $perPage = 10;

    public int $currentPage = 1;

    public int $totalAreas = 0;

    public ?int $deleteAreaId = null;

    public bool $showCreateModal = false;

    public bool $showEditModal = false;

    public ?int $editingAreaId = null;

    public string $areaCode = '';

    public string $areaName = '';

    public function mount(): void
    {
        $this->loadAreas();
    }

    public function loadAreas(): void
    {
        $query = Area::query()->orderBy('name');

        if ($this->search !== '') {
            $needle = strtolower(trim($this->search));

            $query->where(function ($q) use ($needle) {
                $q->whereRaw('LOWER(code) LIKE ?', ['%' . $needle . '%'])
                    ->orWhereRaw('LOWER(name) LIKE ?', ['%' . $needle . '%']);
            });
        }

        $this->areas = $query->get()->map(function (Area $area) {
            return [
                'id' => $area->id,
                'code' => $area->code,
                'name' => $area->name,
            ];
        })->toArray();

        $this->totalAreas = count($this->areas);
        $this->perPage = max(1, $this->perPage);

        $totalPages = max(1, (int) ceil($this->totalAreas / $this->perPage));
        $this->currentPage = max(1, min((int) $this->currentPage, $totalPages));

        $offset = ($this->currentPage - 1) * $this->perPage;
        $this->areas = array_slice($this->areas, $offset, $this->perPage);
    }

    public function updatedSearch(): void
    {
        $this->currentPage = 1;
        $this->loadAreas();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function openEditModal(int $areaId): void
    {
        $area = Area::findOrFail($areaId);

        $this->editingAreaId = $area->id;
        $this->areaCode = $area->code;
        $this->areaName = $area->name;
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editingAreaId = null;
        $this->resetForm();
    }

    public function saveArea(): void
    {
        $this->validate([
            'areaCode' => ['required', 'string', 'max:50'],
            'areaName' => ['required', 'string', 'max:255'],
        ]);

        if ($this->showEditModal && $this->editingAreaId) {
            $area = Area::findOrFail($this->editingAreaId);
            $area->update([
                'code' => trim($this->areaCode),
                'name' => trim($this->areaName),
            ]);

            session()->flash('success', 'Area updated successfully.');
            $this->dispatch('toast', message: 'Area updated successfully.');
        } else {
            Area::create([
                'code' => trim($this->areaCode),
                'name' => trim($this->areaName),
            ]);

            session()->flash('success', 'Area created successfully.');
            $this->dispatch('toast', message: 'Area created successfully.');
        }

        $this->closeCreateModal();
        $this->closeEditModal();
        $this->loadAreas();
    }

    public function openDeleteModal(int $areaId): void
    {
        $this->deleteAreaId = $areaId;
    }

    public function closeDeleteModal(): void
    {
        $this->deleteAreaId = null;
    }

    public function confirmDelete(): void
    {
        if (! $this->deleteAreaId) {
            return;
        }

        $area = Area::find($this->deleteAreaId);

        if ($area) {
            $area->delete();
            session()->flash('success', 'Area deleted successfully.');
            $this->dispatch('toast', message: 'Area deleted successfully.');
        }

        $this->deleteAreaId = null;
        $this->loadAreas();
    }

    public function previousPage(): void
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
            $this->loadAreas();
        }
    }

    public function nextPage(): void
    {
        $totalPages = max(1, (int) ceil($this->totalAreas / $this->perPage));

        if ($this->currentPage < $totalPages) {
            $this->currentPage++;
            $this->loadAreas();
        }
    }

    public function goToPage(int $page): void
    {
        $totalPages = max(1, (int) ceil($this->totalAreas / $this->perPage));
        $this->currentPage = max(1, min($page, $totalPages));
        $this->loadAreas();
    }

    protected function resetForm(): void
    {
        $this->areaCode = '';
        $this->areaName = '';
        $this->editingAreaId = null;
    }

    public function render()
    {
        $totalPages = max(1, (int) ceil($this->totalAreas / $this->perPage));
        $startItem = $this->totalAreas === 0 ? 0 : (($this->currentPage - 1) * $this->perPage) + 1;
        $endItem = min($this->currentPage * $this->perPage, $this->totalAreas);

        return view('livewire.admin.area.index', [
            'areas' => $this->areas,
            'totalAreas' => $this->totalAreas,
            'currentPage' => $this->currentPage,
            'startItem' => $startItem,
            'endItem' => $endItem,
            'totalPages' => $totalPages,
            'deleteArea' => $this->deleteAreaId ? Area::find($this->deleteAreaId) : null,
        ]);
    }
}
