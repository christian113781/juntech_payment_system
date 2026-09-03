{{-- resources/views/components/sidebar.blade.php --}}
<aside id="sidebar" aria-label="Primary navigation"
    class="bg-white dark:bg-gray-800 flex flex-col shadow-card flex-shrink-0 relative z-30"
    :class="{ collapsed: sidebarCollapsed, 'mobile-open': mobileOpen }">

    {{-- Logo --}}
    <div class="logo-wrap flex items-center gap-3 px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex-shrink-0">
        <div class="w-9 h-9 rounded-xl bg-brand-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
        </div>
        <div class="logo-text min-w-0">
            <p class="text-sm font-bold text-gray-900 dark:text-white leading-tight whitespace-nowrap">StockMaster</p>
            <p class="text-[10px] text-gray-400 font-medium tracking-wide uppercase">Juntech System</p>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 sidebar-scroll py-3 px-2 overflow-y-auto" aria-label="Primary">

        <p
            class="nav-section-label px-3 mb-2 mt-4 first:mt-0 text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
            Overview
        </p>
        <ul class="space-y-1">

            <li>
                <a href="{{ route('dashboard') }}"
                    class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors
                  {{ request()->routeIs('dashboard') ? 'bg-brand-50 text-brand-700 dark:bg-gray-700 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                    @if (request()->routeIs('dashboard')) aria-current="page" @endif>
                    <svg class="w-5 h-5 flex-shrink-0 transition duration-75
                      {{ request()->routeIs('dashboard') ? 'text-brand-600 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="nav-label truncate">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('areas.index') }}"
                    class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors
                  {{ request()->routeIs('areas.*') ? 'bg-brand-50 text-brand-700 dark:bg-gray-700 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                    @if (request()->routeIs('areas.*')) aria-current="page" @endif>
                    <svg class="w-5 h-5 flex-shrink-0 transition duration-75
                      {{ request()->routeIs('areas.*') ? 'text-brand-600 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 18l-6 2V6l6-2 6 2 6-2v14l-6 2-6-2zm0-14v14m6-12v14" />
                    </svg>
                    <span class="nav-label truncate">Areas</span>
                </a>
            </li>

        </ul>


        <p
            class="nav-section-label px-3 mb-2 mt-4 text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
            Billing & Customers
        </p>
        <ul class="space-y-0.5">

            <li>
                <a href="{{ route('customers.index') }}"
                    class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors
                  {{ request()->routeIs('customers.index') ? 'bg-brand-50 text-brand-700 dark:bg-gray-700 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                    @if (request()->routeIs('customers.index')) aria-current="page" @endif>
                    <svg class="w-5 h-5 flex-shrink-0 transition duration-75
                      {{ request()->routeIs('customers.index') ? 'text-brand-600 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z" />
                    </svg>
                    <span class="nav-label truncate">Customers</span>
                </a>
            </li>



            <li>
                <a href="{{ route('billings.index') }}"
                    class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors
                  {{ request()->routeIs('billings.index') ? 'bg-brand-50 text-brand-700 dark:bg-gray-700 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                    @if (request()->routeIs('billings.index')) aria-current="page" @endif>
                    <svg class="w-5 h-5 flex-shrink-0 transition duration-75
                      {{ request()->routeIs('billings.index') ? 'text-brand-600 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4V7m-9 10h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span class="nav-label truncate">Billing</span>
                </a>
            </li>

        </ul>

        <p
            class="nav-section-label px-3 mb-2 mt-4 text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
            Inventory
        </p>
        <ul class="space-y-0.5">

            <li>
                <a href="{{ route('products.index') }}"
                    class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors
                  {{ request()->routeIs('products.index') ? 'bg-brand-50 text-brand-700 dark:bg-gray-700 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                    @if (request()->routeIs('products.index')) aria-current="page" @endif>
                    <svg class="w-5 h-5 flex-shrink-0 transition duration-75
                      {{ request()->routeIs('products') ? 'text-brand-600 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span class="nav-label truncate">Products</span>
                </a>
            </li>



            <li>
                <a href="{{ route('stock-movements.index') }}"
                    class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors
                  {{ request()->routeIs('stock-movements.*') ? 'bg-brand-50 text-brand-700 dark:bg-gray-700 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                    @if (request()->routeIs('stock-movements.*')) aria-current="page" @endif>
                    <svg class="w-5 h-5 flex-shrink-0 transition duration-75
                      {{ request()->routeIs('stock-movements.*') ? 'text-brand-600 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    <span class="nav-label truncate">Stock Movements</span>
                </a>
            </li>

            <li>
                <a href="{{ route('categories.index') }}"
                    class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors
                  {{ request()->routeIs('categories.*') ? 'bg-brand-50 text-brand-700 dark:bg-gray-700 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                    @if (request()->routeIs('categories.*')) aria-current="page" @endif>
                    <svg class="w-5 h-5 flex-shrink-0 transition duration-75
                      {{ request()->routeIs('categories.*') ? 'text-brand-600 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="nav-label truncate">Categories</span>
                </a>
            </li>




        </ul>


        <p
            class="nav-section-label px-3 mb-2 mt-4 text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
            Vendo Unit
        </p>


        <ul class="space-y-0.5">

            <li>
                <a href="{{ route('vendo-partners.index') }}"
                    class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors
                  {{ request()->routeIs('vendo-partners.*') ? 'bg-brand-50 text-brand-700 dark:bg-gray-700 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                    @if (request()->routeIs('vendo-partners.*')) aria-current="page" @endif>
                    <svg class="w-5 h-5 flex-shrink-0 transition duration-75
                      {{ request()->routeIs('vendo-partners.*') ? 'text-brand-600 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="nav-label truncate">Manage Partner</span>
                </a>
            </li>

            <li>
                <a href="{{ route('vendo-units.index') }}"
                    class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors
                  {{ request()->routeIs('vendo-units.*') ? 'bg-brand-50 text-brand-700 dark:bg-gray-700 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                    @if (request()->routeIs('vendo-units.*')) aria-current="page" @endif>
                    <svg class="w-5 h-5 flex-shrink-0 transition duration-75
                      {{ request()->routeIs('vendo-units.*') ? 'text-brand-600 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 5h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2z" />
                    </svg>
                    <span class="nav-label truncate">Vendo Unit</span>
                </a>
            </li>

        </ul>

        <p
            class="nav-section-label px-3 mb-2 mt-4 text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
            Omada Cloud
        </p>
        <ul class="space-y-0.5">

            <li>
                <a href="{{ route('omada.index') }}"
                    class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors
                  {{ request()->routeIs('omada.*') ? 'bg-brand-50 text-brand-700 dark:bg-gray-700 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                    @if (request()->routeIs('omada.*')) aria-current="page" @endif>
                    <svg class="w-5 h-5 flex-shrink-0 transition duration-75
                      {{ request()->routeIs('omada.*') ? 'text-brand-600 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.9A5.5 5.5 0 0116.5 9H17a4 4 0 110 8H7zm5-8V4m0 0l-2 2m2-2l2 2m-2 8v2m-2-2h4" />
                    </svg>
                    <span class="nav-label truncate">Omada Partner</span>
                </a>
            </li>

            <li>
                <a href="{{ route('omada-voucher-tool.index') }}"
                    class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors
                  {{ request()->routeIs('omada-voucher-tool.*') ? 'bg-brand-50 text-brand-700 dark:bg-gray-700 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                    @if (request()->routeIs('omada-voucher-tool.*')) aria-current="page" @endif>
                    <svg class="w-5 h-5 flex-shrink-0 transition duration-75
                      {{ request()->routeIs('omada-voucher-tool.*') ? 'text-brand-600 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                    <span class="nav-label truncate">Voucher Designer</span>
                </a>
            </li>


        </ul>

        <p
            class="nav-section-label px-3 mb-2 mt-4 text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
            HR & Finance
        </p>
        <ul class="space-y-0.5">

            <li>
                <a href="{{ route('employees.index') }}"
                    class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors
                  {{ request()->routeIs('employees.*') ? 'bg-brand-50 text-brand-700 dark:bg-gray-700 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                    @if (request()->routeIs('employees.*')) aria-current="page" @endif>
                    <svg class="w-5 h-5 flex-shrink-0 transition duration-75
                      {{ request()->routeIs('employees.*') ? 'text-brand-600 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 20h18M5 20v-4a7 7 0 0114 0v4M4 16h16M12 9V5m-3 4h6" />
                    </svg>
                    <span class="nav-label truncate">Employees</span>
                </a>
            </li>

             <li>
                <a href="{{ route('payrolls.index') }}"
                    class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors
                  {{ request()->routeIs('payrolls.*') ? 'bg-brand-50 text-brand-700 dark:bg-gray-700 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                    @if (request()->routeIs('payrolls.*')) aria-current="page" @endif>
                    <svg class="w-5 h-5 flex-shrink-0 transition duration-75
                      {{ request()->routeIs('payrolls.*') ? 'text-brand-600 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="4" y="3" width="16" height="18" rx="2" stroke-width="2" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 8h8M8 12h2M12 12h4M8 16h2M12 16h4" />
                    </svg>
                    <span class="nav-label truncate">Payroll</span>
                </a>
            </li>


            <li>
                <a href="{{ route('expenses.index') }}"
                    class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors
                  {{ request()->routeIs('expenses.*') ? 'bg-brand-50 text-brand-700 dark:bg-gray-700 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                    @if (request()->routeIs('expenses.*')) aria-current="page" @endif>
                    <svg class="w-5 h-5 flex-shrink-0 transition duration-75
                      {{ request()->routeIs('expenses.*') ? 'text-brand-600 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16M8 7h2m4 0h2M8 11h2m4 0h2M8 15h2m4 0h2M10 21v-3h4v3" />
                    </svg>
                    <span class="nav-label truncate">Expenses</span>
                </a>
            </li>


        </ul>

        <p
            class="nav-section-label px-3 mb-2 mt-4 text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
            Account
        </p>


    </nav>
</aside>
