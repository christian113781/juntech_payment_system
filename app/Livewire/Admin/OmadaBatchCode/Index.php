<?php

namespace App\Livewire\Admin\OmadaBatchCode;

use App\Models\OmadaPartner;
use App\Models\OmadaVoucherBatch;
use App\Models\OmadaVoucherCollection;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app', ['title' => 'Omada Batch Codes'])]
class Index extends Component
{
    use WithPagination;

    public OmadaPartner $partner;
    public string $search = '';
    public string $typeFilter = '';
    public string $statusFilter = '';

    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public ?int $editingBatchId = null;
    public ?int $deleteBatchId = null;
    public ?OmadaVoucherBatch $deleteBatch = null;

    public ?int $statusBatchId = null;
    public ?OmadaVoucherBatch $statusBatch = null;
    public string $statusValue = 'pending';

    public ?int $paymentBatchId = null;
    public ?OmadaVoucherBatch $paymentBatch = null;
    public string $paymentCollectionDate = '';
    public string $paymentTotalAmount = '0';
    public string $paymentRemarks = '';

    public string $type = 'SALE';
    public int $requestedQty = 0;
    public int $bonusQty = 0;
    public ?float $pricePerVoucher = null;
    public string $remarks = '';

    protected $queryString = ['search' => ['except' => ''], 'typeFilter' => ['except' => ''], 'statusFilter' => ['except' => '']];

    public function mount(OmadaPartner $partner): void
    {
        $this->partner = $partner;
    }

    public function loadBatches()
    {
        $query = $this->partner->voucherBatches();

        if ($this->search) {
            $query->where('batch_code', 'like', '%' . $this->search . '%');
        }

        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        return $query->orderBy('generated_date', 'desc')->paginate(25);
    }

