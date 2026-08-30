{{-- resources/views/components/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en" x-data="appShell()" :class="{ dark: isDark }">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'StockMaster' }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>
<body class="font-poppins text-gray-800 antialiased bg-gray-50 dark:bg-gray-900" x-cloak>

  <div id="sidebar-overlay" class="fixed inset-0 bg-gray-900/50 z-20 lg:hidden"
       x-show="mobileOpen" x-transition.opacity @click="closeMobileSidebar()" style="display:none"></div>

  <div id="toast" role="status" aria-live="polite" :class="{ show: toast.show }">
    <span x-text="toast.msg"></span>
  </div>

  <div class="flex min-h-[100dvh]">
    <x-sidebar />
    <div class="flex-1 flex flex-col min-w-0">
      <x-header />
      <main id="main" tabindex="-1" class="flex-1 px-4 sm:px-6 py-6 space-y-6 max-w-[1600px] mx-auto w-full">
        {{ $slot }}
      </main>
      <footer class="border-t border-gray-100 dark:border-gray-700 px-6 py-4 text-center text-xs text-gray-400">
        StockMaster Inventory Pro &copy; 2026
      </footer>
    </div>
  </div>

  @livewireScripts
</body>
</html>
