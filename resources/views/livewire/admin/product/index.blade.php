@php
    // ── Dummy data for UI only — swap for the real Eloquent query later ──
    // Product::with(['category', 'stockBalance'])->paginate(8)
    $categories = ['Electronics', 'Apparel', 'Food & Beverage', 'Furniture', 'Others'];

    $products = [
        ['name' => 'Samsung 55" QLED TV',      'sku' => 'TV-SAM-001', 'brand' => 'Samsung',   'category' => 'Electronics',     'unit' => 'Piece', 'reorder_level' => 10, 'qty' => 42,  'initials' => 'SA'],
        ['name' => 'iPhone 16 Pro 256GB',       'sku' => 'PH-APL-014', 'brand' => 'Apple',     'category' => 'Electronics',     'unit' => 'Piece', 'reorder_level' => 10, 'qty' => 15,  'initials' => 'IP'],
        ['name' => 'Galaxy Tab S9',             'sku' => 'TB-SAM-009', 'brand' => 'Samsung',   'category' => 'Electronics',     'unit' => 'Piece', 'reorder_level' => 10, 'qty' => 3,   'initials' => 'GT'],
        ['name' => 'Air Jordan Retro OG',       'sku' => 'AP-NIK-021', 'brand' => 'Nike',      'category' => 'Apparel',         'unit' => 'Piece', 'reorder_level' => 15, 'qty' => 58,  'initials' => 'AJ'],
        ['name' => "Levi's 501 Original",       'sku' => 'AP-LEV-005', 'brand' => "Levi's",    'category' => 'Apparel',         'unit' => 'Piece', 'reorder_level' => 15, 'qty' => 7,   'initials' => 'LV'],
        ['name' => 'Nescafé Gold 200g',         'sku' => 'FB-NES-002', 'brand' => 'Nestlé',    'category' => 'Food & Beverage', 'unit' => 'Box',   'reorder_level' => 30, 'qty' => 200, 'initials' => 'NG'],
        ['name' => 'Yakult Probiotic 80ml',     'sku' => 'FB-YAK-011', 'brand' => 'Yakult',    'category' => 'Food & Beverage', 'unit' => 'Pack',  'reorder_level' => 40, 'qty' => 0,   'initials' => 'YK'],
        ['name' => 'Monobloc Chair White',      'sku' => 'FN-MNB-004', 'brand' => 'Wilcon',    'category' => 'Furniture',       'unit' => 'Piece', 'reorder_level' => 30, 'qty' => 260, 'initials' => 'MC'],
        ['name' => 'Ergonomic Desk Chair',      'sku' => 'FN-ERG-013', 'brand' => 'Wilcon',    'category' => 'Furniture',       'unit' => 'Piece', 'reorder_level' => 8,  'qty' => 2,   'initials' => 'ED'],
        ['name' => 'JBL Flip 6 Speaker',        'sku' => 'EL-JBL-018', 'brand' => 'JBL',       'category' => 'Electronics',     'unit' => 'Piece', 'reorder_level' => 12, 'qty' => 33,  'initials' => 'JB'],
    ];

    $badgeColors = [
        'Electronics'     => ['bg-brand-50',   'text-brand-600'],
        'Apparel'         => ['bg-amber-50',   'text-amber-600'],
        'Food & Beverage' => ['bg-violet-50',  'text-violet-600'],
        'Furniture'       => ['bg-red-50',     'text-red-500'],
        'Others'          => ['bg-gray-100',   'text-gray-500'],
    ];

    $totalProducts = count($products);
    $lowStockCount = count(array_filter($products, fn ($p) => $p['qty'] > 0 && $p['qty'] <= $p['reorder_level']));
    $outOfStockCount = count(array_filter($products, fn ($p) => $p['qty'] === 0));
@endphp

