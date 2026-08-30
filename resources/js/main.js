/* ════════════════════════════════════════════════════════════════
   StockMaster Inventory Pro — SHARED DASHBOARD LOGIC
   ════════════════════════════════════════════════════════════════ */

/* ── Dark mode ──────────────────────────────────────────────────
   Persisted in localStorage. Applied as a class on <html>. ───── */
function updateDarkToggleButtons() {
  const isDark = document.documentElement.classList.contains('dark');
  document.querySelectorAll('.dark-toggle').forEach((button) => {
    const nextLabel = isDark ? 'Switch to light mode' : 'Switch to dark mode';
    button.setAttribute('aria-label', nextLabel);
    button.setAttribute('title', nextLabel);
  });
}

function setDarkMode(enabled) {
  document.documentElement.classList.toggle('dark', enabled);
  localStorage.setItem('sm-dark', enabled ? 'dark' : 'light');
  updateDarkToggleButtons();
}

function initDarkMode() {
  const saved = localStorage.getItem('sm-dark');
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  const shouldUseDark = saved === 'dark' || (saved === null && prefersDark);
  setDarkMode(shouldUseDark);
}

function bindDarkModeToggle() {
  document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
      const nextState = !document.documentElement.classList.contains('dark');
      setDarkMode(nextState);
    });
  });
}

initDarkMode();
bindDarkModeToggle();

