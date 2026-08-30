<?php

namespace App\Livewire\Admin\Omada;

use App\Models\Area;
use App\Models\OmadaPartner;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Omada Partner'])]
class Index extends Component
{
    public array $partners = [];

    public array $areaOptions = [];

    public string $search = '';

    public string $areaFilter = '';

    public int $perPage = 10;

    public int $currentPage = 1;

    public int $totalPartners = 0;

    public ?int $deletePartnerId = null;

    public bool $showCreateModal = false;

    public bool $showEditModal = false;

    public ?int $editingPartnerId = null;

    public string $partnerName = '';

    public ?int $partnerAreaId = null;

    public string $partnerContactNumber = '';

    public string $partnerAddress = '';

    public function mount(): void
    {
        $this->areaOptions = Area::query()->orderBy('name')->get(['id', 'name'])->toArray();
        $this->loadPartners();
    }

    public function loadPartners(): void
    {
        $query = OmadaPartner::query()->with('area')->orderBy('name');

        if ($this->search !== '') {
            $needle = strtolower(trim($this->search));

            $query->where(function ($q) use ($needle) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . $needle . '%'])
                    ->orWhereRaw('LOWER(address) LIKE ?', ['%' . $needle . '%'])
                    ->orWhereRaw('LOWER(contact_number) LIKE ?', ['%' . $needle . '%'])
                    ->orWhereHas('area', function ($areaQuery) use ($needle) {
                        $areaQuery->whereRaw('LOWER(name) LIKE ?', ['%' . $needle . '%']);
                    });
            });
        }

        if ($this->areaFilter !== '') {
            $query->where('area_id', $this->areaFilter);
        }

        $this->partners = $query->get()->map(function (OmadaPartner $partner) {
            return [
                'id' => $partner->id,
                'name' => $partner->name,
                'area_id' => $partner->area_id,
                'area_name' => $partner->area?->name,
                'contact_number' => $partner->contact_number,
                'address' => $partner->address,
                'initials' => strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $partner->name) ?? '', 0, 2)),
            ];
        })->toArray();

        $this->totalPartners = count($this->partners);
        $this->perPage = max(1, $this->perPage);

        $totalPages = max(1, (int) ceil($this->totalPartners / $this->perPage));
        $this->currentPage = max(1, min((int) $this->currentPage, $totalPages));

        $offset = ($this->currentPage - 1) * $this->perPage;
        $this->partners = array_slice($this->partners, $offset, $this->perPage);
    }

    public function updatedSearch(): void
    {
        $this->currentPage = 1;
        $this->loadPartners();
    }

    public function updatedAreaFilter(): void
    {
        $this->currentPage = 1;
        $this->loadPartners();
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

    public function openEditModal(int $partnerId): void
    {
        $partner = OmadaPartner::findOrFail($partnerId);

        $this->editingPartnerId = $partner->id;
        $this->partnerName = $partner->name;
        $this->partnerAreaId = $partner->area_id;
        $this->partnerContactNumber = $partner->contact_number ?? '';
        $this->partnerAddress = $partner->address ?? '';
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editingPartnerId = null;
        $this->resetForm();
    }

    public function savePartner(): void
    {
        $this->partnerContactNumber = preg_replace('/\D+/', '', (string) $this->partnerContactNumber) ?? '';

        $this->validate([
            'partnerName' => ['required', 'string', 'max:255'],
            'partnerAreaId' => ['required', 'exists:areas,id'],
            'partnerContactNumber' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'partnerAddress' => ['nullable', 'string', 'max:255'],
        ]);

        if ($this->showEditModal && $this->editingPartnerId) {
            $partner = OmadaPartner::findOrFail($this->editingPartnerId);
            $partner->update([
                'name' => trim($this->partnerName),
                'area_id' => $this->partnerAreaId,
                'contact_number' => trim($this->partnerContactNumber),
                'address' => trim($this->partnerAddress),
            ]);

            session()->flash('success', 'Partner updated successfully.');
            $this->dispatch('toast', message: 'Partner updated successfully.');
        } else {
            OmadaPartner::create([
                'name' => trim($this->partnerName),
                'area_id' => $this->partnerAreaId,
                'contact_number' => trim($this->partnerContactNumber),
                'address' => trim($this->partnerAddress),
            ]);

            session()->flash('success', 'Partner created successfully.');
            $this->dispatch('toast', message: 'Partner created successfully.');
        }

        $this->closeCreateModal();
        $this->closeEditModal();
        $this->loadPartners();
    }

    public function openDeleteModal(int $partnerId): void
    {
        $this->deletePartnerId = $partnerId;
    }

    public function closeDeleteModal(): void
    {
        $this->deletePartnerId = null;
    }

    public function confirmDelete(): void
    {
        if (! $this->deletePartnerId) {
            return;
        }

        $partner = OmadaPartner::find($this->deletePartnerId);

        if ($partner) {
            $partner->delete();
            session()->flash('success', 'Partner deleted successfully.');
            $this->dispatch('toast', message: 'Partner deleted successfully.');
        }

        $this->deletePartnerId = null;
        $this->loadPartners();
    }

    public function previousPage(): void
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
            $this->loadPartners();
        }
    }

    public function nextPage(): void
    {
        $totalPages = max(1, (int) ceil($this->totalPartners / $this->perPage));

        if ($this->currentPage < $totalPages) {
            $this->currentPage++;
            $this->loadPartners();
        }
    }

    public function goToPage(int $page): void
    {
        $totalPages = max(1, (int) ceil($this->totalPartners / $this->perPage));
        $this->currentPage = max(1, min($page, $totalPages));
        $this->loadPartners();
    }

    protected function resetForm(): void
    {
        $this->partnerName = '';
        $this->partnerAreaId = null;
        $this->partnerContactNumber = '';
        $this->partnerAddress = '';
        $this->editingPartnerId = null;
    }

    public function render()
    {
        $totalPages = max(1, (int) ceil($this->totalPartners / $this->perPage));
        $startItem = $this->totalPartners === 0 ? 0 : (($this->currentPage - 1) * $this->perPage) + 1;
        $endItem = min($this->currentPage * $this->perPage, $this->totalPartners);

        return view('livewire.admin.omada.index', [
            'partners' => $this->partners,
            'areaOptions' => $this->areaOptions,
            'totalPartners' => $this->totalPartners,
            'currentPage' => $this->currentPage,
            'startItem' => $startItem,
            'endItem' => $endItem,
            'totalPages' => $totalPages,
            'deletePartner' => $this->deletePartnerId ? OmadaPartner::with('area')->find($this->deletePartnerId) : null,
        ]);
    }
}