<div class="space-y-6">

    {{-- Heading --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Products</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $totalProducts }} products across {{ count($categories) }} categories</p>
        </div>
        <button type="button"
                class="flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm min-h-[44px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Product
        </button>
    </div>

    {{-- Mini stats --}}
    <section aria-label="Product summary">
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-card flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center flex-shrink-0" aria-hidden="true">
                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide">Total</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $totalProducts }}</p>
                </div>
            </div>

            <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-card flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center flex-shrink-0" aria-hidden="true">
                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide">Categories</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ count($categories) }}</p>
                </div>
            </div>

            <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-card flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0" aria-hidden="true">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide">Low Stock</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $lowStockCount }}</p>
                </div>
            </div>

            <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-card flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0" aria-hidden="true">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide">Out of Stock</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $outOfStockCount }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Table card --}}
    <section aria-label="Product list" class="bg-white dark:bg-gray-800 rounded-2xl shadow-card">

        {{-- Filter bar (reference-style compact) --}}
        <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex flex-col lg:flex-row lg:items-center gap-3">
            <div class="relative flex-1 max-w-[28rem]">
                <svg class="absolute start-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input type="search" placeholder="Search name or SKU…"
                       class="w-full ps-9 pe-4 py-2.5 text-sm bg-transparent border border-gray-200 rounded-[0.95rem] placeholder:text-gray-500 text-gray-700 focus:border-brand-400 focus:ring-2 focus:ring-brand-100 outline-none transition-colors min-h-[2.75rem] shadow-none"
                       aria-label="Search products">
            </div>

            <select class="field !w-auto min-h-[2.75rem] py-2 text-sm" aria-label="Filter by category">
                <option value="">All categories</option>
                @foreach ($categories as $category)
                    <option>{{ $category }}</option>
                @endforeach
            </select>

            <select class="field !w-auto min-h-[2.75rem] py-2 text-sm" aria-label="Filter by stock level">
                <option value="">All stock levels</option>
                <option>In Stock</option>
                <option>Low Stock</option>
                <option>Out of Stock</option>
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
                        <th scope="col" class="px-5 py-3 font-semibold text-gray-500 dark:text-gray-400 text-[11px] uppercase tracking-[0.12em]">Product</th>
                        <th scope="col" class="px-5 py-3 font-semibold text-gray-500 dark:text-gray-400 text-[11px] uppercase tracking-[0.12em]">SKU</th>
                        <th scope="col" class="px-5 py-3 font-semibold text-gray-500 dark:text-gray-400 text-[11px] uppercase tracking-[0.12em]">Category</th>
                        <th scope="col" class="px-5 py-3 font-semibold text-gray-500 dark:text-gray-400 text-[11px] uppercase tracking-[0.12em]">Unit</th>
                        <th scope="col" class="px-5 py-3 font-semibold text-gray-500 dark:text-gray-400 text-[11px] uppercase tracking-[0.12em] text-end">Reorder Lvl</th>
                        <th scope="col" class="px-5 py-3 font-semibold text-gray-500 dark:text-gray-400 text-[11px] uppercase tracking-[0.12em] text-end">Qty on Hand</th>
                        <th scope="col" class="px-5 py-3 font-semibold text-gray-500 dark:text-gray-400 text-[11px] uppercase tracking-[0.12em]">Status</th>
                        <th scope="col" class="px-5 py-3 font-semibold text-gray-500 dark:text-gray-400 text-[11px] uppercase tracking-[0.12em] text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @foreach ($products as $product)
                        @php
                            [$badgeBg, $badgeText] = $badgeColors[$product['category']] ?? ['bg-gray-100', 'text-gray-500'];

                            $stockLabel = match (true) {
                                $product['qty'] === 0 => 'Out of Stock',
                                $product['qty'] <= $product['reorder_level'] => 'Low Stock',
                                default => 'In Stock',
                            };
                            $stockStyle = match ($stockLabel) {
                                'Out of Stock' => ['bg-red-50 text-red-600', 'bg-red-400', 'text-red-500'],
                                'Low Stock'    => ['bg-amber-50 text-amber-700', 'bg-amber-400', 'text-amber-500'],
                                default        => ['bg-emerald-50 text-emerald-700', 'bg-emerald-500', 'text-gray-700'],
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/40 transition-colors">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-[10px] font-bold {{ $badgeBg }} {{ $badgeText }}" aria-hidden="true">
                                        {{ $product['initials'] }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-800 dark:text-gray-100 truncate">{{ $product['name'] }}</p>
                                        <p class="mt-1 text-[11px] text-gray-400 truncate">{{ $product['sku'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-gray-500 dark:text-gray-400 text-xs">{{ $product['brand'] }}</td>
                            <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ $product['category'] }}</td>
                            <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ $product['unit'] }}</td>
                            <td class="px-5 py-3 text-end text-gray-500 dark:text-gray-400">{{ $product['reorder_level'] }}</td>
                            <td class="px-5 py-3 text-end font-semibold {{ $stockStyle[2] }}">{{ $product['qty'] }}</td>
                            <td class="px-5 py-3">
                                <span class="badge {{ $stockStyle[0] }}">
                                    <span class="w-1.5 h-1.5 rounded-full flex-shrink-0 {{ $stockStyle[1] }}" aria-hidden="true"></span>
                                    {{ $stockLabel }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-end">
                                <div class="flex items-center justify-end gap-1">
                                    <button type="button" class="p-2 rounded-lg text-gray-400 hover:text-emerald-700 hover:bg-emerald-50 transition-colors" aria-label="Stock in {{ $product['name'] }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7.5L12 3l8 4.5v9L12 21l-8-4.5v-9zm8 4.5l8-4.5M12 12v9M4 7.5l8 4.5"/>
                                        </svg>
                                    </button>
                                    <button type="button" class="p-2 rounded-lg text-gray-400 hover:text-brand-600 hover:bg-brand-50 transition-colors" aria-label="Edit {{ $product['name'] }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button type="button" class="p-2 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors" aria-label="Delete {{ $product['name'] }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 3h5a1 1 0 011 1v3h-7V4a1 1 0 011-1z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="space-y-3 p-3 lg:hidden">
            @foreach ($products as $product)
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
                        'Low Stock'    => ['bg-amber-50 text-amber-700', 'bg-amber-400'],
                        default        => ['bg-emerald-50 text-emerald-700', 'bg-emerald-500'],
                    };
                @endphp

                <div x-data="{ open: false }" class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <button type="button" @click="open = !open" class="w-full p-3.5 text-left">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex min-w-0 flex-1 items-center gap-3">
                                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl text-[10px] font-bold {{ $badgeBg }} {{ $badgeText }}" aria-hidden="true">
                                    {{ $product['initials'] }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ $product['name'] }}</p>
                                    <div class="mt-1 flex min-w-0 items-center gap-1.5 text-[11px] text-gray-500 dark:text-gray-400">
                                        <span class="truncate">{{ $product['brand'] }}</span>
                                        <span class="h-1 w-1 rounded-full bg-gray-300"></span>
                                        <span class="truncate">{{ $product['category'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-shrink-0 items-center gap-2">
                                <span class="badge {{ $stockStyle[0] }} max-w-[8rem] whitespace-nowrap text-[9px] sm:text-[11px]">
                                    <span class="h-1.5 w-1.5 rounded-full flex-shrink-0 {{ $stockStyle[1] }}" aria-hidden="true"></span>
                                    <span class="inline-block sm:inline">{{ $stockLabel }}</span>
                                </span>
                                <svg class="h-4 w-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </button>

                    <div x-show="open" x-transition style="display:none" class="border-t border-gray-100 px-3.5 py-3.5 dark:border-gray-700">
                        <dl class="grid grid-cols-2 gap-3 text-sm">
                            <div class="rounded-xl bg-gray-50 p-2.5 dark:bg-gray-700/60">
                                <dt class="text-[10px] font-semibold uppercase tracking-[0.12em] text-gray-400">SKU</dt>
                                <dd class="mt-1 font-medium text-gray-700 dark:text-gray-200">{{ $product['sku'] }}</dd>
                            </div>
                            <div class="rounded-xl bg-gray-50 p-2.5 dark:bg-gray-700/60">
                                <dt class="text-[10px] font-semibold uppercase tracking-[0.12em] text-gray-400">Unit</dt>
                                <dd class="mt-1 font-medium text-gray-700 dark:text-gray-200">{{ $product['unit'] }}</dd>
                            </div>
                            <div class="rounded-xl bg-gray-50 p-2.5 dark:bg-gray-700/60">
                                <dt class="text-[10px] font-semibold uppercase tracking-[0.12em] text-gray-400">Reorder</dt>
                                <dd class="mt-1 font-medium text-gray-700 dark:text-gray-200">{{ $product['reorder_level'] }}</dd>
                            </div>
                            <div class="rounded-xl bg-gray-50 p-2.5 dark:bg-gray-700/60">
                                <dt class="text-[10px] font-semibold uppercase tracking-[0.12em] text-gray-400">Qty</dt>
                                <dd class="mt-1 font-medium text-gray-700 dark:text-gray-200">{{ $product['qty'] }}</dd>
                            </div>
                        </dl>

                        <div class="mt-3 flex gap-2">
                            <button type="button" class="flex flex-1 items-center justify-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2.5 text-emerald-600 transition-colors hover:bg-emerald-100 dark:border-emerald-900/50 dark:bg-emerald-900/20 dark:text-emerald-300" aria-label="Stock in {{ $product['name'] }}">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7.5L12 3l8 4.5v9L12 21l-8-4.5v-9zm8 4.5l8-4.5M12 12v9M4 7.5l8 4.5"/>
                                </svg>
                                <span class="text-[11px] font-semibold">Stock In</span>
                            </button>
                            <button type="button" class="flex flex-1 items-center justify-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-gray-600 transition-colors hover:border-brand-200 hover:text-brand-600 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300" aria-label="Edit {{ $product['name'] }}">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span class="text-[11px] font-semibold">Edit</span>
                            </button>
                            <button type="button" class="flex flex-1 items-center justify-center gap-1.5 rounded-lg border border-red-100 bg-red-50 px-3 py-2.5 text-red-500 transition-colors hover:bg-red-100 dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-300" aria-label="Delete {{ $product['name'] }}">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 3h5a1 1 0 011 1v3h-7V4a1 1 0 011-1z"/></svg>
                                <span class="text-[11px] font-semibold">Delete</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination (display only, not wired) --}}
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-t border-gray-100 dark:border-gray-700">
            <p class="text-xs text-gray-500 dark:text-gray-400">Showing <strong>1</strong>–<strong>{{ $totalProducts }}</strong> of <strong>{{ $totalProducts }}</strong></p>
            <div class="flex items-center gap-1">
                <button type="button" disabled
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-40 disabled:cursor-not-allowed min-h-[36px]">
                    ← Prev
                </button>
                <button type="button" class="w-9 h-9 rounded-lg text-xs font-semibold bg-brand-600 text-white">1</button>
                <button type="button" disabled
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-40 disabled:cursor-not-allowed min-h-[36px]">
                    Next →
                </button>
            </div>
        </div>
    </section>
</div>
