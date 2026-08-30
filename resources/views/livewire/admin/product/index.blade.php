@php
    $badgeColors = [
        'Electronics' => ['bg-brand-50', 'text-brand-600'],
        'Apparel' => ['bg-amber-50', 'text-amber-600'],
        'Food & Beverage' => ['bg-violet-50', 'text-violet-600'],
        'Furniture' => ['bg-red-50', 'text-red-500'],
        'Others' => ['bg-gray-100', 'text-gray-500'],
    ];
@endphp

<div class="space-y-6">

    {{-- Heading --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Products</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $totalProducts }} products across
                {{ count($categories) }} categories</p>
        </div>
        <a href="{{ route('products.create') }}" wire:navigate
            class="flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm min-h-[44px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Product
        </a>
    </div>

    {{-- Mini stats --}}
    <section aria-label="Product summary">
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-card flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center flex-shrink-0"
                    aria-hidden="true">
                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide">Total</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $totalProducts }}</p>
                </div>
            </div>

            <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-card flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center flex-shrink-0"
                    aria-hidden="true">
                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide">Categories
                    </p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ count($categories) }}</p>
                </div>
            </div>

            <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-card flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0"
                    aria-hidden="true">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide">Low Stock
                    </p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $lowStockCount }}</p>
                </div>
            </div>

            <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-card flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0"
                    aria-hidden="true">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide">Out of
                        Stock</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $outOfStockCount }}</p>
                </div>
            </div>
        </div>
    </section>

    @if ($deleteProductId)
        <x-delete-modal
            name="product"
            :show="true"
            :item-name="data_get($deleteProduct, 'name')"
        />
    @endif

    @if ($stockMovementProductId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="w-full max-w-lg rounded-[1.25rem] bg-white p-5 shadow-card dark:bg-gray-800">
                <div class="mb-5 flex items-start justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Restock Item</h2>
                        <p id="modal-desc" class="mt-1 text-xs text-gray-400 dark:text-gray-500">Fill in the details to add a product to inventory</p>
                    </div>

                    <button type="button" wire:click="closeStockMovementModal()"
                        class="p-2 rounded-xl text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors"
                        aria-label="Close dialog">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="stock-item-name" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Item Name</label>
                        <input id="stock-item-name" type="text" value="{{ data_get($stockMovementProduct, 'name') }}" class="field bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200" readonly>
                    </div>

                    <div>
                        <label for="stock-movement-type" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Type <span class="text-red-500">*</span></label>
                        <select id="stock-movement-type" wire:model="stockMovementType" class="field @error('stockMovementType') error @enderror">
                            <option value="in">Stock In</option>
                            <option value="out">Stock Out</option>
                            <option value="adjustment">Adjustment</option>
                        </select>
                    </div>

                    <div>
                        <label for="stock-movement-quantity" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Quantity <span class="text-red-500">*</span></label>
                        <input id="stock-movement-quantity" type="number" min="0" step="1" wire:model="stockMovementQuantity" class="field @error('stockMovementQuantity') error @enderror" placeholder="0" onkeydown="return event.key !== '-' && event.key !== 'e' && event.key !== 'E' && event.key !== '+';">
                        @error('stockMovementQuantity')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="stock-movement-remarks" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Remarks</label>
                        <textarea id="stock-movement-remarks" wire:model="stockMovementRemarks" rows="3" class="field @error('stockMovementRemarks') error @enderror" placeholder="Enter remarks..."></textarea>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" wire:click="closeStockMovementModal()" class="min-h-[44px] rounded-xl bg-gray-700 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-gray-800">
                        Cancel
                    </button>
                    <button type="button" wire:click="saveStockMovement()" class="min-h-[44px] rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand-700">
                        Save
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Table card --}}
    <section aria-label="Product list" class="bg-white dark:bg-gray-800 rounded-2xl shadow-card">

        {{-- Filter bar (reference-style compact) --}}
        <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex flex-col lg:flex-row lg:items-center gap-3">
            <div class="relative flex-1 max-w-[28rem]">
                <svg class="absolute start-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                </svg>
                <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search name or SKU…"
                    class="w-full ps-9 pe-4 py-2.5 text-sm bg-transparent border border-gray-200 rounded-[0.95rem] placeholder:text-gray-500 text-gray-700 focus:border-brand-400 focus:ring-2 focus:ring-brand-100 outline-none transition-colors min-h-[2.75rem] shadow-none"
                    aria-label="Search products">
            </div>

            <select class="field !w-auto min-h-[2.75rem] py-2 text-sm" aria-label="Filter by category" wire:model.live="categoryFilter">
                <option value="">All categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </select>

            <select class="field !w-auto min-h-[2.75rem] py-2 text-sm" aria-label="Filter by stock level" wire:model.live="stockFilter">
                <option value="">All stock levels</option>
                <option value="in_stock">In Stock</option>
                <option value="low_stock">Low Stock</option>
                <option value="out_of_stock">Out of Stock</option>
            </select>

            <select class="field !w-auto min-h-[2.75rem] py-2 text-sm lg:ms-auto" aria-label="Sort products">
                <option>Sort: Name (A–Z)</option>
                <option>Sort: Stock (low → high)</option>
                <option>Sort: Stock (high → low)</option>
            </select>
        </div>

        {{-- Table --}}
        <div class="hidden lg:block tbl-wrap" tabindex="0" role="region" aria-label="Product list table">
            <table class="w-full text-sm" style="min-inline-size:56rem">
                <caption class="sr-only">Product list</caption>
                <thead>
                    <tr class="text-left border-b border-gray-100 dark:border-gray-700">
                        <th scope="col"
                            class="px-5 py-3 font-semibold text-gray-500 dark:text-gray-400 text-[11px] uppercase tracking-[0.12em]">
                            Product</th>
                        <th scope="col"
                            class="px-5 py-3 font-semibold text-gray-500 dark:text-gray-400 text-[11px] uppercase tracking-[0.12em]">
                            SKU</th>
                        <th scope="col"
                            class="px-5 py-3 font-semibold text-gray-500 dark:text-gray-400 text-[11px] uppercase tracking-[0.12em]">
                            Category</th>
                        <th scope="col"
                            class="px-5 py-3 font-semibold text-gray-500 dark:text-gray-400 text-[11px] uppercase tracking-[0.12em]">
                            Unit</th>
                        <th scope="col"
                            class="px-5 py-3 font-semibold text-gray-500 dark:text-gray-400 text-[11px] uppercase tracking-[0.12em] text-end">
                            Reorder</th>
                        <th scope="col"
                            class="px-5 py-3 font-semibold text-gray-500 dark:text-gray-400 text-[11px] uppercase tracking-[0.12em] text-end">
                            Qty</th>
                        <th scope="col"
                            class="px-5 py-3 font-semibold text-gray-500 dark:text-gray-400 text-[11px] uppercase tracking-[0.12em]">
                            Status</th>
                        <th scope="col"
                            class="px-5 py-3 font-semibold text-gray-500 dark:text-gray-400 text-[11px] uppercase tracking-[0.12em] text-end">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse ($products as $product)
                        @php
                            [$badgeBg, $badgeText] = $badgeColors[$product['category']] ?? [
                                'bg-gray-100',
                                'text-gray-500',
                            ];

                            $stockLabel = match (true) {
                                $product['qty'] === 0 => 'Out of Stock',
                                $product['qty'] <= $product['reorder_level'] => 'Low Stock',
                                default => 'In Stock',
                            };
                            $stockStyle = match ($stockLabel) {
                                'Out of Stock' => ['bg-red-50 text-red-600', 'bg-red-400', 'text-red-500'],
                                'Low Stock' => ['bg-amber-50 text-amber-700', 'bg-amber-400', 'text-amber-500'],
                                default => ['bg-emerald-50 text-emerald-700', 'bg-emerald-500', 'text-gray-700'],
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/40 transition-colors">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2.5">
                                    @if (!empty($product['product_image']))
                                        <img src="{{ $product['product_image'] }}" alt="{{ $product['name'] }}" class="w-8 h-8 rounded-lg object-cover flex-shrink-0 border border-gray-200 dark:border-gray-700" />
                                    @else
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-[10px] font-bold {{ $badgeBg }} {{ $badgeText }}"
                                            aria-hidden="true">
                                            {{ $product['initials'] }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-800 dark:text-gray-100 truncate">
                                            {{ $product['name'] }}</p>
                                        <p class=" text-[11px] text-gray-400 truncate">{{ $product['sku'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-gray-500 dark:text-gray-400 text-xs">{{ $product['brand'] }}
                            </td>
                            <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ $product['category'] }}</td>
                            <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ $product['unit'] }}</td>
                            <td class="px-5 py-3 text-end text-gray-500 dark:text-gray-400">
                                {{ $product['reorder_level'] }}</td>
                            <td class="px-5 py-3 text-end font-semibold {{ $stockStyle[2] }}">{{ $product['qty'] }}
                            </td>
                            <td class="px-5 py-3">
                                <span class="badge {{ $stockStyle[0] }}">
                                    <span class="w-1.5 h-1.5 rounded-full flex-shrink-0 {{ $stockStyle[1] }}"
                                        aria-hidden="true"></span>
                                    {{ $stockLabel }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-end">
                                <div class="flex items-center justify-end gap-1">
                                    <button type="button" title="Stock"
                                        wire:click="openStockMovementModal({{ $product['id'] }})"
                                        class="p-2 rounded-lg text-gray-400 hover:text-emerald-700 hover:bg-emerald-50 transition-colors"
                                        aria-label="Stock in {{ $product['name'] }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 7.5L12 3l8 4.5v9L12 21l-8-4.5v-9zm8 4.5l8-4.5M12 12v9M4 7.5l8 4.5" />
                                        </svg>
                                    </button>
                                    <a title="Edit" href="{{ route('products.edit', ['product' => $product['id']]) }}" wire:navigate
                                        class="p-2 rounded-lg text-gray-400 hover:text-brand-600 hover:bg-brand-50 transition-colors"
                                        aria-label="Edit {{ $product['name'] }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <button type="button" title="Delete"
                                        wire:click="openDeleteModal({{ $product['id'] }})"
                                        class="p-2 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors"
                                        aria-label="Delete {{ $product['name'] }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 3h5a1 1 0 011 1v3h-7V4a1 1 0 011-1z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M9 13h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No products found</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Try adjusting your search.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="space-y-3 p-3 lg:hidden">
            @forelse ($products as $product)
                @php
                    [$badgeBg, $badgeText] = $badgeColors[$product['category']] ?? ['bg-gray-100', 'text-gray-500'];

                    $stockLabel = match (true) {
                        $product['qty'] === 0 => 'Out of Stock',
                        $product['qty'] <= $product['reorder_level'] => 'Low Stock',
                        default => 'In Stock',
                    };
                    $stockShortLabel = $stockLabel;
                    $stockStyle = match ($stockLabel) {
                        'Out of Stock' => ['bg-red-50 text-red-600', 'bg-red-400'],
                        'Low Stock' => ['bg-amber-50 text-amber-700', 'bg-amber-400'],
                        default => ['bg-emerald-50 text-emerald-700', 'bg-emerald-500'],
                    };
                @endphp

                <div x-data="{ open: false }"
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <button type="button" @click="open = !open" class="w-full p-3.5 text-left">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex min-w-0 flex-1 items-center gap-3">
                                @if (!empty($product['product_image']))
                                    <img src="{{ $product['product_image'] }}" alt="{{ $product['name'] }}" class="h-10 w-10 flex-shrink-0 rounded-xl object-cover border border-gray-200 dark:border-gray-700" />
                                @else
                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl text-[10px] font-bold {{ $badgeBg }} {{ $badgeText }}"
                                        aria-hidden="true">
                                        {{ $product['initials'] }}
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $product['name'] }}</p>
                                    <div
                                        class="mt-1 flex min-w-0 items-center gap-1.5 text-[11px] text-gray-500 dark:text-gray-400">
                                        <span class="truncate">{{ $product['brand'] }}</span>
                                        <span class="h-1 w-1 rounded-full bg-gray-300"></span>
                                        <span class="truncate">{{ $product['category'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-shrink-0 items-center gap-2">
                                <span
                                    class="badge {{ $stockStyle[0] }} max-w-[8rem] whitespace-nowrap text-[9px] sm:text-[11px]">
                                    <span class="h-1.5 w-1.5 rounded-full flex-shrink-0 {{ $stockStyle[1] }}"
                                        aria-hidden="true"></span>
                                    <span class="inline-block sm:inline">{{ $stockLabel }}</span>
                                </span>
                                <svg class="h-4 w-4 text-gray-400 transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </button>

                    <div x-show="open" x-transition style="display:none"
                        class="border-t border-gray-100 px-3.5 py-3.5 dark:border-gray-700">
                        <dl class="grid grid-cols-2 gap-3 text-sm">
                            <div class="rounded-xl bg-gray-50 p-2.5 dark:bg-gray-700/60">
                                <dt class="text-[10px] font-semibold uppercase tracking-[0.12em] text-gray-400">SKU
                                </dt>
                                <dd class="mt-1 font-medium text-gray-700 dark:text-gray-200">{{ $product['sku'] }}
                                </dd>
                            </div>
                            <div class="rounded-xl bg-gray-50 p-2.5 dark:bg-gray-700/60">
                                <dt class="text-[10px] font-semibold uppercase tracking-[0.12em] text-gray-400">Unit
                                </dt>
                                <dd class="mt-1 font-medium text-gray-700 dark:text-gray-200">{{ $product['unit'] }}
                                </dd>
                            </div>
                            <div class="rounded-xl bg-gray-50 p-2.5 dark:bg-gray-700/60">
                                <dt class="text-[10px] font-semibold uppercase tracking-[0.12em] text-gray-400">Reorder
                                </dt>
                                <dd class="mt-1 font-medium text-gray-700 dark:text-gray-200">
                                    {{ $product['reorder_level'] }}</dd>
                            </div>
                            <div class="rounded-xl bg-gray-50 p-2.5 dark:bg-gray-700/60">
                                <dt class="text-[10px] font-semibold uppercase tracking-[0.12em] text-gray-400">Qty
                                </dt>
                                <dd class="mt-1 font-medium text-gray-700 dark:text-gray-200">{{ $product['qty'] }}
                                </dd>
                            </div>
                        </dl>

                        <div class="mt-3 flex gap-2">
                            <button type="button"
                                wire:click="openStockMovementModal({{ $product['id'] }})"
                                class="flex flex-1 items-center justify-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2.5 text-emerald-600 transition-colors hover:bg-emerald-100 dark:border-emerald-900/50 dark:bg-emerald-900/20 dark:text-emerald-300"
                                aria-label="Stock in {{ $product['name'] }}">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 7.5L12 3l8 4.5v9L12 21l-8-4.5v-9zm8 4.5l8-4.5M12 12v9M4 7.5l8 4.5" />
                                </svg>
                                <span class="text-[11px] font-semibold">Stock In</span>
                            </button>
                            <a href="{{ route('products.edit', ['product' => $product['id']]) }}" wire:navigate
                                class="flex flex-1 items-center justify-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-gray-600 transition-colors hover:border-brand-200 hover:text-brand-600 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300"
                                aria-label="Edit {{ $product['name'] }}">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span class="text-[11px] font-semibold">Edit</span>
                            </a>
                            <button type="button"
                                wire:click="openDeleteModal({{ $product['id'] }})"
                                class="flex flex-1 items-center justify-center gap-1.5 rounded-lg border border-red-100 bg-red-50 px-3 py-2.5 text-red-500 transition-colors hover:bg-red-100 dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-300"
                                aria-label="Delete {{ $product['name'] }}">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 3h5a1 1 0 011 1v3h-7V4a1 1 0 011-1z" />
                                </svg>
                                <span class="text-[11px] font-semibold">Delete</span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 px-5 py-10 text-center dark:border-gray-700 dark:bg-gray-800/60">
                    <div class="flex flex-col items-center justify-center gap-3">
                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M9 13h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No products found</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Try adjusting your search.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        @if (!empty($products))
            @php
                $totalPages = max(1, (int) ceil($totalProducts / max(1, $perPage)));
                $startItem = $totalProducts === 0 ? 0 : (($currentPage - 1) * $perPage) + 1;
                $endItem = min($currentPage * $perPage, $totalProducts);
            @endphp

            <div
                class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-t border-gray-100 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400">Showing
                    <strong>{{ $startItem }}</strong>–<strong>{{ $endItem }}</strong> of <strong>{{ $totalProducts }}</strong></p>
                <div class="flex items-center gap-1">
                    <button type="button" wire:click="previousPage" @disabled($currentPage <= 1)
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-40 disabled:cursor-not-allowed min-h-[36px]">
                        ← Prev
                    </button>

                    @for ($page = 1; $page <= $totalPages; $page++)
                        <button type="button" wire:click="goToPage({{ $page }})"
                            class="w-9 h-9 rounded-lg text-xs font-semibold {{ $page === $currentPage ? 'bg-brand-600 text-white' : 'bg-white text-gray-600 border border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                            {{ $page }}
                        </button>
                    @endfor

                    <button type="button" wire:click="nextPage" @disabled($currentPage >= $totalPages)
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-40 disabled:cursor-not-allowed min-h-[36px]">
                        Next →
                    </button>
                </div>
            </div>
        @endif
    </section>
</div>
