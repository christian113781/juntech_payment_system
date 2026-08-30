<?php

namespace App\Livewire\Admin\Product;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Products'])]
class Index extends Component
{
    public array $allProducts = [];

    public array $products = [];

    public array $categories = [];

    public string $search = '';

    public string $categoryFilter = '';

    public string $stockFilter = '';

    public int $perPage = 10;

    public int $currentPage = 1;

    public int $totalProducts = 0;

    public int $lowStockCount = 0;

    public int $outOfStockCount = 0;

    public ?int $deleteProductId = null;

    public ?int $stockMovementProductId = null;

    public string $stockMovementType = 'in';

    public int $stockMovementQuantity = 0;

    public string $stockMovementRemarks = '';

    public function mount(): void
    {
        $this->loadProducts();
    }

    public function confirmDelete(): void
    {
        if (! $this->deleteProductId) {
            return;
        }

        $product = Product::find($this->deleteProductId);

        if ($product) {
            if ($product->product_image && Storage::disk('public')->exists($product->product_image)) {
                Storage::disk('public')->delete($product->product_image);
            }

            $product->stockMovements()->delete();
            $product->stockBalance()->delete();
            $product->delete();

            session()->flash('success', "\"{$product->name}\" was deleted.");
            $this->dispatch('toast', message: "\"{$product->name}\" was deleted.");
        }

        $this->deleteProductId = null;
        $this->loadProducts();
    }

    public function openDeleteModal(int $productId): void
    {
        $this->deleteProductId = $productId;
    }

    public function closeDeleteModal(): void
    {
        $this->deleteProductId = null;
    }

    public function openStockMovementModal(int $productId): void
    {
        $product = Product::find($productId);

        $this->stockMovementProductId = $productId;
        $this->stockMovementType = 'in';
        $this->stockMovementQuantity = 0;
        $this->stockMovementRemarks = '';
        $this->stockMovementProductId = $product?->id;
    }

    public function closeStockMovementModal(): void
    {
        $this->stockMovementProductId = null;
        $this->stockMovementType = 'in';
        $this->stockMovementQuantity = 0;
        $this->stockMovementRemarks = '';
    }

    public function saveStockMovement(): void
    {
        if (! $this->stockMovementProductId) {
            return;
        }

        $product = Product::with('stockBalance')->find($this->stockMovementProductId);

        if (! $product) {
            $this->closeStockMovementModal();
            return;
        }

        $currentQty = (int) $product->quantity_on_hand;
        $quantity = (int) $this->stockMovementQuantity;

        if ($quantity <= 0) {
            $this->addError('stockMovementQuantity', 'Quantity must be greater than 0.');
            return;
        }

        if ($this->stockMovementType === 'adjustment' && $quantity < 0) {
            $this->addError('stockMovementQuantity', 'Adjustment quantity cannot be negative.');
            return;
        }

        if ($this->stockMovementType === 'out' && $quantity > $currentQty) {
            $this->addError('stockMovementQuantity', 'Stock out quantity cannot exceed the current stock balance.');
            return;
        }

        $newQty = match ($this->stockMovementType) {
            'in' => $currentQty + $quantity,
            'out' => $currentQty - $quantity,
            'adjustment' => $quantity,
            default => $currentQty,
        };

        $product->stockBalance()->updateOrCreate([], [
            'quantity_on_hand' => $newQty,
        ]);

        $product->stockMovements()->create([
            'user_id' => auth()->id(),
            'type' => $this->stockMovementType,
            'quantity' => $quantity,
            'remarks' => $this->stockMovementRemarks ?: null,
        ]);

        session()->flash('success', "Stock movement for \"{$product->name}\" was saved.");
        $this->dispatch('toast', message: "Stock movement for \"{$product->name}\" was saved.");

        $this->closeStockMovementModal();
        $this->loadProducts();
    }

