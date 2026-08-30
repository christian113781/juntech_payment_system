<?php

namespace App\Livewire\Admin\StockMovement;

use App\Models\StockMovement;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Stock Movements'])]
class Index extends Component
{
    public string $search = '';

    public string $typeFilter = '';

    public string $sort = 'date_desc';

    public int $perPage = 25;

    public int $currentPage = 1;

    public function updatedSearch(): void
    {
        $this->currentPage = 1;
    }

    public function updatedTypeFilter(): void
    {
        $this->currentPage = 1;
    }

    public function previousPage(): void
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    public function nextPage(): void
    {
        $totalPages = max(1, (int) ceil($this->totalMovementCount() / max(1, $this->perPage)));
        if ($this->currentPage < $totalPages) {
            $this->currentPage++;
        }
    }

    public function goToPage(int $page): void
    {
        $totalPages = max(1, (int) ceil($this->totalMovementCount() / max(1, $this->perPage)));
        $this->currentPage = max(1, min($page, $totalPages));
    }

    public function totalMovementCount(): int
    {
        $movements = $this->filteredMovements();
        return count($movements);
    }

    protected function filteredMovements(): array
    {
        $movements = StockMovement::with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function (StockMovement $movement) {
                $productName = $movement->product?->name ?? 'Unknown Product';
                $initials = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $productName) ?? '', 0, 2));

                return [
                    'id' => $movement->id,
                    'product' => $productName,
                    'product_image' => $movement->product?->product_image ? asset('storage/' . $movement->product->product_image) : null,
                    'initials' => $initials ?: 'NA',
                    'badge_bg' => match ($movement->type) {
                        'in' => 'bg-emerald-50',
                        'out' => 'bg-red-50',
                        'adjustment' => 'bg-violet-50',
                        default => 'bg-gray-100',
                    },
                    'badge_text' => match ($movement->type) {
                        'in' => 'text-emerald-600',
                        'out' => 'text-red-500',
                        'adjustment' => 'text-violet-600',
                        default => 'text-gray-500',
                    },
                    'type' => $movement->type,
                    'type_label' => match ($movement->type) {
                        'in' => 'Stock In',
                        'out' => 'Stock Out',
                        'adjustment' => 'Adjustment',
                        default => 'Unknown',
                    },
                    'quantity' => (int) $movement->quantity,
                    'date' => $movement->created_at?->format('Y-m-d') ?? '-',
                    'remarks' => $movement->remarks ?: '—',
                    'user' => $movement->user?->name ?? 'System',
                    'status' => 'Completed',
                ];
            })
            ->toArray();

        $filtered = $movements;

        if ($this->search !== '') {
            $needle = strtolower(trim($this->search));
            $filtered = array_values(array_filter($filtered, function (array $movement) use ($needle) {
                return str_contains(strtolower($movement['product']), $needle)
                    || str_contains(strtolower($movement['remarks']), $needle)
                    || str_contains(strtolower($movement['user']), $needle)
                    || str_contains(strtolower($movement['type_label']), $needle);
            }));
        }

        if ($this->typeFilter !== '') {
            $filtered = array_values(array_filter($filtered, fn (array $movement) => $movement['type'] === $this->typeFilter));
        }

        usort($filtered, function (array $left, array $right) {
            $leftDate = strtotime($left['date']);
            $rightDate = strtotime($right['date']);

            return match ($this->sort) {
                'date_asc' => $leftDate <=> $rightDate,
                'qty_desc' => $right['quantity'] <=> $left['quantity'],
                'qty_asc' => $left['quantity'] <=> $right['quantity'],
                default => $rightDate <=> $leftDate,
            };
        });

        return $filtered;
    }

    public function render()
    {
        $filtered = $this->filteredMovements();
        $totalMovements = count($filtered);
        $totalPages = max(1, (int) ceil($totalMovements / max(1, $this->perPage)));
        $this->currentPage = max(1, min((int) $this->currentPage, $totalPages));
        $offset = ($this->currentPage - 1) * $this->perPage;
        $paginated = array_slice($filtered, $offset, $this->perPage);

        $stockInTotal = array_sum(array_map(fn (array $movement) => $movement['type'] === 'in' ? $movement['quantity'] : 0, $filtered));
        $stockOutTotal = array_sum(array_map(fn (array $movement) => $movement['type'] === 'out' ? $movement['quantity'] : 0, $filtered));
        $adjustmentCount = count(array_filter($filtered, fn (array $movement) => $movement['type'] === 'adjustment'));

        return view('livewire.admin.stock-movement.index', [
            'movements' => $paginated,
            'stockInTotal' => $stockInTotal,
            'stockOutTotal' => $stockOutTotal,
            'adjustmentCount' => $adjustmentCount,
            'totalMovements' => $totalMovements,
            'allMovementsCount' => count($this->filteredMovements()),
            'currentPage' => $this->currentPage,
            'perPage' => $this->perPage,
            'totalPages' => $totalPages,
        ]);
    }
}
