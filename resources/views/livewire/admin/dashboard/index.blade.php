<div class="space-y-6">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-600">Operations overview</p>
            <h1 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">Juntech dashboard</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Live activity and financial signals for {{ $monthName }}.</p>
        </div>
    </div>

    <section aria-label="Business summary" class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($stats as $stat)
            @php $tone = ['brand' => ['bg' => 'bg-brand-50 dark:bg-brand-900/20', 'text' => 'text-brand-600'], 'red' => ['bg' => 'bg-red-50 dark:bg-red-900/20', 'text' => 'text-red-500'], 'emerald' => ['bg' => 'bg-emerald-50 dark:bg-emerald-900/20', 'text' => 'text-emerald-600'], 'amber' => ['bg' => 'bg-amber-50 dark:bg-amber-900/20', 'text' => 'text-amber-600']][$stat['tone']]; @endphp
            <a href="{{ route($stat['route']) }}" class="group rounded-2xl bg-white p-5 shadow-card transition-transform hover:-translate-y-0.5 dark:bg-gray-800">
                <div class="flex items-start justify-between gap-3">
                    <div><p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $stat['label'] }}</p><p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ !empty($stat['currency']) ? '₱'.number_format($stat['value'], 2) : number_format($stat['value']) }}</p><p class="mt-1.5 text-xs text-gray-400">{{ $stat['note'] }}</p></div>
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $tone['bg'] }} {{ $tone['text'] }}"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $stat['tone'] === 'brand' ? 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z' : ($stat['tone'] === 'red' ? 'M12 9v2m0 4h.01M5.5 19h13a1.5 1.5 0 001.3-2.25l-6.5-11.25a1.5 1.5 0 00-2.6 0L4.2 16.75A1.5 1.5 0 005.5 19z' : ($stat['tone'] === 'emerald' ? 'M3 7h18M5 7v10h14V7M8 7V5h8v2M9 11h6' : 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 10v-1')) }}" /></svg></span>
                </div>
            </a>
        @endforeach
    </section>

    <section class="grid grid-cols-1 gap-4 lg:grid-cols-3" aria-label="Monthly performance">
        @foreach ([['label' => 'Customer Collections', 'value' => $monthlyCollections, 'route' => 'billings.index', 'color' => 'bg-brand-500'], ['label' => 'Vendo Owner Share', 'value' => $vendoCollections, 'route' => 'vendo-partners.index', 'color' => 'bg-emerald-500'], ['label' => 'Omada Sales', 'value' => $omadaSales, 'route' => 'omada.index', 'color' => 'bg-amber-500']] as $metric)
            <a href="{{ route($metric['route']) }}" class="rounded-2xl bg-white p-5 shadow-card dark:bg-gray-800"><div class="flex items-center justify-between gap-3"><div><p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $metric['label'] }}</p><p class="mt-2 text-xl font-bold text-gray-900 dark:text-white">₱{{ number_format($metric['value'], 2) }}</p></div><span class="rounded-full bg-gray-100 px-2.5 py-1 text-[11px] font-semibold text-gray-500">{{ $monthName }}</span></div><div class="mt-4 h-2 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700"><div class="h-full w-2/3 rounded-full {{ $metric['color'] }}"></div></div></a>
        @endforeach
    </section>

    <section aria-labelledby="stock-chart-heading" class="rounded-2xl bg-white p-5 shadow-card dark:bg-gray-800">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 id="stock-chart-heading" class="text-base font-semibold text-gray-900 dark:text-white">Stock Movement</h2>
                <p class="mt-0.5 text-xs text-gray-400">Items in vs. out — last 7 days</p>
            </div>
            <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                <span class="flex items-center gap-1.5"><span class="h-3 w-3 rounded-sm bg-brand-500" aria-hidden="true"></span>Stock In</span>
                <span class="flex items-center gap-1.5"><span class="h-3 w-3 rounded-sm bg-amber-400" aria-hidden="true"></span>Stock Out</span>
            </div>
        </div>

        <div class="mt-3 flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
            <span>Total In <strong class="text-gray-800 dark:text-gray-200">{{ number_format($totalIn) }}</strong></span>
            <span>Total Out <strong class="text-gray-800 dark:text-gray-200">{{ number_format($totalOut) }}</strong></span>
            <span class="{{ $totalIn - $totalOut >= 0 ? 'text-emerald-600' : 'text-red-500' }}">Net <strong>{{ $totalIn - $totalOut >= 0 ? '+' : '' }}{{ number_format($totalIn - $totalOut) }}</strong></span>
        </div>

        <div class="relative mt-4 h-48 sm:h-56" role="img" aria-label="Stock in and stock out quantities for the last seven days">
            <div class="pointer-events-none absolute inset-0 flex flex-col justify-between" aria-hidden="true">
                <div class="border-t border-dashed border-gray-200 dark:border-gray-700"></div>
                <div class="border-t border-dashed border-gray-200 dark:border-gray-700"></div>
                <div class="border-t border-dashed border-gray-200 dark:border-gray-700"></div>
                <div class="border-t border-gray-200 dark:border-gray-700"></div>
            </div>
            <div class="relative flex h-full items-stretch justify-between gap-1 sm:gap-2">
                @foreach ($stockChart as $day)
                    <div class="group flex h-full min-w-0 flex-1 flex-col items-center gap-1.5" tabindex="0" aria-label="{{ $day['day'] }}: {{ $day['in'] }} in, {{ $day['out'] }} out">
                        <div class="relative flex h-full w-full items-end justify-center gap-0.5 sm:gap-1">
                            <div class="absolute bottom-full z-10 mb-2 hidden whitespace-nowrap rounded-md bg-gray-900 px-2 py-1 text-[10px] text-white shadow-lg group-hover:block group-focus:block">{{ $day['day'] }}: In {{ $day['in'] }} · Out {{ $day['out'] }}</div>
                            <div class="w-5 rounded-t-md bg-gradient-to-t from-brand-600 to-brand-400 sm:w-6 lg:w-8" style="height: {{ max(0, $day['in'] / $stockChartMax * 100) }}%"></div>
                            <div class="w-5 rounded-t-md bg-gradient-to-t from-amber-500 to-amber-300 sm:w-6 lg:w-8" style="height: {{ max(0, $day['out'] / $stockChartMax * 100) }}%"></div>
                        </div>
                        <span class="shrink-0 text-[10px] text-gray-400">{{ $day['day'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-4 xl:grid-cols-2">
        <div class="rounded-2xl bg-white p-5 shadow-card dark:bg-gray-800"><div class="mb-4 flex items-center justify-between"><div><h2 class="text-base font-semibold text-gray-900 dark:text-white">Recent Collections</h2><p class="mt-0.5 text-xs text-gray-400">Latest customer payments received</p></div><a href="{{ route('billings.index') }}" class="text-xs font-semibold text-brand-600">View all</a></div><div class="space-y-3">@forelse ($recentPayments as $payment)<div class="flex items-center gap-3 border-b border-gray-100 pb-3 last:border-0 last:pb-0 dark:border-gray-700"><div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-xs font-bold text-emerald-600">₱</div><div class="min-w-0 flex-1"><p class="truncate text-sm font-medium text-gray-800 dark:text-gray-100">{{ $payment['customer'] }}</p><p class="text-xs text-gray-400">{{ $payment['date'] }} · {{ $payment['method'] }}</p></div><p class="text-sm font-bold text-emerald-600">₱{{ number_format($payment['amount'], 2) }}</p></div>@empty<p class="py-6 text-center text-sm text-gray-400">No payments recorded yet.</p>@endforelse</div></div>
        <div class="rounded-2xl bg-white p-5 shadow-card dark:bg-gray-800"><div class="mb-4 flex items-center justify-between"><div><h2 class="text-base font-semibold text-gray-900 dark:text-white">Inventory Activity</h2><p class="mt-0.5 text-xs text-gray-400">Latest stock movements</p></div><a href="{{ route('stock-movements.index') }}" class="text-xs font-semibold text-brand-600">View all</a></div><div class="space-y-3">@forelse ($recentMovements as $movement)<div class="flex items-center gap-3 border-b border-gray-100 pb-3 last:border-0 last:pb-0 dark:border-gray-700"><div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600">↕</div><div class="min-w-0 flex-1"><p class="truncate text-sm font-medium text-gray-800 dark:text-gray-100">{{ $movement['product'] }}</p><p class="text-xs text-gray-400">{{ $movement['type'] }} · {{ $movement['date'] }}</p></div><p class="text-sm font-bold text-brand-600">{{ $movement['quantity'] }}</p></div>@empty<p class="py-6 text-center text-sm text-gray-400">No stock movements recorded yet.</p>@endforelse</div></div>
    </section>

    <section class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-brand-100 bg-brand-50/60 p-5 dark:border-brand-900/40 dark:bg-brand-900/10"><div><h2 class="text-base font-semibold text-gray-900 dark:text-white">Operations shortcuts</h2><p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Low stock alerts: <strong class="text-red-500">{{ $lowStockCount }}</strong> products need attention.</p></div><div class="flex flex-wrap gap-2"><a href="{{ route('products.index') }}" class="rounded-lg bg-white px-3 py-2 text-xs font-semibold text-gray-700 shadow-sm hover:text-brand-600 dark:bg-gray-800 dark:text-gray-200">Inventory</a><a href="{{ route('expenses.index') }}" class="rounded-lg bg-white px-3 py-2 text-xs font-semibold text-gray-700 shadow-sm hover:text-brand-600 dark:bg-gray-800 dark:text-gray-200">Expenses</a><a href="{{ route('vendo-partners.index') }}" class="rounded-lg bg-white px-3 py-2 text-xs font-semibold text-gray-700 shadow-sm hover:text-brand-600 dark:bg-gray-800 dark:text-gray-200">Vendo</a><a href="{{ route('omada.index') }}" class="rounded-lg bg-white px-3 py-2 text-xs font-semibold text-gray-700 shadow-sm hover:text-brand-600 dark:bg-gray-800 dark:text-gray-200">Omada</a></div></section>
</div>
