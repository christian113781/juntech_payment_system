<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Employees</h1>
            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                {{ $totalEmployees }} employee(s) in total
            </p>
        </div>

        <button type="button" wire:click="openCreateModal()"
            class="flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700 min-h-[44px]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Employee
        </button>
    </div>

    <section aria-label="Employee summary" class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-900/20" aria-hidden="true">
                    <svg class="h-5 w-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Total</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $totalEmployees }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-900/20" aria-hidden="true">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 3" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Active</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $activeCount }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-700" aria-hidden="true">
                    <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 115.636 5.636a9 9 0 0112.728 12.728zM9 9l6 6M15 9l-6 6" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Inactive</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $inactiveCount }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-900/20" aria-hidden="true">
                    <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 10v2m0-2c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Avg Rate</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">₱{{ number_format((float) $avgRate, 2) }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800" aria-label="Employee filters">
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[220px]">
                <svg class="pointer-events-none absolute start-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                </svg>
                <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search employee or position…"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 ps-9 pe-4 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200"
                    aria-label="Search employees">
            </div>

            <select wire:model.live="statusFilter" class="w-auto min-w-[10rem] rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-400 focus:bg-white dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200" aria-label="Filter employees by status">
                <option value="all">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </section>

    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/55 p-4">
            <div class="my-auto flex max-h-[90dvh] w-full max-w-2xl flex-col rounded-[1.25rem] bg-white p-5 shadow-card dark:bg-gray-800" role="dialog" aria-modal="true" aria-labelledby="employee-modal-title">
                <div class="mb-5 flex shrink-0 items-start justify-between gap-3">
                    <div>
                        <h2 id="employee-modal-title" class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ $editingEmployeeId ? 'Edit Employee' : 'Add New Employee' }}
                        </h2>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                            Fill in the employee details below
                        </p>
                    </div>

                    <button type="button" wire:click="closeModal()"
                        class="rounded-xl p-2 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600"
                        aria-label="Close dialog">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="saveEmployee" class="flex min-h-0 flex-1 flex-col">
                    <div class="min-h-0 flex-1 space-y-4 overflow-y-auto pe-1">
                    <div>
                        <label for="emp-name" class="mb-1.5 block text-sm font-semibold text-gray-700 dark:text-gray-300">Full Name</label>
                        <input id="emp-name" type="text" wire:model="name" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 @error('name') border-red-300 bg-red-50 @enderror" placeholder="e.g. Juan Dela Cruz">
                        @error('name')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="emp-position" class="mb-1.5 block text-sm font-semibold text-gray-700 dark:text-gray-300">Position</label>
                            <input id="emp-position" type="text" wire:model="position" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 @error('position') border-red-300 bg-red-50 @enderror" placeholder="e.g. Technician">
                            @error('position')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="emp-contact" class="mb-1.5 block text-sm font-semibold text-gray-700 dark:text-gray-300">Contact Number</label>
                            <input id="emp-contact" type="number" inputmode="numeric" pattern="[0-9]*" maxlength="20" wire:model="contactNumber" oninput="this.value = this.value.replace(/\D/g, '')" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 @error('contactNumber') border-red-300 bg-red-50 @enderror" placeholder="09XX XXX XXXX">
                            @error('contactNumber')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="emp-rate" class="mb-1.5 block text-sm font-semibold text-gray-700 dark:text-gray-300">Daily Rate</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 start-0 flex items-center ps-3 text-sm text-gray-400">₱</span>
                                <input id="emp-rate" type="number" min="0" step="1" wire:model="dailyRate" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 ps-8 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 @error('dailyRate') border-red-300 bg-red-50 @enderror" placeholder="700">
                            </div>
                            @error('dailyRate')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="emp-started" class="mb-1.5 block text-sm font-semibold text-gray-700 dark:text-gray-300">Date Started</label>
                            <input id="emp-started" type="date" wire:model="dateStarted" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 @error('dateStarted') border-red-300 bg-red-50 @enderror">
                            @error('dateStarted')
                                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="emp-status" class="mb-1.5 block text-sm font-semibold text-gray-700 dark:text-gray-300">Status</label>
                        <select id="emp-status" wire:model="status" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 @error('status') border-red-300 bg-red-50 @enderror">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="emp-notes" class="mb-1.5 block text-sm font-semibold text-gray-700 dark:text-gray-300">Notes <span class="font-normal text-gray-400">(optional)</span></label>
                        <textarea id="emp-notes" rows="3" wire:model="notes" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none transition-colors placeholder:text-gray-400 focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 @error('notes') border-red-300 bg-red-50 @enderror" placeholder="Any additional notes about this employee…"></textarea>
                        @error('notes')
                            <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    </div>

                    <div class="mt-6 flex shrink-0 items-center justify-end gap-3">
                        <button type="button" wire:click="closeModal()" class="min-h-[44px] rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 transition-colors hover:bg-gray-200">
                            Cancel
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="saveEmployee"
                            class="flex min-h-[44px] items-center justify-center gap-2 rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand-700 disabled:opacity-70">
                            <svg wire:loading wire:target="saveEmployee" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="saveEmployee">{{ $editingEmployeeId ? 'Save Changes' : 'Save Employee' }}</span>
                            <span wire:loading wire:target="saveEmployee">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/55" aria-hidden="true"></div>
            <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-card dark:bg-gray-800" role="alertdialog" aria-modal="true" aria-labelledby="delete-employee-title">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-500 dark:bg-red-500/10" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 3h5a1 1 0 011 1v3h-7V4a1 1 0 011-1z"/>
                    </svg>
                </div>

                <h2 id="delete-employee-title" class="text-base font-bold text-gray-900 dark:text-white">Remove employee?</h2>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-300">
                    This will remove <span class="font-semibold text-gray-800 dark:text-gray-100">{{ $deleteEmployee?->name ?? 'this employee' }}</span> from the employee list.
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
                        <span wire:loading.remove wire:target="confirmDelete">Remove</span>
                        <span wire:loading wire:target="confirmDelete">Removing...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <section aria-label="Employee list" class="rounded-2xl bg-white shadow-card dark:bg-gray-800">
        <div class="tbl-wrap" tabindex="0" role="region" aria-label="Employee table">
            <table class="w-full text-sm" style="min-inline-size:56rem">
                <thead>
                    <tr class="border-b border-gray-100 text-left dark:border-gray-700">
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Employee</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Position</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Daily Rate</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Status</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Date Started</th>
                        <th scope="col" class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse ($employees as $employee)
                        <tr class="transition-colors hover:bg-gray-50/60 dark:hover:bg-gray-700/40">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-brand-400 to-brand-700 text-xs font-bold text-white">
                                        {{ $employee['initials'] }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $employee['name'] }}</p>
                                        <p class="text-xs text-gray-400">{{ $employee['contact_number'] !== '—' ? $employee['contact_number'] : 'No contact' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-gray-600 dark:text-gray-300">{{ $employee['position'] }}</td>
                            <td class="px-5 py-3.5 font-semibold text-gray-900 dark:text-white">₱{{ number_format($employee['daily_rate'], 2) }}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $employee['status'] === 'Active' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ $employee['status'] }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                {{ $employee['date_started'] ? \Carbon\Carbon::parse($employee['date_started'])->format('M d, Y') : '—' }}
                            </td>
                            <td class="px-5 py-3.5 text-end">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('employee-cash-advances.index', ['employee' => $employee['id']]) }}"
                                        class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-amber-50 hover:text-amber-600"
                                        aria-label="Cash advances for {{ $employee['name'] }}">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5s8.577 3.01 9.964 7.183a1.012 1.012 0 010 .639C20.577 16.49 16.64 19.5 12 19.5s-8.577-3.01-9.964-7.178z" />
                                            <circle cx="12" cy="12" r="3" stroke-width="2" />
                                        </svg>
                                    </a>
                                    <button type="button" wire:click="openEditModal({{ $employee['id'] }})" class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-brand-50 hover:text-brand-600" aria-label="Edit {{ $employee['name'] }}">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button type="button" wire:click="openDeleteModal({{ $employee['id'] }})" class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-red-50 hover:text-red-500" aria-label="Delete {{ $employee['name'] }}">
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
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No employees match your filters.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($totalEmployees > 0)
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-5 py-4 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400">Showing
                    <strong>{{ $startItem }}</strong>–<strong>{{ $endItem }}</strong> of <strong>{{ $totalEmployees }}</strong>
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
