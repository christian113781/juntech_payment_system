{{-- resources/views/components/header.blade.php --}}
<header class="sticky top-0 z-10 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 shadow-sm">
  <div class="flex items-center gap-3 px-4 sm:px-6 py-3.5">

    {{-- Desktop sidebar collapse --}}
    <button type="button"
            class="header-icon-btn hidden lg:flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-brand-600 dark:hover:text-white transition-colors flex-shrink-0"
            @click="sidebarCollapsed = !sidebarCollapsed"
            :aria-expanded="(!sidebarCollapsed).toString()" aria-controls="sidebar"
            :aria-label="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v18"/>
      </svg>
    </button>

    {{-- Mobile menu --}}
    <button type="button"
            class="header-icon-btn lg:hidden p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
            @click="mobileOpen ? closeMobileSidebar() : openMobileSidebar()"
            :aria-expanded="mobileOpen.toString()" aria-controls="sidebar"
            :aria-label="mobileOpen ? 'Close navigation menu' : 'Open navigation menu'">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>

    {{-- Search --}}
    <div class="flex-1 max-w-md">
      <div class="relative">
        <svg class="absolute start-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
        </svg>
        <input type="text" placeholder="Search products, orders…"
       class="header-search w-full ps-9 pe-4 py-2 text-sm bg-gray-50  dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-gray-100 rounded-lg placeholder:text-gray-400 focus:bg-white dark:focus:bg-gray-700 focus:border-brand-400 focus:ring-1 focus:ring-brand-100 dark:focus:ring-brand-900 outline-none transition-colors min-h-[2.75rem] shadow-sm">

    </div>
    </div>

    <div class="flex items-center gap-2 ms-auto">

      {{-- Dark mode --}}
      <button type="button" @click="toggleDark()"
              class="header-icon-btn p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
              :aria-label="isDark ? 'Switch to light mode' : 'Switch to dark mode'">
        <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        <svg x-show="isDark" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
        </svg>
      </button>

      {{-- Notifications --}}
      <div class="relative" x-data="{ notifOpen: false }">
        <button type="button" @click="notifOpen = !notifOpen"
                class="header-icon-btn relative p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                aria-haspopup="true" :aria-expanded="notifOpen.toString()">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
          </svg>
          <span class="absolute top-1.5 end-1.5 w-2 h-2 rounded-full bg-red-500"></span>
        </button>

        <div x-show="notifOpen" x-transition @click.outside="notifOpen = false" @keydown.escape="notifOpen = false"
             class="absolute end-0 top-full mt-2 w-80 sm:w-96 bg-white dark:bg-gray-800 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 z-40 overflow-hidden"
             style="display:none">
          <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Notifications</h2>
          </div>

          <ul class="max-h-80 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700" role="list">
            <li>
              <button type="button" class="w-full flex items-start gap-3 px-4 py-3 text-start hover:bg-gray-50 dark:hover:bg-gray-700/60 transition-colors bg-brand-50/40 dark:bg-brand-900/10">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 bg-amber-50 dark:bg-amber-900/30" aria-hidden="true">
                  <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                  </svg>
                </div>
                <div class="min-w-0 flex-1">
                  <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">Low stock alert</p>
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Galaxy Tab S9 has only 3 units left in Manila Warehouse.</p>
                  <p class="text-[11px] text-gray-400 mt-1">10 min ago</p>
                </div>
                <span class="w-2 h-2 rounded-full bg-brand-500 flex-shrink-0 mt-1.5" aria-hidden="true"></span>
              </button>
            </li>

            <li>
              <button type="button" class="w-full flex items-start gap-3 px-4 py-3 text-start hover:bg-gray-50 dark:hover:bg-gray-700/60 transition-colors">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 bg-violet-50 dark:bg-violet-900/30" aria-hidden="true">
                  <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                  </svg>
                </div>
                <div class="min-w-0 flex-1">
                  <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">Purchase order awaiting approval</p>
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">PO-2026-0033 for Levi Strauss PH needs your sign-off.</p>
                  <p class="text-[11px] text-gray-400 mt-1">1 hour ago</p>
                </div>
                <span class="w-2 h-2 rounded-full bg-brand-500 flex-shrink-0 mt-1.5" aria-hidden="true"></span>
              </button>
            </li>

            <li>
              <button type="button" class="w-full flex items-start gap-3 px-4 py-3 text-start hover:bg-gray-50 dark:hover:bg-gray-700/60 transition-colors">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 bg-emerald-50 dark:bg-emerald-900/30" aria-hidden="true">
                  <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                  </svg>
                </div>
                <div class="min-w-0 flex-1">
                  <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">Stock transfer completed</p>
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">200 units of Nescafé Gold 200g arrived at Davao Warehouse.</p>
                  <p class="text-[11px] text-gray-400 mt-1">3 hours ago</p>
                </div>
                <span class="w-2 h-2 rounded-full bg-brand-500 flex-shrink-0 mt-1.5" aria-hidden="true"></span>
              </button>
            </li>
          </ul>

          <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700 text-center">
            <a href="#" class="text-xs font-semibold text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">View all notifications</a>
          </div>
        </div>
      </div>

      {{-- Profile --}}
      <div class="relative" x-data="{ profileOpen: false }">
        <button type="button" @click="profileOpen = !profileOpen"
                class="header-icon-btn flex items-center gap-2 p-1 pe-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                aria-haspopup="true" :aria-expanded="profileOpen.toString()">
          <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-400 to-brand-700 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
            {{ auth()->user()?->initials() ?? 'JD' }}
          </div>
          <svg class="w-3.5 h-3.5 text-gray-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>

        <div x-show="profileOpen" x-transition @click.outside="profileOpen = false" @keydown.escape="profileOpen = false"
             class="absolute end-0 top-full mt-2 w-64 bg-white dark:bg-gray-800 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 z-40 overflow-hidden"
             style="display:none">
          <div class="flex items-center gap-3 px-4 py-3.5 border-b border-gray-100 dark:border-gray-700">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-400 to-brand-700 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
              {{ auth()->user()?->initials() ?? 'JD' }}
            </div>
            <div class="min-w-0">
              <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ auth()->user()?->name ?? 'Juan Dela Cruz' }}</p>
              <p class="text-xs text-gray-400 truncate">{{ auth()->user()?->email ?? 'juan@stockmaster.ph' }}</p>
            </div>
          </div>
          <ul class="py-1.5">
            <li>
              <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                Account Settings
              </a>
            </li>
          </ul>
          <div class="border-t border-gray-100 dark:border-gray-700 py-1.5">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                Sign Out
              </button>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
</header>