    public function loadProducts(): void
    {
        $this->allProducts = Product::with(['category', 'stockBalance'])
            ->get()
            ->map(function (Product $product) {
                $categoryName = $product->category?->name ?? 'Others';

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'brand' => $product->brand,
                    'category' => $categoryName,
                    'unit' => $product->unit ?? 'Piece',
                    'reorder_level' => (int) $product->reorder_level,
                    'qty' => (int) $product->quantity_on_hand,
                    'product_image' => $product->product_image ? asset('storage/' . $product->product_image) : null,
                    'initials' => strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $product->name) ?? '', 0, 2)),
                ];
            })
            ->toArray();

        $this->categories = Category::query()->pluck('name')->toArray();
        $this->products = $this->allProducts;
        $this->applyFilters();
    }

    protected function applyFilters(): void
    {
        $filtered = $this->allProducts;

        if ($this->search !== '') {
            $needle = strtolower(trim($this->search));

            $filtered = array_values(array_filter($filtered, function (array $product) use ($needle) {
                return str_contains(strtolower($product['name']), $needle)
                    || str_contains(strtolower($product['sku']), $needle)
                    || str_contains(strtolower($product['brand']), $needle);
            }));
        }

        if ($this->categoryFilter !== '') {
            $filtered = array_values(array_filter($filtered, fn (array $product) => $product['category'] === $this->categoryFilter));
        }

        if ($this->stockFilter !== '') {
            $filtered = array_values(array_filter($filtered, function (array $product) {
                return match ($this->stockFilter) {
                    'in_stock' => $product['qty'] > $product['reorder_level'],
                    'low_stock' => $product['qty'] > 0 && $product['qty'] <= $product['reorder_level'],
                    'out_of_stock' => $product['qty'] === 0,
                    default => true,
                };
            }));
        }

        usort($filtered, function (array $left, array $right) {
            return strnatcasecmp($left['name'], $right['name']);
        });

        $this->totalProducts = count($filtered);
        $this->lowStockCount = count(array_filter($filtered, fn (array $product) => $product['qty'] > 0 && $product['qty'] <= $product['reorder_level']));
        $this->outOfStockCount = count(array_filter($filtered, fn (array $product) => $product['qty'] === 0));

        $this->perPage = max(1, $this->perPage);
        $totalPages = max(1, (int) ceil($this->totalProducts / $this->perPage));
        $this->currentPage = max(1, min((int) $this->currentPage, $totalPages));

        $offset = ($this->currentPage - 1) * $this->perPage;
        $this->products = array_slice($filtered, $offset, $this->perPage);
    }

    public function updatedSearch(): void
    {
        $this->currentPage = 1;
        $this->applyFilters();
    }

    public function updatedCategoryFilter(): void
    {
        $this->currentPage = 1;
        $this->applyFilters();
    }

    public function updatedStockFilter(): void
    {
        $this->currentPage = 1;
        $this->applyFilters();
    }

    public function updatedPerPage(): void
    {
        $this->currentPage = 1;
        $this->applyFilters();
    }

    public function goToPage(int $page): void
    {
        $totalPages = max(1, (int) ceil($this->totalProducts / max(1, $this->perPage)));
        $this->currentPage = max(1, min($page, $totalPages));
        $this->applyFilters();
    }

    public function previousPage(): void
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
            $this->applyFilters();
        }
    }

    public function nextPage(): void
    {
        $totalPages = max(1, (int) ceil($this->totalProducts / max(1, $this->perPage)));
        if ($this->currentPage < $totalPages) {
            $this->currentPage++;
            $this->applyFilters();
        }
    }

    public function render()
    {
        return view('livewire.admin.product.index', [
            'products' => $this->products,
            'categories' => $this->categories,
            'totalProducts' => $this->totalProducts,
            'lowStockCount' => $this->lowStockCount,
            'outOfStockCount' => $this->outOfStockCount,
            'currentPage' => $this->currentPage,
            'perPage' => $this->perPage,
            'deleteProduct' => $this->deleteProductId ? collect($this->allProducts)->firstWhere('id', $this->deleteProductId) : null,
            'stockMovementProduct' => $this->stockMovementProductId ? collect($this->allProducts)->firstWhere('id', $this->stockMovementProductId) : null,
        ]);
    }
}
