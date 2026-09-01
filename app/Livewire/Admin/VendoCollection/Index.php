<?php

namespace App\Livewire\Admin\VendoCollection;

use App\Models\VendoCollection;
use App\Models\VendoPartner;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Vendo Collections'])]
class Index extends Component
{
    public VendoPartner $partner;

    public array $collections = [];

    public int $currentPage = 1;

    public int $perPage = 10;

    public bool $showCollectModal = false;

    public ?int $editingCollectionId = null;

    public ?int $deletingCollectionId = null;

    public string $collectionDate = '';

    public string $collectionAmount = '0.00';

    public string $collectionRemarks = '';

    public function mount(VendoPartner $partner): void
    {
        $this->partner = $partner;
        $this->loadCollections();
    }

    public function loadCollections(): void
    {
        $this->collections = $this->partner->collections()
            ->orderByDesc('collection_date')
            ->orderByDesc('id')
            ->get()
            ->map(function (VendoCollection $collection) {
                return [
                    'id' => $collection->id,
                    'partner_id' => $collection->partner_id,
                    'collection_date' => $collection->collection_date?->format('Y-m-d'),
                    'total_amount' => (float) $collection->total_amount,
                    'share_amount' => (float) $collection->share_amount,
                    'owner_amount' => (float) $collection->owner_amount,
                    'remarks' => $collection->remarks,
                ];
            })
            ->toArray();
    }

    public function openCollectModal(): void
    {
        $this->editingCollectionId = null;
        $this->collectionDate = now()->toDateString();
        $this->collectionAmount = '0.00';
        $this->collectionRemarks = '';
        $this->showCollectModal = true;
    }

    public function closeCollectModal(): void
    {
        $this->showCollectModal = false;
        $this->editingCollectionId = null;
        $this->collectionDate = now()->toDateString();
        $this->collectionAmount = '0.00';
        $this->collectionRemarks = '';
    }

    public function saveCollection(): void
    {
        $this->validate([
            'collectionDate' => ['required', 'date', 'before_or_equal:today'],
            'collectionAmount' => ['required', 'numeric', 'min:0.01'],
            'collectionRemarks' => ['nullable', 'string', 'max:255'],
        ]);

        $totalAmount = (float) $this->collectionAmount;
        $shareRate = (float) ($this->partner->share_rate ?: 0);
        $shareAmount = round($totalAmount * ($shareRate / 100), 2);
        $ownerAmount = round($totalAmount - $shareAmount, 2);

        $collection = VendoCollection::create([
            'partner_id' => $this->partner->id,
            'collection_date' => Carbon::parse($this->collectionDate)->toDateString(),
            'total_amount' => $totalAmount,
            'share_amount' => $shareAmount,
            'owner_amount' => $ownerAmount,
            'remarks' => trim($this->collectionRemarks) !== '' ? trim($this->collectionRemarks) : null,
        ]);

        if ($this->partner->last_collected_at === null || Carbon::parse($this->collectionDate)->greaterThan(Carbon::parse($this->partner->last_collected_at))) {
            $this->partner->update([
                'last_collected_at' => Carbon::parse($this->collectionDate)->toDateString(),
            ]);
        }

        $this->closeCollectModal();
        $this->loadCollections();
        session()->flash('success', 'Collection recorded successfully.');
        $this->dispatch('toast', message: 'Collection recorded successfully.');
    }

    public function openDeleteModal(int $collectionId): void
    {
        $this->deletingCollectionId = $collectionId;
    }

    public function closeDeleteModal(): void
    {
        $this->deletingCollectionId = null;
    }

    public function confirmDelete(): void
    {
        if (! $this->deletingCollectionId) {
            return;
        }

        $collection = $this->partner->collections()->find($this->deletingCollectionId);

        if ($collection) {
            $collection->delete();
            session()->flash('success', 'Collection deleted.');
            $this->dispatch('toast', message: 'Collection deleted.');
        }

        $this->closeDeleteModal();
        $this->loadCollections();
    }

    public function previousPage(): void
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    public function nextPage(): void
    {
        $totalPages = max(1, (int) ceil(count($this->collections) / $this->perPage));

        if ($this->currentPage < $totalPages) {
            $this->currentPage++;
        }
    }

    public function goToPage(int $page): void
    {
        $totalPages = max(1, (int) ceil(count($this->collections) / $this->perPage));
        $this->currentPage = max(1, min($page, $totalPages));
    }

    public function render()
    {
        $totalCollections = count($this->collections);
        $totalPages = max(1, (int) ceil($totalCollections / $this->perPage));
        $this->currentPage = max(1, min($this->currentPage, $totalPages));

        $startItem = $totalCollections === 0 ? 0 : (($this->currentPage - 1) * $this->perPage) + 1;
        $endItem = min($this->currentPage * $this->perPage, $totalCollections);
        $paginatedCollections = array_slice($this->collections, ($this->currentPage - 1) * $this->perPage, $this->perPage);

        $total = collect($this->collections)->sum('total_amount');
        $share = collect($this->collections)->sum('share_amount');
        $owner = collect($this->collections)->sum('owner_amount');

        return view('livewire.admin.vendo-collection.index', [
            'partner' => $this->partner,
            'collections' => $this->collections,
            'paginatedCollections' => $paginatedCollections,
            'totalCollections' => $totalCollections,
            'totalPages' => $totalPages,
            'currentPage' => $this->currentPage,
            'startItem' => $startItem,
            'endItem' => $endItem,
            'totalAmount' => $total,
            'shareAmount' => $share,
            'ownerAmount' => $owner,
        ]);
    }
}