    public function loadCollections()
    {
        return OmadaVoucherCollection::query()
            ->whereIn('batch_id', $this->partner->voucherBatches()->pluck('id'))
            ->with('voucherBatch')
            ->orderByDesc('collection_date')
            ->orderByDesc('id')
            ->paginate(10);
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal(int $batchId): void
    {
        $batch = OmadaVoucherBatch::findOrFail($batchId);
        $this->editingBatchId = $batch->id;
        $this->type = $batch->type;
        $this->requestedQty = $batch->requested_qty;
        $this->bonusQty = $batch->bonus_qty;
        $this->pricePerVoucher = (float) $batch->price_per_voucher;
        $this->remarks = $batch->remarks ?? '';
        $this->showEditModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function openDeleteModal(int $batchId): void
    {
        $this->deleteBatch = OmadaVoucherBatch::findOrFail($batchId);
        $this->deleteBatchId = $batchId;
    }

    public function closeDeleteModal(): void
    {
        $this->deleteBatchId = null;
        $this->deleteBatch = null;
    }

    public function openStatusModal(int $batchId): void
    {
        $batch = OmadaVoucherBatch::findOrFail($batchId);
        $this->statusBatchId = $batch->id;
        $this->statusBatch = $batch;
        $this->statusValue = in_array($batch->status, ['delivered', 'cancelled'], true)
            ? $batch->status
            : 'delivered';
    }

    public function closeStatusModal(): void
    {
        $this->statusBatchId = null;
        $this->statusBatch = null;
        $this->statusValue = 'pending';
    }

    public function openPaymentModal(int $batchId): void
    {
        $batch = OmadaVoucherBatch::findOrFail($batchId);
        $this->paymentBatchId = $batch->id;
        $this->paymentBatch = $batch;
        $this->paymentCollectionDate = now()->format('Y-m-d');
        $this->paymentTotalAmount = number_format((float) ($batch->requested_qty * $batch->price_per_voucher), 2, '.', '');
        $this->paymentRemarks = '';
    }

    public function closePaymentModal(): void
    {
        $this->paymentBatchId = null;
        $this->paymentBatch = null;
        $this->paymentCollectionDate = '';
        $this->paymentTotalAmount = '0';
        $this->paymentRemarks = '';
    }

    public function confirmDelete(): void
    {
        if ($this->deleteBatchId) {
            OmadaVoucherBatch::destroy($this->deleteBatchId);
            session()->flash('success', 'Batch deleted successfully.');
            $this->dispatch('toast', message: 'Batch deleted successfully.');
            $this->closeDeleteModal();
        }
    }

    public function saveStatus(): void
    {
        if (! $this->statusBatch) {
            return;
        }

        $this->validate([
            'statusValue' => ['required', 'in:delivered,cancelled'],
        ]);

        $this->statusBatch->update([
            'status' => $this->statusValue,
        ]);

        session()->flash('success', 'Batch status updated successfully.');
        $this->dispatch('toast', message: 'Batch status updated successfully.');
        $this->closeStatusModal();
    }

    public function savePayment(): void
    {
        if (! $this->paymentBatch) {
            return;
        }

        $this->validate([
            'paymentCollectionDate' => ['required', 'date'],
            'paymentTotalAmount' => ['required', 'numeric', 'min:0'],
            'paymentRemarks' => ['nullable', 'string', 'max:255'],
        ]);

        OmadaVoucherCollection::create([
            'batch_id' => $this->paymentBatch->id,
            'collection_date' => $this->paymentCollectionDate,
            'total_amount' => $this->paymentTotalAmount,
            'remarks' => trim($this->paymentRemarks),
        ]);

        $this->paymentBatch->update([
            'status' => 'paid',
        ]);

        session()->flash('success', 'Batch marked as paid.');
        $this->dispatch('toast', message: 'Batch marked as paid.');
        $this->closePaymentModal();
    }

    public function saveBatch(): void
    {
        $this->validate([
            'type' => ['required', 'in:SALE,FREE,SALE + FREE'],
            'requestedQty' => ['required', 'integer', 'min:1'],
            'bonusQty' => ['required', 'integer', 'min:0'],
            'pricePerVoucher' => ['required', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);

        if ($this->showEditModal && $this->editingBatchId) {
            $batch = OmadaVoucherBatch::findOrFail($this->editingBatchId);
            $batch->update([
                'type' => $this->type,
                'requested_qty' => $this->requestedQty,
                'bonus_qty' => $this->bonusQty,
                'price_per_voucher' => $this->pricePerVoucher,
                'remarks' => trim($this->remarks),
            ]);

            session()->flash('success', 'Batch updated successfully.');
            $this->dispatch('toast', message: 'Batch updated successfully.');
        } else {
            $batchCode = $this->generateBatchCode();

            OmadaVoucherBatch::create([
                'partner_id' => $this->partner->id,
                'batch_code' => $batchCode,
                'type' => $this->type,
                'requested_qty' => $this->requestedQty,
                'bonus_qty' => $this->bonusQty,
                'price_per_voucher' => $this->pricePerVoucher,
                'generated_date' => now(),
                'status' => 'pending',
                'remarks' => trim($this->remarks),
            ]);

            session()->flash('success', 'Batch created successfully.');
            $this->dispatch('toast', message: 'Batch created successfully.');
        }

        $this->closeCreateModal();
        $this->closeEditModal();
    }

    private function generateBatchCode(): string
    {
        $year = now()->format('Y');
        $month = now()->format('m');
        $timestamp = now()->format('Hisu'); // Hour, minute, second, microsecond

        return "OM-{$year}-{$month}-" . substr($timestamp, 0, 6);
    }

    public function resetForm(): void
    {
        $this->type = 'SALE';
        $this->requestedQty = 0;
        $this->bonusQty = 0;
        $this->pricePerVoucher = null;
        $this->remarks = '';
        $this->editingBatchId = null;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $batches = $this->loadBatches();
        $collections = $this->loadCollections();
        $totalBatches = $this->partner->voucherBatches()->count();
        $collectionTotal = $this->partner->voucherBatches()
            ->with('voucherCollections')
            ->get()
            ->flatMap(fn ($batch) => $batch->voucherCollections)
            ->sum('total_amount');

        return view('livewire.admin.omada-batch-code.index', [
            'partner' => $this->partner,
            'batches' => $batches,
            'collections' => $collections,
            'collectionTotal' => $collectionTotal,
            'totalBatches' => $totalBatches,
        ]);
    }
}
