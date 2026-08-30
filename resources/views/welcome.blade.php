<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>StockMaster — Inventory Pro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#f8f9fc] dark:bg-[#0f1117] text-gray-800 dark:text-gray-100">

<a href="#main" class="skip-link">Skip to main content</a>

{{-- ── NAV ── --}}
<header class="sticky top-0 z-30 bg-white/80 dark:bg-[#1e2133]/80 backdrop-blur border-b border-gray-100 dark:border-gray-800">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 flex items-center justify-between h-16">

        {{-- Logo --}}
        <a href="/" class="flex items-center gap-2.5 group" aria-label="StockMaster home">
            <div class="w-8 h-8 rounded-xl bg-brand-600 flex items-center justify-center flex-shrink-0 group-hover:bg-brand-700 transition-colors">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div class="leading-tight">
                <span class="text-sm font-bold text-gray-900 dark:text-white">StockMaster</span>
                <span class="block text-[10px] text-gray-400 font-medium tracking-widest uppercase -mt-0.5">Inventory Pro</span>
            </div>
        </a>

        {{-- Nav actions --}}
        <div class="flex items-center gap-2">
            {{-- Dark mode --}}
            <button type="button" data-theme-toggle
                    class="dark-toggle text-gray-500 hover:bg-gray-100 dark:hover:bg-white/10"
                    aria-label="Switch to dark mode">
                <svg class="icon-sun w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <svg class="icon-moon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>

            @if (Route::has('login'))
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2 text-sm font-semibold text-brand-600 hover:text-brand-700 transition-colors">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">
                        Sign in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="px-4 py-2 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-xl transition-colors shadow-sm">
                            Get started
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</header>

<main id="main" tabindex="-1">

    {{-- ── HERO ── --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 pt-20 pb-16 text-center">

        {{-- Badge --}}
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-brand-50 dark:bg-brand-900/30 border border-brand-100 dark:border-brand-800 text-brand-600 dark:text-brand-400 text-xs font-semibold mb-6">
            <span class="w-1.5 h-1.5 rounded-full bg-brand-500 animate-pulse"></span>
            Now available — StockMaster Inventory Pro
        </div>

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white leading-tight tracking-tight max-w-3xl mx-auto">
            Inventory that
            <span class="text-brand-600"> works as hard</span>
            as you do
        </h1>

        <p class="mt-6 text-lg text-gray-500 dark:text-gray-400 max-w-xl mx-auto leading-relaxed">
            Track stock, manage suppliers, process purchase orders, and move inventory across warehouses — all in one place.
        </p>

        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
            @if (Route::has('register'))
                <a href="{{ route('register') }}"
                   class="w-full sm:w-auto px-6 py-3 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-xl transition-colors shadow-sm min-h-[44px] flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Start for free
                </a>
            @endif
            @if (Route::has('login'))
                <a href="{{ route('login') }}"
                   class="w-full sm:w-auto px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 bg-white dark:bg-white/5 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-white/10 rounded-xl transition-colors min-h-[44px] flex items-center justify-center">
                    Sign in to your account
                </a>
            @endif
        </div>

        {{-- Stats --}}
        <div class="mt-14 grid grid-cols-2 sm:grid-cols-4 gap-px bg-gray-100 dark:bg-gray-800 rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800">
            @foreach ([
                ['value' => '10k+',   'label' => 'Products tracked'],
                ['value' => '99.9%',  'label' => 'Uptime SLA'],
                ['value' => '500+',   'label' => 'Warehouses'],
                ['value' => 'PH-first','label' => 'Built for the Philippines'],
            ] as $stat)
            <div class="bg-white dark:bg-[#1e2133] px-6 py-5 text-center">
                <p class="text-2xl font-bold text-brand-600">{{ $stat['value'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    {{-- ── FEATURES ── --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 py-16">
        <div class="text-center mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Everything you need to run your inventory</h2>
            <p class="mt-3 text-gray-500 dark:text-gray-400 max-w-lg mx-auto text-sm">
                Built for Philippine businesses — from sari-sari stores to multi-warehouse distributors.
            </p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ([
                [
                    'icon'  => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                    'title' => 'Product Catalog',
                    'desc'  => 'Manage thousands of SKUs with categories, variants, and real-time stock levels.',
                    'color' => 'bg-brand-50 dark:bg-brand-900/20 text-brand-600',
                ],
                [
                    'icon'  => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                    'title' => 'Purchase Orders',
                    'desc'  => 'Create, approve, and track POs from draft to delivery with full audit trail.',
                    'color' => 'bg-violet-50 dark:bg-violet-900/20 text-violet-600',
                ],
                [
                    'icon'  => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
                    'title' => 'Stock Movements',
                    'desc'  => 'Record inbound, outbound, and inter-warehouse transfers with zero data loss.',
                    'color' => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600',
                ],
                [
                    'icon'  => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
                    'title' => 'Supplier Management',
                    'desc'  => 'Keep supplier contacts, payment terms, and delivery records in one place.',
                    'color' => 'bg-amber-50 dark:bg-amber-900/20 text-amber-600',
                ],
                [
                    'icon'  => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                    'title' => 'Warehouse Zones',
                    'desc'  => 'Organise stock by location — Luzon, Visayas, Mindanao, or any custom zone.',
                    'color' => 'bg-sky-50 dark:bg-sky-900/20 text-sky-600',
                ],
                [
                    'icon'  => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                    'title' => 'Low Stock Alerts',
                    'desc'  => 'Get notified before you run out — set thresholds per product, per warehouse.',
                    'color' => 'bg-rose-50 dark:bg-rose-900/20 text-rose-600',
                ],
            ] as $feature)
            <div class="bg-white dark:bg-[#1e2133] rounded-2xl p-6 border border-gray-100 dark:border-gray-800 hover:shadow-card transition-shadow">
                <div class="w-10 h-10 rounded-xl {{ $feature['color'] }} flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1.5">{{ $feature['title'] }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">{{ $feature['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    {{-- ── CTA ── --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 pb-20">
        <div class="bg-brand-600 rounded-2xl px-8 py-12 text-center relative overflow-hidden">
            {{-- Subtle radial glow --}}
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(255,255,255,0.1),transparent_60%)] pointer-events-none"></div>

            <h2 class="text-2xl sm:text-3xl font-bold text-white relative">
                Ready to take control of your stock?
            </h2>
            <p class="mt-3 text-brand-200 text-sm max-w-md mx-auto relative">
                Join businesses across the Philippines using StockMaster to move inventory smarter.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3 relative">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="w-full sm:w-auto px-6 py-3 text-sm font-semibold text-brand-600 bg-white hover:bg-brand-50 rounded-xl transition-colors shadow-sm min-h-[44px] flex items-center justify-center">
                        Create a free account
                    </a>
                @endif
                @if (Route::has('login'))
                    <a href="{{ route('login') }}"
                       class="w-full sm:w-auto px-6 py-3 text-sm font-semibold text-white border border-white/30 hover:bg-white/10 rounded-xl transition-colors min-h-[44px] flex items-center justify-center">
                        Sign in
                    </a>
                @endif
            </div>
        </div>
    </section>

</main>

{{-- ── FOOTER ── --}}
<footer class="border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-[#1e2133]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-400">
        <div class="flex items-center gap-2">
            <div class="w-5 h-5 rounded-lg bg-brand-600 flex items-center justify-center flex-shrink-0" aria-hidden="true">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <span>StockMaster Inventory Pro</span>
        </div>
        <span>Laravel v{{ Illuminate\Foundation\Application::VERSION }} · PHP v{{ PHP_VERSION }}</span>
    </div>
</footer>

</body>
</html>
