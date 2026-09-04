<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Billing;
use App\Models\CompanyExpense;
use App\Models\Customer;
use App\Models\OmadaVoucherCollection;
use App\Models\Payment;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\VendoCollection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Dashboard'])]
class Index extends Component
{
    public function render()
    {
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $recentPayments = Payment::query()->with('customer')->latest('payment_date')->latest('id')->limit(5)->get()
            ->map(fn (Payment $payment) => [
                'customer' => $payment->customer?->name ?? 'Unknown customer',
                'amount' => (float) $payment->amount_paid,
                'date' => $payment->payment_date?->format('M d, Y') ?? '—',
                'method' => ucfirst($payment->payment_method ?? 'cash'),
            ])->all();

        $recentMovements = StockMovement::query()->with('product')->latest()->limit(5)->get()
            ->map(fn (StockMovement $movement) => [
                'product' => $movement->product?->name ?? 'Unknown product',
                'type' => ucfirst($movement->type),
                'quantity' => (int) $movement->quantity,
                'date' => $movement->created_at?->format('M d, Y') ?? '—',
            ])->all();

        $chartStart = now()->startOfDay()->subDays(6);
        $movementRows = StockMovement::query()
            ->whereBetween('created_at', [$chartStart, now()->endOfDay()])
            ->whereIn('type', ['in', 'out'])
            ->get(['type', 'quantity', 'created_at'])
            ->groupBy(fn (StockMovement $movement) => $movement->created_at->format('Y-m-d'));
        $stockChart = collect(range(0, 6))->map(function (int $offset) use ($chartStart, $movementRows): array {
            $date = $chartStart->copy()->addDays($offset);
            $rows = $movementRows->get($date->format('Y-m-d'), collect());

            return [
                'day' => $date->format('D'),
                'in' => (int) $rows->where('type', 'in')->sum('quantity'),
                'out' => (int) $rows->where('type', 'out')->sum('quantity'),
            ];
        })->all();
        $totalIn = collect($stockChart)->sum('in');
        $totalOut = collect($stockChart)->sum('out');

        return view('livewire.admin.dashboard.index', [
            'stats' => [
                ['label' => 'Active Customers', 'value' => Customer::where('status', 'active')->count(), 'note' => 'Currently connected', 'tone' => 'brand', 'route' => 'customers.index'],
                ['label' => 'Outstanding Billing', 'value' => Billing::sum('balance'), 'note' => 'Across all billing records', 'tone' => 'red', 'route' => 'billings.index', 'currency' => true],
                ['label' => 'Inventory On Hand', 'value' => Product::with('stockBalance')->get()->sum('quantity_on_hand'), 'note' => Product::count().' products tracked', 'tone' => 'emerald', 'route' => 'products.index'],
                ['label' => 'This Month Expenses', 'value' => CompanyExpense::whereBetween('expense_date', [$monthStart, $monthEnd])->sum('amount'), 'note' => 'Recorded business expenses', 'tone' => 'amber', 'route' => 'expenses.index', 'currency' => true],
            ],
            'monthName' => now()->format('F Y'),
            'monthlyCollections' => Payment::whereBetween('payment_date', [$monthStart, $monthEnd])->sum('amount_paid'),
            'vendoCollections' => VendoCollection::whereBetween('collection_date', [$monthStart, $monthEnd])->sum('owner_amount'),
            'omadaSales' => OmadaVoucherCollection::whereBetween('collection_date', [$monthStart, $monthEnd])->sum('total_amount'),
            'lowStockCount' => Product::lowStock()->count(),
            'recentPayments' => $recentPayments,
            'recentMovements' => $recentMovements,
            'stockChart' => $stockChart,
            'stockChartMax' => max(1, collect($stockChart)->max(fn (array $day) => max($day['in'], $day['out']))),
            'totalIn' => $totalIn,
            'totalOut' => $totalOut,
        ]);
    }
}
