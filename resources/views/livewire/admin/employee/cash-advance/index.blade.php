<div class="space-y-6 px-4 py-6 sm:px-6 lg:px-8">
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <span class="font-medium text-gray-500">Employees</span>
        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 18l6-6-6-6"/>
        </svg>
        <span class="font-medium text-gray-500">{{ $employee->name }}</span>
        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 18l6-6-6-6"/>
        </svg>
        <span class="font-semibold text-gray-800">Cash Advances</span>
    </nav>

    <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
        <div class="flex items-center gap-4">
            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-indigo-400 to-indigo-700 text-lg font-bold text-white shadow-sm">
                {{ strtoupper(substr($employee->name, 0, 1)) }}{{ strtoupper(substr(strrchr($employee->name, ' ') ?: '', 1, 1)) }}
            </div>
            <div>
                <h1 class="text-3xl font-black tracking-tight text-gray-900">
                    {{ $employee->name }}'s Cash Advances
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $advances->count() }} of {{ $advanceCount }} advances • {{ $employee->position ?? 'Employee' }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3 self-start xl:self-auto">
            <button type="button" wire:click="openCreateModal()"
                class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Record Cash Advance
            </button>
        </div>
    </div>

    <section class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
            <div class="mb-3 flex items-center justify-between">
                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-gray-400">Total Advanced</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3 0 1.657 1.343 3 3 3s3 1.343 3 3-1.343 3-3 3m0-12v2m0 14v2m8-8h-2M6 12H4"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black text-gray-900">₱{{ number_format($totalAdvance, 2) }}</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
            <div class="mb-3 flex items-center justify-between">
                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-gray-400">Total Deducted</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 3"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black text-gray-900">₱{{ number_format($totalPaid, 2) }}</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
            <div class="mb-3 flex items-center justify-between">
                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-gray-400">Outstanding</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black text-gray-900">₱{{ number_format($balance, 2) }}</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
            <div class="mb-3 flex items-center justify-between">
                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-gray-400">Active Borrowers</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black text-gray-900">{{ $activeBorrowers }}</p>
        </div>
    </section>

    <section class="rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 p-4 md:flex-row md:items-center">
            <div class="relative flex-1">
                <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input type="search" wire:model.live.debounce.250ms="search" placeholder="Search by employee name..."
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 py-3 pl-11 pr-4 text-sm text-gray-700 placeholder:text-gray-400 focus:border-indigo-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-100" aria-label="Search advances">
            </div>

        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead>
                    <tr class="border-t border-b border-gray-200 bg-gray-50 text-[11px] font-semibold uppercase tracking-[0.16em] text-gray-500">
                        <th class="px-5 py-4">Date</th>
                        <th class="px-5 py-4">Amount</th>
                        <th class="px-5 py-4">Deducted</th>
                        <th class="px-5 py-4">Balance</th>
                        <th class="px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($advances as $advance)
                        <tr class="transition-colors hover:bg-gray-50">
                            <td class="px-5 py-4 text-gray-600">{{ \Carbon\Carbon::parse($advance->advance_date)->format('M d, Y') }}</td>
                            <td class="px-5 py-4 font-semibold text-gray-900">₱{{ number_format($advance->amount, 2) }}</td>
                            <td class="px-5 py-4 text-gray-600">₱{{ number_format($advance->amount_paid, 2) }}</td>
                            <td class="px-5 py-4 font-semibold text-gray-900">₱{{ number_format($advance->balance, 2) }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" wire:click="openEditModal({{ $advance->id }})" class="rounded-lg p-2 text-gray-400 transition hover:bg-indigo-50 hover:text-indigo-600" aria-label="Edit advance">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button type="button" wire:click="openDeleteModal({{ $advance->id }})" class="rounded-lg p-2 text-gray-400 transition hover:bg-red-50 hover:text-red-600" aria-label="Delete advance">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-sm text-gray-500">
                                No cash advances match your current search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($totalCashAdvances > 0)
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-5 py-4">
                <p class="text-xs text-gray-500">Showing
                    <strong>{{ $startItem }}</strong>–<strong>{{ $endItem }}</strong> of <strong>{{ $totalCashAdvances }}</strong>
                </p>
                <div class="flex items-center gap-1">
                    <button type="button" wire:click="previousPage" @disabled($currentPage <= 1)
                        class="min-h-[36px] rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 transition-colors hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40">
                        ← Prev
                    </button>

                    @for ($page = 1; $page <= $totalPages; $page++)
                        <button type="button" wire:click="goToPage({{ $page }})"
                            class="h-9 w-9 rounded-lg text-xs font-semibold {{ $page === $currentPage ? 'bg-indigo-600 text-white' : 'border border-gray-200 bg-white text-gray-600 hover:bg-gray-50' }}">
                            {{ $page }}
                        </button>
                    @endfor

                    <button type="button" wire:click="nextPage" @disabled($currentPage >= $totalPages)
                        class="min-h-[36px] rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 transition-colors hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40">
                        Next →
                    </button>
                </div>
            </div>
        @endif
    </section>

    @if ($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/55 p-4">
            <div class="w-full max-w-xl rounded-[1.25rem] bg-white p-5 shadow-2xl">
                <div class="mb-5 flex items-start justify-between gap-3 border-b border-gray-100 pb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">
                            {{ $advanceId ? 'Edit Cash Advance' : 'Record Cash Advance' }}
                        </h2>
                        <p class="mt-1 text-xs text-gray-400">This will be deducted from a future payroll.</p>
                    </div>

                    <button type="button" wire:click="closeEditModal()" class="rounded-xl p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600" aria-label="Close dialog">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit="saveAdvance" class="space-y-4">
                    <div>
                        <label for="edit-employee" class="mb-1.5 block text-sm font-semibold text-gray-700">Employee</label>
                        <input id="edit-employee" type="text" value="{{ $employee->name }} — {{ $employee->position ?? 'Employee' }}" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-600" disabled />
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="edit-advance-date" class="mb-1.5 block text-sm font-semibold text-gray-700">Date</label>
                            <input id="edit-advance-date" type="date" wire:model="advanceDate" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 focus:border-indigo-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-100 @error('advanceDate') border-red-300 bg-red-50 @enderror" />
                            @error('advanceDate')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="edit-advance-amount" class="mb-1.5 block text-sm font-semibold text-gray-700">Amount</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-400">₱</span>
                                <input id="edit-advance-amount" type="number" min="1" step="0.01" wire:model="amount" class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-8 pr-3 text-sm text-gray-700 focus:border-indigo-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-100 @error('amount') border-red-300 bg-red-50 @enderror" placeholder="3000" />
                            </div>
                            @error('amount')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="edit-advance-remarks" class="mb-1.5 block text-sm font-semibold text-gray-700">Remarks</label>
                        <textarea id="edit-advance-remarks" rows="2" wire:model="remarks" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 placeholder:text-gray-400 focus:border-indigo-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-100" placeholder="Optional remarks"></textarea>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-100 pt-4">
                        <button type="button" wire:click="closeEditModal()" class="min-h-[44px] rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-200">Cancel</button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="saveAdvance"
                            class="flex min-h-[44px] items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:opacity-70">
                            <svg wire:loading wire:target="saveAdvance" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="saveAdvance">{{ $advanceId ? 'Save Changes' : 'Record Advance' }}</span>
                            <span wire:loading wire:target="saveAdvance">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/55" aria-hidden="true"></div>
            <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl" role="alertdialog" aria-modal="true" aria-labelledby="delete-cash-advance-title">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-500" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>

                <h2 id="delete-cash-advance-title" class="text-base font-bold text-gray-900">Remove cash advance?</h2>
                <p class="mt-2 text-sm text-gray-500">
                    This will remove this advance for <span class="font-semibold text-gray-700">{{ $employee->name }}</span> and its payment history.
                </p>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="closeDeleteModal()" class="min-h-[44px] rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">Cancel</button>
                    <button type="button" wire:click="confirmDelete()" wire:loading.attr="disabled" wire:target="confirmDelete"
                        class="flex min-h-[44px] items-center justify-center gap-2 rounded-xl bg-red-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-600 disabled:opacity-70">
                        <svg wire:loading wire:target="confirmDelete" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="confirmDelete">Remove</span>
                        <span wire:loading wire:target="confirmDelete">Removing...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
