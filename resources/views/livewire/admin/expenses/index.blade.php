<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Company Expenses</h1>
            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                {{ $totalExpenses }} expense(s) recorded
            </p>
        </div>

        <button type="button" wire:click="openCreateModal()"
            class="flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700 min-h-[44px]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Expense
        </button>
    </div>

    <section aria-label="Expense summary" class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-900/20" aria-hidden="true">
                    <svg class="h-5 w-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 10v2m0-2c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Total</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">₱{{ number_format($filteredTotal, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 dark:bg-violet-900/20" aria-hidden="true">
                    <svg class="h-5 w-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Transactions</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $totalExpenses }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-900/20" aria-hidden="true">
                    <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Top Category</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $topCategory }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-900/20" aria-hidden="true">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m-6 4h6m-6 4h4M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Avg / Expense</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">₱{{ number_format($averageExpense, 2) }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800" aria-label="Filter expenses">
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[220px]">
                <svg class="pointer-events-none absolute start-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                </svg>
                <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search expense or category…"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 ps-9 pe-4 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200"
                    aria-label="Search expenses">
            </div>

            <select wire:model.live="dateRange" class="w-auto min-w-[10rem] rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-400 focus:bg-white dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200" aria-label="Filter by date range">
                <option value="All Time">All Time</option>
                <option value="This Month">This Month</option>
                <option value="Last Month">Last Month</option>
                <option value="This Year">This Year</option>
            </select>

            <select wire:model.live="categoryFilter" class="w-auto min-w-[12rem] rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-400 focus:bg-white dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200" aria-label="Filter by category">
                <option value="All Categories">All Categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </select>
        </div>
    </section>

    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/55 p-4">
            <div class="my-auto max-h-[90dvh] w-full max-w-2xl overflow-y-auto rounded-[1.25rem] bg-white p-5 shadow-card dark:bg-gray-800" role="dialog" aria-modal="true" aria-labelledby="expense-modal-title">
                <div class="mb-5 flex items-start justify-between gap-3">
                    <div>
                        <h2 id="expense-modal-title" class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ $editingExpenseId ? 'Edit Expense' : 'Add Expense' }}
                        </h2>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Record a company expense</p>
                    </div>

                    <button type="button" wire:click="closeModal()"
                        class="rounded-xl p-2 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600"
                        aria-label="Close dialog">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="saveExpense" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="expense-date" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Date</label>
                            <input id="expense-date" type="date" wire:model="expenseDate" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 @error('expenseDate') border-red-300 bg-red-50 @enderror">
                            @error('expenseDate')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="expense-category" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Category</label>
                            <select id="expense-category" wire:model="categoryId" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 @error('categoryId') border-red-300 bg-red-50 @enderror">
                                <option value="">Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ App\Models\ExpenseCategory::where('name', $category)->value('id') }}">{{ $category }}</option>
                                @endforeach
                            </select>
                            @error('categoryId')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="expense-description" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Description</label>
                        <input id="expense-description" type="text" wire:model="description" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 @error('description') border-red-300 bg-red-50 @enderror" placeholder="e.g. Internet Bill, Fuel, Office Supplies">
                        @error('description')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="expense-amount" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Amount</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 start-0 flex items-center ps-3 text-sm text-gray-400">₱</span>
                                <input id="expense-amount" type="number" min="0" step="0.01" wire:model="amount" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 ps-8 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 @error('amount') border-red-300 bg-red-50 @enderror" placeholder="2500">
                            </div>
                            @error('amount')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="expense-method" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Payment Method</label>
                            <select id="expense-method" wire:model="paymentMethod" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 @error('paymentMethod') border-red-300 bg-red-50 @enderror">
                                <option value="cash">Cash</option>
                                <option value="gcash">GCash</option>
                                <option value="bank">Bank</option>
                            </select>
                            @error('paymentMethod')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="expense-reference" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Reference Number <span class="font-normal text-gray-400">(optional)</span></label>
                        <input id="expense-reference" type="text" wire:model="referenceNumber" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 @error('referenceNumber') border-red-300 bg-red-50 @enderror" placeholder="e.g. OR / invoice / transaction no.">
                        @error('referenceNumber')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="expense-remarks" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Remarks <span class="font-normal text-gray-400">(optional)</span></label>
                        <textarea id="expense-remarks" rows="2" wire:model="remarks" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 @error('remarks') border-red-300 bg-red-50 @enderror" placeholder="Any additional notes…"></textarea>
                        @error('remarks')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3">
                        <button type="button" wire:click="closeModal()"
                            class="min-h-[44px] rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 transition-colors hover:bg-gray-200">
                            Cancel
                        </button>
                        <button type="submit"
                            class="min-h-[44px] rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand-700">
                            {{ $editingExpenseId ? 'Save Changes' : 'Save Expense' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/55" aria-hidden="true"></div>
            <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-card dark:bg-gray-800" role="alertdialog" aria-modal="true" aria-labelledby="delete-expense-title">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-500 dark:bg-red-500/10" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 3h5a1 1 0 011 1v3h-7V4a1 1 0 011-1z"/>
                    </svg>
                </div>

                <h2 id="delete-expense-title" class="text-base font-bold text-gray-900 dark:text-white">Delete this expense?</h2>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-300">
                    This will remove <span class="font-semibold text-gray-800 dark:text-gray-100">{{ $deleteExpense?->description ?? 'this expense' }}</span> from your records.
                </p>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="closeDeleteModal()" class="min-h-[44px] rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-600 transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="button" wire:click="confirmDelete()" wire:loading.attr="disabled" wire:target="confirmDelete"
                        class="flex min-h-[44px] items-center justify-center gap-2 rounded-xl bg-red-500 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-red-600 disabled:opacity-70">
                        <svg wire:loading wire:target="confirmDelete" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="confirmDelete">Delete</span>
                        <span wire:loading wire:target="confirmDelete">Deleting...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <section aria-label="Expense list" class="rounded-2xl bg-white shadow-card dark:bg-gray-800">
        <div class="tbl-wrap" tabindex="0" role="region" aria-label="Expense table">
            <table class="w-full text-sm" style="min-inline-size:56rem">
                <thead>
                    <tr class="border-b border-gray-100 text-left dark:border-gray-700">
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Date</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Description</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Category</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Payment</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Amount</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse ($expenses as $expense)
                        <tr class="transition-colors hover:bg-gray-50/60 dark:hover:bg-gray-700/40">
                            <td class="px-5 py-3.5 text-gray-600 dark:text-gray-300 whitespace-nowrap">{{ \Carbon\Carbon::parse($expense['date'])->format('M d, Y') }}</td>
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $expense['description'] }}</p>
                                @if (!empty($expense['reference']))
                                    <p class="text-xs text-gray-400">Ref: {{ $expense['reference'] }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center rounded-full bg-brand-50 px-2.5 py-1 text-[11px] font-semibold text-brand-700 dark:bg-brand-900/20 dark:text-brand-300">
                                    {{ $expense['category'] }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $expense['payment_method'] === 'Cash' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300' : ($expense['payment_method'] === 'Gcash' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300' : 'bg-violet-50 text-violet-700 dark:bg-violet-900/20 dark:text-violet-300') }}">
                                    {{ $expense['payment_method'] }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-end font-semibold text-gray-900 dark:text-white whitespace-nowrap">₱{{ number_format($expense['amount'], 2) }}</td>
                            <td class="px-5 py-3.5 text-end">
                                <div class="flex items-center justify-end gap-1">
                                    <button type="button" wire:click="openEditModal({{ $expense['id'] }})" class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-brand-50 hover:text-brand-600" aria-label="Edit {{ $expense['description'] }}">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button type="button" wire:click="openDeleteModal({{ $expense['id'] }})" class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-red-50 hover:text-red-500" aria-label="Delete {{ $expense['description'] }}">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 3h5a1 1 0 011 1v3h-7V4a1 1 0 011-1z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center">
                                <div class="flex flex-col items-center justify-center gap-3 text-gray-400 dark:text-gray-500">
                                    <svg class="h-12 w-12 text-gray-300 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No expenses found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($totalExpenses > 0)
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-5 py-4 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400">Showing
                    <strong>{{ $startItem }}</strong>–<strong>{{ $endItem }}</strong> of <strong>{{ $totalExpenses }}</strong>
                </p>
                <div class="flex items-center gap-1">
                    <button type="button" wire:click="previousPage" @disabled($currentPage <= 1)
                        class="min-h-[36px] rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 transition-colors hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                        ← Prev
                    </button>

                    @for ($page = 1; $page <= $totalPages; $page++)
                        <button type="button" wire:click="goToPage({{ $page }})"
                            class="h-9 w-9 rounded-lg text-xs font-semibold {{ $page === $currentPage ? 'bg-brand-600 text-white' : 'border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            {{ $page }}
                        </button>
                    @endfor

                    <button type="button" wire:click="nextPage" @disabled($currentPage >= $totalPages)
                        class="min-h-[36px] rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 transition-colors hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                        Next →
                    </button>
                </div>
            </div>
        @endif
    </section>
</div>
