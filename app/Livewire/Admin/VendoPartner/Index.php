<?php

namespace App\Livewire\Admin\VendoPartner;

use App\Models\Area;
use App\Models\VendoCollection;
use App\Models\VendoPartner;
use App\Models\VendoUnit;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Vendo Partners'])]
class Index extends Component
{
    public array $partners = [];

    public array $areaOptions = [];

    public array $assignableUnits = [];

    public string $search = '';

    public string $areaFilter = '';

    public string $collectionFilter = 'all';

    public string $sortBy = 'urgency';

    public int $perPage = 10;

    public int $currentPage = 1;

    public int $totalPartners = 0;

    public ?int $deletePartnerId = null;

    public ?string $deleteErrorMessage = null;

    public bool $showCreateModal = false;

    public bool $showEditModal = false;

    public bool $showCollectModal = false;

    public ?int $editingPartnerId = null;

    public ?int $collectingPartnerId = null;

    public ?string $collectionDate = null;

    public string $collectionAmount = '0.00';

    public string $collectionRemarks = '';

    public string $partnerName = '';

    public ?int $partnerAreaId = null;

    public ?int $partnerUnitId = null;

    public string $partnerContactNumber = '';

    public string $partnerAddress = '';

    public string $partnerStatus = 'active';

    public string $partnerShareRate = '30.00';

    public int $collectionIntervalDays = 32;

    public ?string $lastCollectedAt = null;

    public function mount(): void
    {
        $this->areaOptions = Area::query()->orderBy('name')->get(['id', 'name'])->toArray();
        $this->refreshAssignableUnits();
        $this->loadPartners();
    }

    public function refreshAssignableUnits(?int $includeUnitId = null): void
    {
        $this->assignableUnits = VendoUnit::query()
            ->where(function ($query) use ($includeUnitId) {
                $query->where('status', 'ready');

                if ($includeUnitId) {
                    $query->orWhere('id', $includeUnitId);
                }
            })
            ->orderBy('name')
            ->get(['id', 'name', 'key', 'status'])
            ->toArray();
    }

    protected function getCollectionDueData(VendoPartner $partner): array
    {
        $interval = max(1, (int) ($partner->collection_interval_days ?: 32));

        if (!$partner->last_collected_at) {
            return [
                'days_left' => null,
                'collection_label' => 'No collection yet',
                'next_collection_date' => null,
            ];
        }

        $nextCollectionDate = $partner->last_collected_at->copy()->addDays($interval);
        $daysLeft = (int) now()->startOfDay()->diffInDays($nextCollectionDate, false);

        if ($daysLeft <= 0) {
            $label = $daysLeft === 0 ? 'Due today' : abs($daysLeft) . ' day' . (abs($daysLeft) === 1 ? '' : 's') . ' overdue';
        } elseif ($daysLeft === 1) {
            $label = '1 day left';
        } else {
            $label = $daysLeft . ' days left';
        }

        return [
            'days_left' => $daysLeft,
            'collection_label' => $label,
            'next_collection_date' => $nextCollectionDate->format('M d, Y'),
        ];
    }

