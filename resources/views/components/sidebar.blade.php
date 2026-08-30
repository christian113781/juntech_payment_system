{{-- resources/views/components/sidebar.blade.php --}}
<aside id="sidebar" aria-label="Primary navigation"
       class="bg-white dark:bg-gray-800 flex flex-col shadow-card flex-shrink-0 relative z-30"
       :class="{ collapsed: sidebarCollapsed, 'mobile-open': mobileOpen }">

  {{-- Logo --}}
  <div class="logo-wrap flex items-center gap-3 px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex-shrink-0">
    <div class="w-9 h-9 rounded-xl bg-brand-600 flex items-center justify-center flex-shrink-0">
      <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
      </svg>
    </div>
    <div class="logo-text min-w-0">
      <p class="text-sm font-bold text-gray-900 dark:text-white leading-tight whitespace-nowrap">StockMaster</p>
      <p class="text-[10px] text-gray-400 font-medium tracking-wide uppercase">Inventory Pro</p>
    </div>
  </div>

  {{-- Nav --}}
  <nav class="flex-1 sidebar-scroll py-3 px-2 overflow-y-auto" aria-label="Primary">

    <p class="nav-section-label px-3 mb-2 mt-4 first:mt-0 text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
      Overview
    </p>
    <ul class="space-y-1">

      <li>
        <a href="{{ route('dashboard') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors
                  {{ request()->routeIs('dashboard') ? 'bg-brand-50 text-brand-700 dark:bg-gray-700 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           @if(request()->routeIs('dashboard')) aria-current="page" @endif>
          <svg class="w-5 h-5 flex-shrink-0 transition duration-75
                      {{ request()->routeIs('dashboard') ? 'text-brand-600 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
               fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
          </svg>
          <span class="nav-label truncate">Dashboard</span>
        </a>
      </li>

      <li>
        <a href="{{ route('areas.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors
                  {{ request()->routeIs('areas.*') ? 'bg-brand-50 text-brand-700 dark:bg-gray-700 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           @if(request()->routeIs('areas.*')) aria-current="page" @endif>
          <svg class="w-5 h-5 flex-shrink-0 transition duration-75
                      {{ request()->routeIs('areas.*') ? 'text-brand-600 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
               fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 18l-6 2V6l6-2 6 2 6-2v14l-6 2-6-2zm0-14v14m6-12v14"/>
          </svg>
          <span class="nav-label truncate">Areas</span>
        </a>
      </li>

    </ul>

    <p class="nav-section-label px-3 mb-2 mt-4 text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
      Inventory
    </p>
    <ul class="space-y-0.5">

         <li>
        <a href="{{ route('products.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors
                  {{ request()->routeIs('products.index') ? 'bg-brand-50 text-brand-700 dark:bg-gray-700 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           @if(request()->routeIs('products.index')) aria-current="page" @endif>
          <svg class="w-5 h-5 flex-shrink-0 transition duration-75
                      {{ request()->routeIs('products') ? 'text-brand-600 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
               fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
          </svg>
          <span class="nav-label truncate">Products</span>
        </a>
      </li>



      <li>
        <a href="{{ route('stock-movements.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors
                  {{ request()->routeIs('stock-movements.*') ? 'bg-brand-50 text-brand-700 dark:bg-gray-700 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           @if(request()->routeIs('stock-movements.*')) aria-current="page" @endif>
          <svg class="w-5 h-5 flex-shrink-0 transition duration-75
                      {{ request()->routeIs('stock-movements.*') ? 'text-brand-600 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
               fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
          </svg>
          <span class="nav-label truncate">Stock Movements</span>
        </a>
      </li>

       <li>
        <a href="{{ route('categories.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors
                  {{ request()->routeIs('categories.*') ? 'bg-brand-50 text-brand-700 dark:bg-gray-700 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           @if(request()->routeIs('categories.*')) aria-current="page" @endif>
          <svg class="w-5 h-5 flex-shrink-0 transition duration-75
                      {{ request()->routeIs('categories.*') ? 'text-brand-600 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
               fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
          <span class="nav-label truncate">Categories</span>
        </a>
      </li>




    </ul>

    <p class="nav-section-label px-3 mb-2 mt-4 text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
      Omada Cloud
    </p>
    <ul class="space-y-0.5">

         <li>
        <a href="{{ route('omada.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors
                  {{ request()->routeIs('omada.*') ? 'bg-brand-50 text-brand-700 dark:bg-gray-700 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           @if(request()->routeIs('omada.*')) aria-current="page" @endif>
          <svg class="w-5 h-5 flex-shrink-0 transition duration-75
                      {{ request()->routeIs('omada.*') ? 'text-brand-600 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
               fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M7 16a4 4 0 01-.88-7.9A5.5 5.5 0 0116.5 9H17a4 4 0 110 8H7zm5-8V4m0 0l-2 2m2-2l2 2m-2 8v2m-2-2h4"/>
          </svg>
          <span class="nav-label truncate">Omada Partner</span>
        </a>
      </li>


    </ul>

    <p class="nav-section-label px-3 mb-2 mt-4 text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
      Account
    </p>
    <ul class="space-y-0.5">

      <li>
        <a href="{{ route('profile.show') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors
                  {{ request()->routeIs('profile.*') ? 'bg-brand-50 text-brand-700 dark:bg-gray-700 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           @if(request()->routeIs('profile.*')) aria-current="page" @endif>
          <svg class="w-5 h-5 flex-shrink-0 transition duration-75
                      {{ request()->routeIs('profile.*') ? 'text-brand-600 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
               fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
          <span class="nav-label truncate">My Profile</span>
        </a>
      </li>

      <li>
        <a href="{{ route('settings.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors
                  {{ request()->routeIs('settings.*') ? 'bg-brand-50 text-brand-700 dark:bg-gray-700 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           @if(request()->routeIs('settings.*')) aria-current="page" @endif>
          <svg class="w-5 h-5 flex-shrink-0 transition duration-75
                      {{ request()->routeIs('settings.*') ? 'text-brand-600 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
               fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
          </svg>
          <span class="nav-label truncate">Settings</span>
        </a>
      </li>

    </ul>

  </nav>

  {{-- User --}}
  <div class="border-t border-gray-100 dark:border-gray-700 px-3 py-4 flex-shrink-0">
    <div class="user-wrap flex items-center gap-3">
      <div class="user-avatar w-8 h-8 rounded-full bg-gradient-to-br from-brand-400 to-brand-700 flex items-center justify-center flex-shrink-0 text-white text-xs font-bold">
        {{ auth()->user()?->initials() ?? 'JD' }}
      </div>
      <div class="user-info min-w-0">
        <p class="text-sm font-semibold text-gray-800 dark:text-gray-100 truncate">{{ auth()->user()?->name ?? 'Guest' }}</p>
        <p class="text-[11px] text-gray-400 truncate">{{ auth()->user()?->role ?? '' }}</p>
      </div>
      <form method="POST" action="{{ route('logout') }}" class="user-info ms-auto">
        @csrf
        <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" aria-label="Sign out">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
        </button>
      </form>
    </div>
  </div>
</aside>