/* ── Sidebar nav links ──────────────────────────────────────── */
function getNavSections(activeHref) {
  const sections = [
    { label: 'Overview', items: [
      { name: 'Dashboard',        href: 'index.html',            icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
      { name: 'Products',         href: 'products.html',         icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4' },
      { name: 'Purchase Orders',  href: 'purchase-orders.html',  icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' },
      { name: 'Stock Movements',  href: 'stock-movements.html',  icon: 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4' },
    ]},
    { label: 'Management', items: [
      { name: 'Suppliers',   href: 'suppliers.html',  icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z' },
      { name: 'Warehouses',  href: 'warehouses.html', icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10' },
      { name: 'Login',       href: 'login.html',      icon: 'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1' },
      { name: 'Register',    href: 'register.html',   icon: 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z' },
      { name: '404 Page',    href: '404.html',         icon: 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z' },
    ]},
    { label: 'Account', items: [
      { name: 'My Profile', href: 'profile.html',    icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' },
      { name: 'Settings',   href: '#',               icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z' },
    ]},
  ];

  if (activeHref === 'stock-movements.html') {
    sections[0].items[3] = { name: 'Stock Movements', href: 'stock-movements.html', icon: sections[0].items[3].icon };
  }

  sections.forEach(section => section.items.forEach(item => { item.active = item.href === activeHref; }));
  return sections;
}

/* ── Shared notifications ───────────────────────────────────── */
function getSharedNotifications() {
  return [
    { id: 1, title: 'Low stock alert',                  message: 'Galaxy Tab S9 has only 3 units left in Manila Warehouse.',   time: '10 min ago', read: false, icon: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', iconBg: 'bg-amber-50 dark:bg-amber-900/30',   iconColor: 'text-amber-500' },
    { id: 2, title: 'Purchase order awaiting approval',  message: 'PO-2026-0033 for Levi Strauss PH needs your sign-off.',      time: '1 hour ago', read: false, icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', iconBg: 'bg-violet-50 dark:bg-violet-900/30',  iconColor: 'text-violet-600' },
    { id: 3, title: 'Stock transfer completed',          message: '200 units of Nescafé Gold 200g arrived at Davao Warehouse.', time: '3 hours ago', read: false, icon: 'M5 13l4 4L19 7', iconBg: 'bg-emerald-50 dark:bg-emerald-900/30', iconColor: 'text-emerald-600' },
    { id: 4, title: 'Out of stock',                      message: 'Yakult Probiotic 80ml is now out of stock.',                 time: 'Yesterday',  read: true,  icon: 'M6 18L18 6M6 6l12 12', iconBg: 'bg-red-50 dark:bg-red-900/30',     iconColor: 'text-red-500' },
    { id: 5, title: 'Delivery confirmed',                message: 'Wilcon Depot confirmed delivery for PO-2026-0038.',          time: '2 days ago', read: true,  icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', iconBg: 'bg-brand-50 dark:bg-brand-900/30', iconColor: 'text-brand-600' },
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

    /* ── MODAL PLUMBING ── */
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

/* ── Dashboard-specific data (index.html) ───────────────────── */
function getDashboardChartData() {
  return {

    /* Revenue trend line chart */
    revenueData: [
      { month: 'Mar', value: 2.8, target: 3.0 },
      { month: 'Apr', value: 3.2, target: 3.0 },
      { month: 'May', value: 2.9, target: 3.1 },
      { month: 'Jun', value: 3.7, target: 3.2 },
      { month: 'Jul', value: 3.5, target: 3.3 },
      { month: 'Aug', value: 4.2, target: 3.5 },
    ],
    get revenuePills() {
      const vals = this.revenueData.map(d => d.value);
      const latest = vals[vals.length - 1];
      const prev   = vals[vals.length - 2];
      const total  = vals.reduce((a, b) => a + b, 0);
      const pct    = ((latest - prev) / prev * 100).toFixed(1);
      return [
        { label: 'Latest',     value: '₱' + latest + 'M', color: 'text-brand-600' },
        { label: 'vs prev',    value: (pct > 0 ? '+' : '') + pct + '%', color: pct >= 0 ? 'text-emerald-600' : 'text-red-500' },
        { label: '6-mo total', value: '₱' + total.toFixed(1) + 'M', color: 'text-gray-800' },
      ];
    },
    /* Fixed 600×180 coordinate space — works regardless of container width */
    get linePointsArr() {
      const W = 552, H = 150, padX = 24, padY = 16, maxV = 5;
      return this.revenueData.map((d, i) => {
        const x = padX + (i / (this.revenueData.length - 1)) * W;
        const y = padY + (1 - d.value / maxV) * H;
        return { x, y, label: '₱' + d.value + 'M' };
      });
    },
    get linePoints() {
      return this.linePointsArr.map(p => p.x.toFixed(1) + ',' + p.y.toFixed(1)).join(' ');
    },
    get lineAreaPath() {
      const pts = this.linePointsArr;
      const bottom = 166; // padY + H
      return 'M' + pts[0].x.toFixed(1) + ',' + bottom +
        ' L' + pts.map(p => p.x.toFixed(1) + ',' + p.y.toFixed(1)).join(' L') +
        ' L' + pts[pts.length-1].x.toFixed(1) + ',' + bottom + ' Z';
    },
    get lineTargetPoints() {
      const W = 552, H = 150, padX = 24, padY = 16, maxV = 5;
      return this.revenueData.map((d, i) => {
        const x = padX + (i / (this.revenueData.length - 1)) * W;
        const y = padY + (1 - d.target / maxV) * H;
        return x.toFixed(1) + ',' + y.toFixed(1);
      }).join(' ');
    },

    /* Top products */
    topProducts: [
      { name: 'iPhone 16 Pro 256GB',  revenue: '₱842K', pct: 100, barColor: 'bg-brand-500' },
      { name: 'Samsung 55" QLED TV',  revenue: '₱615K', pct: 73,  barColor: 'bg-brand-400' },
      { name: 'Air Jordan Retro OG',  revenue: '₱480K', pct: 57,  barColor: 'bg-emerald-400' },
      { name: 'Ergonomic Desk Chair', revenue: '₱312K', pct: 37,  barColor: 'bg-amber-400' },
      { name: 'Nescafé Gold 200g',    revenue: '₱198K', pct: 24,  barColor: 'bg-violet-400' },
    ],

    /* Supplier performance */
    supplierPerf: [
      { name: 'Samsung Philippines', rate: 96, orders: 42, leadTime: 5 },
      { name: 'Nestlé Philippines',  rate: 88, orders: 31, leadTime: 3 },
      { name: 'Levi Strauss PH',     rate: 79, orders: 18, leadTime: 7 },
      { name: 'Wilcon Depot',        rate: 72, orders: 25, leadTime: 4 },
    ],

    /* Purchase order status */
    poStatuses: [
      { label: 'Completed',  count: 58, hex: '#34d399' },
      { label: 'In Transit', count: 21, hex: '#fbbf24' },
      { label: 'Pending',    count: 14, hex: '#6366f1' },
      { label: 'Cancelled',  count: 7,  hex: '#f87171' },
    ],
    get poTotal() { return this.poStatuses.reduce((s, p) => s + p.count, 0); },
    get poSegments() {
      const total = this.poTotal;
      const circumference = 100; // using % on r=15.9 stroke-dasharray scale
      let cumulative = 0;
      return this.poStatuses.map(s => {
        const dash = (s.count / total) * circumference;
        const offset = 25 - cumulative; // 25 = start at top (SVG -90deg rotated)
        cumulative += dash;
        return { label: s.label, hex: s.hex, dash: +dash.toFixed(2), offset: +offset.toFixed(2) };
      });
    },

    /* Stock ageing */
    ageingBands: [
      { label: '0 – 30 days',  pct: 44, skus: 1253, color: 'bg-emerald-400', textColor: 'text-emerald-600' },
      { label: '31 – 60 days', pct: 28, skus: 796,  color: 'bg-amber-400',   textColor: 'text-amber-600'   },
      { label: '61 – 90 days', pct: 17, skus: 483,  color: 'bg-orange-400',  textColor: 'text-orange-600'  },
      { label: '90 + days',    pct: 11, skus: 315,  color: 'bg-red-400',     textColor: 'text-red-600'     },
    ],
  };
}