    public function loadPartners(): void
    {
        $query = VendoPartner::query()->with(['area', 'unit'])->orderBy('name');

        if ($this->search !== '') {
            $needle = strtolower(trim($this->search));

            $query->where(function ($q) use ($needle) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . $needle . '%'])
                    ->orWhereRaw('LOWER(address) LIKE ?', ['%' . $needle . '%'])
                    ->orWhereRaw('LOWER(contact_number) LIKE ?', ['%' . $needle . '%'])
                    ->orWhereRaw('LOWER(status) LIKE ?', ['%' . $needle . '%'])
                    ->orWhereHas('area', function ($areaQuery) use ($needle) {
                        $areaQuery->whereRaw('LOWER(name) LIKE ?', ['%' . $needle . '%']);
                    })
                    ->orWhereHas('unit', function ($unitQuery) use ($needle) {
                        $unitQuery->whereRaw('LOWER(name) LIKE ?', ['%' . $needle . '%'])
                            ->orWhereRaw('LOWER(key) LIKE ?', ['%' . $needle . '%']);
                    });
            });
        }

        if ($this->areaFilter !== '') {
            $query->where('area_id', $this->areaFilter);
        }

        $this->partners = $query->get()->map(function (VendoPartner $partner) {
            $dueData = $this->getCollectionDueData($partner);

            return [
                'id' => $partner->id,
                'name' => $partner->name,
                'area_id' => $partner->area_id,
                'area_name' => $partner->area?->name,
                'unit_id' => $partner->vendo_unit_id,
                'unit_name' => $partner->unit?->name,
                'unit_key' => $partner->unit?->key,
                'contact_number' => $partner->contact_number,
                'address' => $partner->address,
                'status' => $partner->status,
                'status_label' => ucfirst($partner->status),
                'share_rate' => (float) $partner->share_rate,
                'last_collected_at' => $partner->last_collected_at?->format('M d, Y'),
                'collection_interval_days' => (int) ($partner->collection_interval_days ?: 32),
                'days_left' => $dueData['days_left'],
                'collection_label' => $dueData['collection_label'],
                'next_collection_date' => $dueData['next_collection_date'],
            ];
        })->toArray();

        if ($this->collectionFilter !== 'all') {
            $this->partners = array_values(array_filter($this->partners, function (array $partner): bool {
                $daysLeft = $partner['days_left'];

                return match ($this->collectionFilter) {
                    'overdue' => $daysLeft !== null && $daysLeft < 0,
                    'due_today' => $daysLeft === 0,
                    'due_soon' => $daysLeft !== null && $daysLeft >= 0 && $daysLeft <= 32,
                    'active' => ($partner['status'] ?? '') === 'active',
                    'unassigned' => ($partner['status'] ?? '') === 'unassigned',
                    default => true,
                };
            }));
        }

        if ($this->sortBy === 'urgency') {
            usort($this->partners, function (array $a, array $b): int {
                $aKey = $a['days_left'] ?? 99999;
                $bKey = $b['days_left'] ?? 99999;

                if ($aKey === $bKey) {
                    return strcmp(($a['name'] ?? ''), ($b['name'] ?? ''));
                }

                return $aKey <=> $bKey;
            });
        } elseif ($this->sortBy === 'name') {
            usort($this->partners, function (array $a, array $b): int {
                return strcmp(($a['name'] ?? ''), ($b['name'] ?? ''));
            });
        } elseif ($this->sortBy === 'area') {
            usort($this->partners, function (array $a, array $b): int {
                $areaComparison = strcmp(($a['area_name'] ?? ''), ($b['area_name'] ?? ''));

                return $areaComparison !== 0 ? $areaComparison : strcmp(($a['name'] ?? ''), ($b['name'] ?? ''));
            });
        }

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

    public function updatedCollectionFilter(): void
    {
        $this->currentPage = 1;
        $this->loadPartners();
    }

    public function updatedSortBy(): void
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
        $partner = VendoPartner::findOrFail($partnerId);

        $this->refreshAssignableUnits($partner->vendo_unit_id);
        $this->editingPartnerId = $partner->id;
        $this->partnerName = $partner->name;
        $this->partnerAreaId = $partner->area_id;
        $this->partnerUnitId = $partner->vendo_unit_id;
        $this->partnerContactNumber = $partner->contact_number ?? '';
        $this->partnerAddress = $partner->address ?? '';
        $this->partnerStatus = $partner->status ?? 'active';
        $this->partnerShareRate = (string) $partner->share_rate;
        $this->collectionIntervalDays = (int) ($partner->collection_interval_days ?: 32);
        $this->lastCollectedAt = $partner->last_collected_at?->format('Y-m-d') ?? now()->toDateString();
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editingPartnerId = null;
        $this->resetForm();
    }

    public function openCollectModal(int $partnerId): void
    {
        $partner = VendoPartner::findOrFail($partnerId);

        $this->collectingPartnerId = $partner->id;
        $this->collectionDate = now()->toDateString();
        $this->collectionAmount = '0.00';
        $this->collectionRemarks = '';
        $this->showCollectModal = true;
    }

    public function closeCollectModal(): void
    {
        $this->showCollectModal = false;
        $this->collectingPartnerId = null;
        $this->collectionDate = null;
        $this->collectionAmount = '0.00';
        $this->collectionRemarks = '';
    }

    public function saveCollection(): void
    {
        if (! $this->collectingPartnerId) {
            return;
        }

        $partner = VendoPartner::with('unit')->findOrFail($this->collectingPartnerId);

        $this->validate([
            'collectionDate' => ['required', 'date', 'before_or_equal:today'],
            'collectionAmount' => ['required', 'numeric', 'min:0.01'],
        ]);

        if (! $partner->vendo_unit_id) {
            $this->addError('collectionAmount', 'This partner does not have an assigned vendo unit.');
            return;
        }

        $totalAmount = (float) $this->collectionAmount;
        $shareRate = (float) ($partner->share_rate ?: 0);
        $shareAmount = round($totalAmount * ($shareRate / 100), 2);
        $ownerAmount = round($totalAmount - $shareAmount, 2);

        VendoCollection::create([
            'partner_id' => $partner->id,
            'collection_date' => Carbon::parse($this->collectionDate)->toDateString(),
            'total_amount' => $totalAmount,
            'share_amount' => $shareAmount,
            'owner_amount' => $ownerAmount,
            'remarks' => trim($this->collectionRemarks) !== '' ? trim($this->collectionRemarks) : 'Collected from partner',
        ]);

        $partner->update([
            'last_collected_at' => Carbon::parse($this->collectionDate)->toDateString(),
        ]);

        session()->flash('success', 'Collection recorded successfully.');
        $this->dispatch('toast', message: 'Collection recorded successfully.');
        $this->closeCollectModal();
        $this->loadPartners();
    }

    public function savePartner(): void
    {
        $this->partnerContactNumber = preg_replace('/\D+/', '', (string) $this->partnerContactNumber) ?? '';

        $this->validate([
            'partnerName' => ['required', 'string', 'max:255'],
            'partnerAreaId' => ['required', 'exists:areas,id'],
            'partnerUnitId' => ['nullable', 'exists:vendo_units,id'],
            'partnerContactNumber' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'partnerAddress' => ['nullable', 'string', 'max:255'],
            'partnerStatus' => ['required', 'in:unassigned,active,inactive'],
            'partnerShareRate' => ['required', 'numeric', 'min:0', 'max:100'],
            'lastCollectedAt' => ['nullable', 'date'],
        ]);

        if ($this->partnerUnitId) {
            $currentPartner = $this->editingPartnerId ? VendoPartner::find($this->editingPartnerId) : null;
            $assignedPartner = VendoPartner::query()
                ->where('vendo_unit_id', $this->partnerUnitId)
                ->when($this->editingPartnerId, fn ($query) => $query->whereKeyNot($this->editingPartnerId))
                ->first();

            if ($assignedPartner) {
                $this->addError('partnerUnitId', 'This vendo unit is already assigned to another partner.');
                return;
            }

            $unit = VendoUnit::find($this->partnerUnitId);
            if ($unit && $unit->status !== 'ready' && (!$currentPartner || $currentPartner->vendo_unit_id !== $unit->id)) {
                $this->addError('partnerUnitId', 'Only ready units can be assigned to a partner.');
                return;
            }
        }

        if ($this->showEditModal && $this->editingPartnerId) {
            $partner = VendoPartner::findOrFail($this->editingPartnerId);
            $previousUnitId = $partner->vendo_unit_id;

            $partner->update([
                'name' => trim($this->partnerName),
                'area_id' => $this->partnerAreaId,
                'vendo_unit_id' => $this->partnerUnitId,
                'contact_number' => trim($this->partnerContactNumber),
                'address' => trim($this->partnerAddress) !== '' ? trim($this->partnerAddress) : null,
                'status' => $this->partnerStatus,
                'share_rate' => (float) $this->partnerShareRate,
                'last_collected_at' => $this->lastCollectedAt
                    ? \Carbon\Carbon::parse($this->lastCollectedAt)->toDateString()
                    : now()->toDateString(),
                'collection_interval_days' => $this->collectionIntervalDays,
            ]);

            if ($previousUnitId && $previousUnitId !== $this->partnerUnitId) {
                VendoUnit::whereKey($previousUnitId)->update(['status' => 'ready']);
            }

            if ($this->partnerUnitId) {
                VendoUnit::whereKey($this->partnerUnitId)->update(['status' => 'assigned']);
            }

            session()->flash('success', 'Vendo partner updated successfully.');
            $this->dispatch('toast', message: 'Vendo partner updated successfully.');
        } else {
            $partner = VendoPartner::create([
                'name' => trim($this->partnerName),
                'area_id' => $this->partnerAreaId,
                'vendo_unit_id' => $this->partnerUnitId,
                'contact_number' => trim($this->partnerContactNumber),
                'address' => trim($this->partnerAddress) !== '' ? trim($this->partnerAddress) : null,
                'status' => $this->partnerStatus,
                'share_rate' => (float) $this->partnerShareRate,
                'last_collected_at' => $this->lastCollectedAt
                    ? \Carbon\Carbon::parse($this->lastCollectedAt)->toDateString()
                    : now()->toDateString(),
                'collection_interval_days' => $this->collectionIntervalDays,
            ]);

            if ($partner->vendo_unit_id) {
                VendoUnit::whereKey($partner->vendo_unit_id)->update(['status' => 'assigned']);
            }

            session()->flash('success', 'Vendo partner created successfully.');
            $this->dispatch('toast', message: 'Vendo partner created successfully.');
        }

        $this->closeCreateModal();
        $this->closeEditModal();
        $this->refreshAssignableUnits();
        $this->loadPartners();
    }

    public function openDeleteModal(int $partnerId): void
    {
        $this->deletePartnerId = $partnerId;
        $this->deleteErrorMessage = null;
    }

    public function closeDeleteModal(): void
    {
        $this->deletePartnerId = null;
        $this->deleteErrorMessage = null;
    }

    public function confirmDelete(): void
    {
        if (! $this->deletePartnerId) {
            return;
        }

        $partner = VendoPartner::with('unit')->find($this->deletePartnerId);

        if ($partner) {
            $unitId = $partner->vendo_unit_id;
            $partner->delete();

            if ($unitId) {
                VendoUnit::whereKey($unitId)->update(['status' => 'ready']);
            }

            session()->flash('success', 'Vendo partner deleted successfully.');
            $this->dispatch('toast', message: 'Vendo partner deleted successfully.');
        }

        $this->deletePartnerId = null;
        $this->deleteErrorMessage = null;
        $this->refreshAssignableUnits();
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
        $this->partnerUnitId = null;
        $this->partnerContactNumber = '';
        $this->partnerAddress = '';
        $this->partnerStatus = 'active';
        $this->partnerShareRate = '30.00';
        $this->collectionIntervalDays = 32;
        $this->lastCollectedAt = now()->toDateString();
        $this->editingPartnerId = null;
    }

    public function render()
    {
        $totalPages = max(1, (int) ceil($this->totalPartners / $this->perPage));
        $startItem = $this->totalPartners === 0 ? 0 : (($this->currentPage - 1) * $this->perPage) + 1;
        $endItem = min($this->currentPage * $this->perPage, $this->totalPartners);

        return view('livewire.admin.vendo-partner.index', [
            'partners' => $this->partners,
            'areaOptions' => $this->areaOptions,
            'assignableUnits' => $this->assignableUnits,
            'totalPartners' => $this->totalPartners,
            'currentPage' => $this->currentPage,
            'startItem' => $startItem,
            'endItem' => $endItem,
            'totalPages' => $totalPages,
            'deletePartner' => $this->deletePartnerId ? VendoPartner::with(['area', 'unit'])->find($this->deletePartnerId) : null,
            'collectingPartner' => $this->collectingPartnerId ? VendoPartner::with(['area', 'unit'])->find($this->collectingPartnerId) : null,
        ]);
    }
}
