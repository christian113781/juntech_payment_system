/* ════════════════════════════════════════════════════════════════
   StockMaster Inventory Pro — SHARED DASHBOARD LOGIC
   Every page loads this file, then defines its own Alpine
   component (e.g. `function partnersPage() { return { ...sharedChrome('partners.html'), ...page data } }`).
   Page-specific DATA (products, orders, movements, partners,
   batches, stats, etc.) lives in each page's own inline <script>.
   ════════════════════════════════════════════════════════════════ */

/* ── Dark mode ──────────────────────────────────────────────────
   Persisted in localStorage. Applied as a class on <html>.
   Call toggleDark() from any page. ─────────────────────────── */
function initDarkMode() {
  const saved = localStorage.getItem('sm-dark');
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  if (saved === 'dark' || (saved === null && prefersDark)) {
    document.documentElement.classList.add('dark');
  }
}
initDarkMode();

/* ── shared icon paths (reused by nav, notifications, stat cards) ── */
const ICONS = {
  dashboard:      'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
  products:       'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
  purchaseOrders: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
  transfers:      'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
  suppliers:      'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
  warehouses:     'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
  reports:        'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
  settings:       'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
  lowStock:       'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
  outOfStock:     'M6 18L18 6M6 6l12 12',
  check:          'M5 13l4 4L19 7',
  login:          'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1',
  register:       'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z',
  notFound:       'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z',
  profile:        'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
  wand:           'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',
};

/* ── Sidebar nav links ──────────────────────────────────────── */
function getNavSections(activeHref) {
  const sections = [
    { label: 'Overview', items: [
      { name: 'Dashboard',        href: 'index.html',            icon: ICONS.dashboard },
      { name: 'Products',         href: 'products.html',         icon: ICONS.products },
      { name: 'Purchase Orders',  href: 'purchase-orders.html',  icon: ICONS.purchaseOrders },
      { name: 'Stock Movements',  href: 'stock-movements.html',  icon: ICONS.transfers },
    ]},
    { label: 'Management', items: [
      { name: 'Suppliers',   href: 'suppliers.html',  icon: ICONS.suppliers },
      { name: 'Warehouses',  href: 'warehouses.html', icon: ICONS.warehouses },
    ]},
    { label: 'Omada Cloud', items: [
      { name: 'Partners',          href: 'partners.html',          icon: ICONS.suppliers },
      { name: 'Voucher Designer',  href: 'voucher-designer.html',  icon: ICONS.wand },
    ]},
    { label: 'Account', items: [
      { name: 'My Profile', href: 'profile.html', icon: ICONS.profile },
      { name: 'Settings',   href: '#',            icon: ICONS.settings },
    ]},
  ];

  // Batch Codes is a drill-down from a specific partner, not a nav
  // destination on its own — keep "Partners" highlighted while there.
  const effectiveHref = activeHref === 'batch-codes.html' ? 'partners.html' : activeHref;
  sections.forEach(section => section.items.forEach(item => { item.active = item.href === effectiveHref; }));
  return sections;
}

/* ── Shared notifications (same bell menu on every page) ─────── */
function getSharedNotifications() {
  return [
    { id: 1, title: 'Low stock alert',                 message: 'Galaxy Tab S9 has only 3 units left in Manila Warehouse.',   time: '10 min ago', read: false, icon: ICONS.lowStock,       iconBg: 'bg-amber-50 dark:bg-amber-900/30',   iconColor: 'text-amber-500' },
    { id: 2, title: 'Purchase order awaiting approval', message: 'PO-2026-0033 for Levi Strauss PH needs your sign-off.',      time: '1 hour ago', read: false, icon: ICONS.purchaseOrders, iconBg: 'bg-violet-50 dark:bg-violet-900/30', iconColor: 'text-violet-600' },
    { id: 3, title: 'Stock transfer completed',         message: '200 units of Nescafé Gold 200g arrived at Davao Warehouse.', time: '3 hours ago', read: false, icon: ICONS.check,          iconBg: 'bg-emerald-50 dark:bg-emerald-900/30', iconColor: 'text-emerald-600' },
    { id: 4, title: 'Out of stock',                     message: 'Yakult Probiotic 80ml is now out of stock.',                 time: 'Yesterday',  read: true,  icon: ICONS.outOfStock,      iconBg: 'bg-red-50 dark:bg-red-900/30',       iconColor: 'text-red-500' },
    { id: 5, title: 'Delivery confirmed',               message: 'Wilcon Depot confirmed delivery for PO-2026-0038.',          time: '2 days ago', read: true,  icon: ICONS.warehouses,      iconBg: 'bg-brand-50 dark:bg-brand-900/30',   iconColor: 'text-brand-600' },
  ];
}

/* ── Everything a page spreads into its Alpine component ──────── */
function sharedChrome(activeHref) {
  return {

    /* ── DARK MODE ── */
    get isDark() { return document.documentElement.classList.contains('dark'); },
    toggleDark() {
      const html = document.documentElement;
      html.classList.toggle('dark');
      localStorage.setItem('sm-dark', html.classList.contains('dark') ? 'dark' : 'light');
    },

    /* ── SIDEBAR ── */
    sidebarCollapsed: false,
    mobileOpen: false,
    openMobileSidebar() { this.mobileOpen = true; },
    closeMobileSidebar() {
      this.mobileOpen = false;
      this.$nextTick(() => document.getElementById('menu-btn').focus());
    },
    navSections: getNavSections(activeHref),

    /* ── TOAST ── */
    toast: { show: false, msg: '' },
    toastTimer: null,
    showToast(msg) {
      this.toast.msg = msg;
      this.toast.show = true;
      clearTimeout(this.toastTimer);
      this.toastTimer = setTimeout(() => this.toast.show = false, 3500);
    },

    /* ── NOTIFICATIONS ── */
    notifOpen: false,
    notifications: getSharedNotifications(),
    get unreadCount() { return this.notifications.filter(n => !n.read).length; },
    markRead(n) { n.read = true; },
    markAllRead() { this.notifications.forEach(n => n.read = true); },

    /* ── PROFILE DROPDOWN ── */
    profileOpen: false,

    /* ── MODAL PLUMBING (shared by every add/edit modal) ── */
    lastFocus: null,
    openModalCommon() {
      this.lastFocus = document.activeElement;
      this.modalOpen = true;
      document.body.style.overflow = 'hidden';
      this.$nextTick(() => {
        const first = this.$refs.dialog.querySelector('input, select, textarea, button');
        if (first) first.focus();
      });
    },
    closeModal() {
      this.modalOpen = false;
      document.body.style.overflow = '';
      if (this.lastFocus) this.lastFocus.focus();
    },
    trapFocus(e) {
      const focusable = [...this.$refs.dialog.querySelectorAll(
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
      )].filter(el => !el.disabled && el.offsetParent !== null);
      if (!focusable.length) return;
      const first = focusable[0], last = focusable[focusable.length - 1];
      if (e.shiftKey) { if (document.activeElement === first) { e.preventDefault(); last.focus(); } }
      else            { if (document.activeElement === last)  { e.preventDefault(); first.focus(); } }
    },
    /* only used by multi-step modals (pages set currentStep/totalSteps/validateStep) */
    nextStep() {
      if (!this.validateStep(this.currentStep)) return;
      if (this.currentStep < this.totalSteps) {
        this.currentStep++;
        this.$nextTick(() => this.$refs.dialog.scrollTop = 0);
      }
    },
    backStep() {
      if (this.currentStep > 1) {
        this.currentStep--;
        this.$nextTick(() => this.$refs.dialog.scrollTop = 0);
      }
    },
  };
}
