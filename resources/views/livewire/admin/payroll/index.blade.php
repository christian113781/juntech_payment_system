<div class="space-y-6" x-data="payrollBoard()" x-init="init()" x-cloak>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Payroll</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload attendance, review computed pay, save the run, and print payslips.</p>
        </div>
        <button
            type="button"
            @click="showRates = !showRates"
            class="inline-flex min-h-[44px] items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
        >
            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span x-text="showRates ? 'Hide Employee Rates' : 'Manage Employee Rates'"></span>
        </button>
    </div>

    <section aria-labelledby="summary-heading" class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-900/20">
                    <svg class="h-5 w-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Employees</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white" x-text="rows.length"></p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 dark:bg-violet-900/20">
                    <svg class="h-5 w-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Gross Pay</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white" x-text="money(totalGross)"></p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/20">
                    <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Deductions</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white" x-text="money(totalDeductions)"></p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-900/20">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Net Pay</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white" x-text="money(totalNet)"></p>
                </div>
            </div>
        </div>
    </section>

    <section x-show="showRates" x-transition x-cloak class="rounded-2xl bg-white shadow-card dark:bg-gray-800">
        <div class="border-b border-gray-100 p-5 dark:border-gray-700">
            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Employee Rate Book</h2>
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Daily rates used for payroll calculations. Saved in local browser storage.</p>
        </div>

        <div class="tbl-wrap">
            <table class="w-full text-sm" style="min-inline-size: 40rem;">
                <thead>
                    <tr class="border-b border-gray-100 text-left dark:border-gray-700">
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Name</th>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Role</th>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Daily Rate</th>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    <template x-for="emp in employees" :key="emp.id">
                        <tr>
                            <td class="px-5 py-2.5"><input type="text" class="field !py-1.5 !min-h-0" x-model.trim="emp.name" @change="persistEmployees()"></td>
                            <td class="px-5 py-2.5"><input type="text" class="field !py-1.5 !min-h-0" x-model.trim="emp.role" @change="persistEmployees()"></td>
                            <td class="px-5 py-2.5"><input type="number" min="0" step="0.01" class="field !py-1.5 !min-h-0 text-end" x-model.number="emp.dailyRate" @change="persistEmployees()"></td>
                            <td class="px-5 py-2.5 text-end">
                                <button type="button" @click="removeEmployee(emp)" class="inline-flex min-h-[36px] min-w-[36px] items-center justify-center rounded-lg text-gray-400 transition hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-950/20" :aria-label="'Remove ' + emp.name">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 3h5a1 1 0 011 1v3h-7V4a1 1 0 011-1z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <form @submit.prevent="addEmployee()" class="flex flex-col gap-3 border-t border-gray-100 p-5 sm:flex-row sm:flex-wrap sm:items-end dark:border-gray-700">
            <div class="w-full sm:w-auto">
                <label for="new-employee-name" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Name</label>
                <input id="new-employee-name" type="text" class="field sm:!w-40" x-model.trim="newEmployee.name" placeholder="e.g. MARC" required>
            </div>
            <div class="w-full sm:w-auto">
                <label for="new-employee-role" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Role</label>
                <input id="new-employee-role" type="text" class="field sm:!w-48" x-model.trim="newEmployee.role" placeholder="e.g. Warehouse Staff">
            </div>
            <div class="w-full sm:w-auto">
                <label for="new-employee-rate" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Daily Rate</label>
                <input id="new-employee-rate" type="number" min="0" step="0.01" class="field sm:!w-32" x-model.number="newEmployee.dailyRate" placeholder="0.00" required>
            </div>
            <button type="submit" class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">+ Add Employee</button>
        </form>
    </section>

    <section class="rounded-2xl bg-white p-5 shadow-card dark:bg-gray-800" x-show="!viewingHistoryId">
        <h2 class="text-sm font-bold text-gray-900 dark:text-white">Import Attendance File</h2>
        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Upload a biometric summary or attendance export (.xls / .xlsx). Process happens locally in-browser.</p>

        <label for="attendance-file" class="upload-zone mt-4 block cursor-pointer rounded-2xl border border-dashed border-brand-200 bg-brand-50/50 p-8 text-center transition dark:border-brand-900/50 dark:bg-brand-950/10" :class="{ 'ring-2 ring-brand-200': dragging }" @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false" @drop.prevent="dragging = false; handleFile($event.dataTransfer.files[0])">
            <div x-show="!parsing">
                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-100 dark:bg-brand-900/20">
                    <svg class="h-6 w-6 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-200" x-show="!fileName">Drop the attendance file here or <span class="text-brand-600">browse</span></p>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-200" x-show="fileName" x-text="fileName"></p>
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">.xlsx or .xls — processed on this device, not uploaded anywhere.</p>
            </div>
            <p class="text-sm font-medium text-brand-600" x-show="parsing">Reading attendance file…</p>
        </label>
        <input id="attendance-file" type="file" accept=".xlsx,.xls" class="sr-only" @change="handleFile($event.target.files[0])" aria-label="Upload attendance file">
        <p class="mt-3 text-sm text-red-500" x-show="parseError" x-text="parseError"></p>

        <div class="mt-4 grid gap-4 md:grid-cols-3">
            <div>
                <label for="payroll-period-start" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Period Start</label>
                <input id="payroll-period-start" type="date" class="field" x-model="periodStart">
            </div>
            <div>
                <label for="payroll-period-end" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Period End</label>
                <input id="payroll-period-end" type="date" class="field" x-model="periodEnd">
            </div>
        </div>

        <div class="mt-4 flex justify-end" x-show="rows.length > 0">
            <button type="button" @click="clearImport()" class="text-sm font-semibold text-gray-500 transition hover:text-red-500 dark:text-gray-300">Discard this import</button>
        </div>
    </section>

    <section class="rounded-2xl bg-white shadow-card dark:bg-gray-800" x-show="attendanceEmployees.length > 0" x-cloak>
        <div class="flex flex-col gap-3 border-b border-gray-100 p-5 sm:flex-row sm:items-center sm:justify-between dark:border-gray-700">
            <div>
                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Attendance Record</h2>
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">View by employee or print the full attendance summary.</p>
            </div>
            <button type="button" @click="printAttendance()" class="inline-flex min-h-[44px] items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a1 1 0 001-1v-4a1 1 0 00-1-1H9a1 1 0 00-1 1v4a1 1 0 001 1zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span x-text="selectedEmployee === '__ALL__' ? 'Print All' : 'Print ' + selectedEmployee + ' Record'"></span>
            </button>
        </div>

        <div class="px-5 pt-4 pb-1">
            <label for="attendance-employee-select" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">View attendance for</label>
            <select id="attendance-employee-select" x-model="selectedEmployee" class="field !w-full sm:!w-72">
                <option value="__ALL__">All Employees</option>
                <template x-for="name in attendanceEmployees" :key="name">
                    <option :value="name" x-text="name"></option>
                </template>
            </select>
        </div>

        <div class="tbl-wrap mt-3" x-show="selectedEmployee !== '__ALL__'" x-cloak>
            <table class="w-full text-sm" style="min-inline-size: 24rem;">
                <thead>
                    <tr class="border-b border-gray-100 text-left dark:border-gray-700">
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Date</th>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Time In</th>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Time Out</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    <template x-for="(dateLabel, index) in attendanceDates" :key="index">
                        <tr>
                            <td class="px-5 py-2.5 font-medium text-gray-700 dark:text-gray-200" x-text="dateLabel"></td>
                            <td class="px-5 py-2.5" :class="attendanceGrid[selectedEmployee][index].in === 'Absent' ? 'text-red-500 font-medium' : 'text-gray-600 dark:text-gray-200'" x-text="attendanceGrid[selectedEmployee][index].in === 'Absent' ? '-' : (attendanceGrid[selectedEmployee][index].in || '-')"></td>
                            <td class="px-5 py-2.5" :class="attendanceGrid[selectedEmployee][index].out === 'Absent' ? 'text-red-500 font-medium' : 'text-gray-600 dark:text-gray-200'" x-text="attendanceGrid[selectedEmployee][index].out === 'Absent' ? '-' : (attendanceGrid[selectedEmployee][index].out || '-')"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="tbl-wrap mt-3" x-show="selectedEmployee === '__ALL__'" x-cloak>
            <table class="w-full text-sm text-center" style="min-inline-size: 64rem;">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <th class="px-4 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400" rowspan="2">Date</th>
                        <template x-for="name in attendanceEmployees" :key="name">
                            <th class="border-l border-gray-50 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-700 dark:border-gray-700 dark:text-gray-200" :colspan="2" x-text="name"></th>
                        </template>
                    </tr>
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <template x-for="name in attendanceEmployees" :key="name + '-sub'">
                            <template x-for="label in ['In', 'Out']" :key="name + label">
                                <th class="border-l border-gray-50 px-4 py-1.5 text-[10px] font-medium uppercase text-gray-400 dark:border-gray-700 dark:text-gray-500" x-text="label"></th>
                            </template>
                        </template>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    <template x-for="(dateLabel, index) in attendanceDates" :key="index">
                        <tr>
                            <td class="px-4 py-2.5 text-left font-medium text-gray-700 dark:text-gray-200" x-text="dateLabel"></td>
                            <template x-for="name in attendanceEmployees" :key="name + index">
                                <template x-for="field in ['in', 'out']" :key="name + index + field">
                                    <td class="border-l border-gray-50 px-4 py-2.5 dark:border-gray-700" :class="attendanceGrid[name][index][field] === 'Absent' ? 'text-red-500 font-medium' : 'text-gray-600 dark:text-gray-200'" x-text="attendanceGrid[name][index][field] === 'Absent' ? '-' : (attendanceGrid[name][index][field] || '-')"></td>
                                </template>
                            </template>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </section>

    <div x-show="viewingHistoryId" x-cloak class="flex flex-col gap-3 rounded-2xl border border-brand-100 bg-brand-50 px-5 py-3.5 dark:border-brand-800 dark:bg-brand-900/20 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-brand-700 dark:text-brand-300">
            Viewing a saved payroll run for <strong x-text="periodStart + ' – ' + periodEnd"></strong>.
        </p>
        <button type="button" @click="backToNew()" class="text-left text-sm font-semibold text-brand-700 hover:text-brand-900 dark:text-brand-300">← Back to new import</button>
    </div>

    <section class="rounded-2xl bg-white shadow-card dark:bg-gray-800" x-show="rows.length > 0" x-cloak>
        <div class="flex flex-col gap-3 border-b border-gray-100 p-5 lg:flex-row lg:items-center lg:justify-between dark:border-gray-700">
            <div>
                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Payroll Preview</h2>
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500" x-show="periodStart && periodEnd">Period: <span x-text="periodStart"></span> – <span x-text="periodEnd"></span></p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row">
                <button type="button" @click="printRegister()" class="inline-flex min-h-[44px] items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a1 1 0 001-1v-4a1 1 0 00-1-1H9a1 1 0 00-1 1v4a1 1 0 001 1zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Register
                </button>
                <button type="button" @click="savePayrollRun()" x-show="!viewingHistoryId" class="inline-flex min-h-[44px] items-center justify-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Payroll Run
                </button>
            </div>
        </div>

        <div class="grid gap-4 border-b border-gray-100 p-5 dark:border-gray-700 md:grid-cols-3">
            <div>
                <label for="payroll-basis" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">Payroll Basis</label>
                <select id="payroll-basis" x-model="payBasis" class="field">
                    <option value="fixed">Fixed Base</option>
                    <option value="time">Time Base (late / early out)</option>
                </select>
            </div>
            <div class="md:col-span-2 flex items-start pt-1 md:items-center md:pt-6 md:pl-2">
                <p class="text-xs text-gray-500 dark:text-gray-400" x-show="payBasis === 'fixed'">Basic pay uses the daily rate multiplied by days worked.</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 md:whitespace-nowrap" x-show="payBasis === 'time'">Time base uses an 8-hour day. Late arrival and early checkout are deducted at the hourly rate. Schedule: 8:30 AM–5:30 PM.</p>
            </div>
        </div>

        <div class="tbl-wrap" tabindex="0" role="region">
            <table class="w-full text-sm" style="min-inline-size: 84rem;">
                <thead>
                    <tr class="border-b border-gray-100 text-left dark:border-gray-700">
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Employee</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Days</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Daily Rate</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end" x-show="payBasis === 'time'">Hourly Rate</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end" x-show="payBasis === 'time'">Time Deduction</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Basic</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Other +</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Other −</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Cash Adv.</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Net Pay</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end no-print">Print</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    <template x-for="(row, index) in rows" :key="index">
                        <tr class="transition hover:bg-gray-50/60 dark:hover:bg-gray-700/40" :class="{ 'bg-amber-50/50 dark:bg-amber-950/10': !row.matched }">
                            <td class="px-4 py-2.5">
                                <p class="font-medium text-gray-800 dark:text-gray-200" x-text="row.name"></p>
                                <p class="text-[11px] text-amber-600" x-show="!row.matched">Not in rate book — set rate</p>
                            </td>
                            <td class="px-4 py-2.5 text-end text-gray-600 dark:text-gray-300" x-text="row.daysWorked"></td>
                            <td class="px-4 py-2.5 text-end">
                                <input type="number" min="0" step="0.01" class="field !min-h-0 !w-24 !py-1.5 text-end no-print" x-model.number="row.dailyRate" :disabled="viewingHistoryId">
                                <span class="hidden print:inline" x-text="money(row.dailyRate)"></span>
                            </td>
                            <td class="px-4 py-2.5 text-end text-gray-600 dark:text-gray-300" x-show="payBasis === 'time'" x-text="money(hourlyRate(row))"></td>
                            <td class="px-4 py-2.5 text-end text-red-600 dark:text-red-400" x-show="payBasis === 'time'" x-text="money(timeDeduction(row))"></td>
                            <td class="px-4 py-2.5 text-end text-gray-700 dark:text-gray-200" x-text="money(basicSalary(row))"></td>
                            <td class="px-4 py-2.5 text-end">
                                <input type="number" min="0" step="0.01" class="field !min-h-0 !w-24 !py-1.5 text-end no-print" x-model.number="row.otherEarnings" :disabled="viewingHistoryId">
                                <span class="hidden print:inline" x-text="money(row.otherEarnings)"></span>
                            </td>
                            <td class="px-4 py-2.5 text-end">
                                <input type="number" min="0" step="0.01" class="field !min-h-0 !w-24 !py-1.5 text-end no-print" x-model.number="row.otherDeductions" :disabled="viewingHistoryId">
                                <span class="hidden print:inline" x-text="money(row.otherDeductions)"></span>
                            </td>
                            <td class="px-4 py-2.5 text-end">
                                <input type="number" min="0" step="0.01" class="field !min-h-0 !w-24 !py-1.5 text-end no-print" x-model.number="row.cashAdvance" :disabled="viewingHistoryId">
                                <span class="hidden print:inline" x-text="money(row.cashAdvance)"></span>
                            </td>
                            <td class="px-4 py-2.5 text-end font-bold text-gray-900 dark:text-white" x-text="money(netSalary(row))"></td>
                            <td class="px-4 py-2.5 text-end no-print">
                                <button type="button" @click="openPayslip(row)" class="inline-flex min-h-[36px] min-w-[36px] items-center justify-center rounded-lg text-gray-400 transition hover:bg-brand-50 hover:text-brand-600 dark:hover:bg-brand-900/20" :aria-label="'Print payslip for ' + row.name">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a1 1 0 001-1v-4a1 1 0 00-1-1H9a1 1 0 00-1 1v4a1 1 0 001 1zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr class="border-t border-gray-100 bg-gray-50/60 font-bold text-gray-900 dark:border-gray-700 dark:bg-gray-900/40 dark:text-white">
                        <td class="px-4 py-3" :colspan="payBasis === 'time' ? 5 : 3">Totals</td>
                        <td class="px-4 py-3 text-end" x-text="money(rows.reduce((sum, row) => sum + basicSalary(row), 0))"></td>
                        <td class="px-4 py-3 text-end" colspan="3"></td>
                        <td class="px-4 py-3 text-end" x-text="money(totalNet)"></td>
                        <td class="no-print"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </section>

    <section class="rounded-2xl bg-white shadow-card dark:bg-gray-800">
        <div class="border-b border-gray-100 p-5 dark:border-gray-700">
            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Payroll History</h2>
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Saved payroll runs remain available for review, edit, and reprint.</p>
        </div>

        <div class="tbl-wrap">
            <table class="w-full text-sm" style="min-inline-size: 52rem;">
                <thead>
                    <tr class="border-b border-gray-100 text-left dark:border-gray-700">
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Period</th>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Source File</th>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Employees</th>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Net Pay</th>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Generated</th>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    <template x-for="run in sortedHistory" :key="run.id">
                        <tr class="transition hover:bg-gray-50/60 dark:hover:bg-gray-700/40">
                            <td class="px-5 py-3 font-medium text-gray-800 dark:text-gray-200" x-text="run.periodStart + ' – ' + run.periodEnd"></td>
                            <td class="px-5 py-3 text-xs text-gray-500 dark:text-gray-400" x-text="run.attendanceFile || '—'"></td>
                            <td class="px-5 py-3 text-end text-gray-600 dark:text-gray-300" x-text="run.rows.length"></td>
                            <td class="px-5 py-3 text-end font-semibold text-gray-800 dark:text-gray-200" x-text="money(run.rows.reduce((sum, row) => sum + Number(row.netSalary || 0), 0))"></td>
                            <td class="px-5 py-3 text-xs text-gray-500 dark:text-gray-400" x-text="run.generatedAt"></td>
                            <td class="px-5 py-3 text-end">
                                <div class="flex items-center justify-end gap-1">
                                    <button type="button" @click="viewHistory(run)" class="inline-flex min-h-[36px] min-w-[36px] items-center justify-center rounded-lg text-gray-400 transition hover:bg-brand-50 hover:text-brand-600 dark:hover:bg-brand-900/20" :aria-label="'View payroll run ' + run.periodStart">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button type="button" @click="openDeleteHistoryModal(run)" class="inline-flex min-h-[36px] min-w-[36px] items-center justify-center rounded-lg text-gray-400 transition hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-950/20" :aria-label="'Delete payroll run ' + run.periodStart">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 3h5a1 1 0 011 1v3h-7V4a1 1 0 011-1z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="history.length === 0">
                        <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-400">No payroll runs saved yet.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <div x-show="deleteHistoryModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" @click.self="closeDeleteHistoryModal()">
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl dark:bg-gray-800" role="alertdialog" aria-modal="true" aria-labelledby="delete-payroll-history-title">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-500 dark:bg-red-500/10" aria-hidden="true">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 3h5a1 1 0 011 1v3h-7V4a1 1 0 011-1z" />
                </svg>
            </div>

            <h2 id="delete-payroll-history-title" class="text-base font-bold text-gray-900 dark:text-white">Delete payroll history?</h2>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-300">
                This will permanently remove the saved payroll run for
                <span class="font-semibold text-gray-800 dark:text-gray-100" x-text="deleteTarget ? (deleteTarget.periodStart + ' – ' + deleteTarget.periodEnd) : ''"></span>.
                This action cannot be undone.
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" @click="closeDeleteHistoryModal()" class="min-h-[44px] rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-600 transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                    Cancel
                </button>
                <button type="button" @click="confirmDeleteHistory()" class="flex min-h-[44px] items-center justify-center gap-2 rounded-xl bg-red-500 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-red-600">
                    Delete Record
                </button>
            </div>
        </div>
    </div>

    <template x-if="printMode === 'attendance'">
        <div class="print-area" style="padding:0; color:#1f2937; font-family:'Poppins', sans-serif; line-height:1.2;">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:0.2rem;">
                <div>
                    <div style="font-size:1.6rem; font-weight:800; color:#111827; letter-spacing:-0.04em;">StockMaster Inventory Pro</div>
                    <div style="font-size:0.72rem; color:#4b5563; margin-top:0.1rem; font-weight:500;">Attendance Record Report</div>
                </div>
                <div style="text-align:right; font-size:0.72rem; color:#374151; padding-top:0.2rem;">
                    <div style="font-weight:600;">Payroll — Inventory Admin</div>
                    <div style="margin-top:0.18rem; color:#4b5563;">Printed: <span x-text="new Date().toLocaleString()"></span></div>
                </div>
            </div>

            <div style="font-size:0.72rem; color:#374151; margin:0.2rem 0 0.4rem;">
                <span style="font-weight:600;">Period:</span> <span x-text="periodStart + ' – ' + periodEnd"></span>
            </div>

            <table style="width:100%; border-collapse:collapse; font-size:0.7rem; table-layout:fixed; border:1px solid #d1d5db;">
                <thead>
                    <tr style="background:#f3f4f6; border-bottom:1px solid #d1d5db;">
                        <th style="padding:0.28rem 0.3rem; text-align:left; width:4.2rem; border-right:1px solid #d1d5db; font-weight:700; color:#111827;">Date</th>
                        <template x-for="name in attendanceEmployees" :key="name">
                            <th style="padding:0.24rem 0.18rem; text-align:center; border-right:1px solid #d1d5db; font-weight:700; color:#111827;" colspan="2" x-text="name"></th>
                        </template>
                    </tr>
                    <tr style="background:#f3f4f6; border-bottom:1px solid #d1d5db;">
                        <th style="padding:0.18rem 0.26rem; border-right:1px solid #d1d5db; font-size:0.58rem; font-weight:600; color:#6b7280; text-transform:uppercase;"> </th>
                        <template x-for="name in attendanceEmployees" :key="name + '-sub'">
                            <template x-for="label in ['In', 'Out']" :key="name + label">
                                <th style="padding:0.18rem 0.1rem; border-right:1px solid #d1d5db; font-size:0.56rem; font-weight:600; color:#6b7280; text-transform:uppercase;" x-text="label"></th>
                            </template>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(dateLabel, index) in attendanceDates" :key="index">
                        <tr style="border-bottom:1px solid #e5e7eb;">
                            <td style="padding:0.18rem 0.26rem; font-weight:600; border-right:1px solid #d1d5db; color:#111827; white-space:nowrap;" x-text="dateLabel"></td>
                            <template x-for="name in attendanceEmployees" :key="name + index">
                                <template x-for="field in ['in', 'out']" :key="name + index + field">
                                    <td style="padding:0.18rem 0.1rem; text-align:center; border-right:1px solid #e5e7eb; color:#374151;"
                                        :style="attendanceGrid[name][index][field] === 'Absent' ? 'color:#c0392b; background:#fff5f5;' : ''"
                                        x-text="attendanceGrid[name][index][field] === 'Absent' ? '-' : (attendanceGrid[name][index][field] || '-')"></td>
                                </template>
                            </template>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </template>

    <template x-if="printMode === 'attendance-single' && selectedEmployee !== '__ALL__' && attendanceGrid[selectedEmployee]">
        <div class="print-area">
            <div class="flex items-center justify-between border-b-2 border-gray-800 pb-2">
                <div>
                    <h1 class="text-xl font-bold">StockMaster Inventory Pro</h1>
                    <p class="text-xs text-gray-600">Attendance Record — <span x-text="selectedEmployee"></span></p>
                </div>
                <p class="text-xs text-gray-600">Printed: <span x-text="new Date().toLocaleString()"></span></p>
            </div>
            <p class="mt-3 text-sm">Period: <strong x-text="periodStart + ' – ' + periodEnd"></strong></p>
            <table class="mt-4 w-full border-collapse text-sm">
                <thead>
                    <tr class="border-b-2 border-gray-800 text-left">
                        <th class="p-2">Date</th>
                        <th class="p-2">Time In</th>
                        <th class="p-2">Time Out</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(dateLabel, index) in attendanceDates" :key="index">
                        <tr class="border-b border-gray-200">
                            <td class="p-2 font-semibold" x-text="dateLabel"></td>
                            <td class="p-2" :style="attendanceGrid[selectedEmployee][index].in === 'Absent' ? 'color:#c0392b;' : ''" x-text="attendanceGrid[selectedEmployee][index].in === 'Absent' ? '-' : (attendanceGrid[selectedEmployee][index].in || '-')"></td>
                            <td class="p-2" :style="attendanceGrid[selectedEmployee][index].out === 'Absent' ? 'color:#c0392b;' : ''" x-text="attendanceGrid[selectedEmployee][index].out === 'Absent' ? '-' : (attendanceGrid[selectedEmployee][index].out || '-')"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </template>

    <template x-if="printMode === 'register'">
        <div class="print-area">
            <div class="flex items-center justify-between border-b-2 border-gray-800 pb-2">
                <div>
                    <h1 class="text-xl font-bold">StockMaster Inventory Pro</h1>
                    <p class="text-xs text-gray-600">Payroll Register</p>
                </div>
                <p class="text-xs text-gray-600">Printed: <span x-text="new Date().toLocaleString()"></span></p>
            </div>
            <p class="mt-3 text-sm">Period: <strong x-text="periodStart + ' – ' + periodEnd"></strong></p>
            <table class="mt-4 w-full border-collapse text-[11px]">
                <thead>
                    <tr class="border-b-2 border-gray-800 text-left">
                        <th class="p-1">Employee</th>
                        <th class="p-1 text-right">Days</th>
                        <th class="p-1 text-right">Rate</th>
                        <th class="p-1 text-right">Basic</th>
                        <th class="p-1 text-right" x-show="payBasis === 'time'">Time Deduction</th>
                        <th class="p-1 text-right">Other Deductions</th>
                        <th class="p-1 text-right">Net Pay</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(row, index) in rows" :key="index">
                        <tr class="border-b border-gray-200">
                            <td class="p-1" x-text="row.name"></td>
                            <td class="p-1 text-right" x-text="row.daysWorked"></td>
                            <td class="p-1 text-right" x-text="money(row.dailyRate)"></td>
                            <td class="p-1 text-right" x-text="money(basicSalary(row))"></td>
                            <td class="p-1 text-right" x-show="payBasis === 'time'" x-text="money(timeDeduction(row))"></td>
                            <td class="p-1 text-right" x-text="money(Number(row.otherDeductions || 0) + Number(row.cashAdvance || 0))"></td>
                            <td class="p-1 text-right font-bold" x-text="money(netSalary(row))"></td>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-gray-800 font-bold">
                        <td class="p-2" :colspan="payBasis === 'time' ? 6 : 5">Total Net Pay</td>
                        <td class="p-2 text-right" x-text="money(totalNet)"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </template>

    <template x-if="printMode === 'payslip' && printRow">
        <div class="print-area">
            <div class="flex items-center justify-between border-b-2 border-gray-800 pb-2">
                <div>
                    <h1 class="text-xl font-bold">StockMaster Inventory Pro</h1>
                    <p class="text-xs text-gray-600">Employee Payslip</p>
                </div>
                <p class="text-right text-xs text-gray-600">Period<br><strong x-text="periodStart + ' – ' + periodEnd"></strong></p>
            </div>

            <table class="mt-4 w-full text-sm">
                <tr>
                    <td class="py-1 text-gray-600">Employee</td>
                    <td class="py-1 text-right font-bold" x-text="printRow.name"></td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-600">Position</td>
                    <td class="py-1 text-right" x-text="printRow.role || printRow.position || '—'"></td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-600">Daily Rate</td>
                    <td class="py-1 text-right" x-text="money(printRow.dailyRate)"></td>
                </tr>
            </table>

            <table class="mt-4 w-full border-collapse text-sm">
                <tr class="border-b border-gray-200"><td class="py-2">Days Worked</td><td class="py-2 text-right" x-text="printRow.daysWorked"></td></tr>
                <tr class="border-b border-gray-200"><td class="py-2">Basic Pay</td><td class="py-2 text-right" x-text="money(basicSalary(printRow))"></td></tr>
                <tr class="border-b border-gray-200"><td class="py-2">Other Earnings</td><td class="py-2 text-right" x-text="money(printRow.otherEarnings)"></td></tr>
                <tr class="border-b border-gray-800 font-bold"><td class="py-2">Gross Pay</td><td class="py-2 text-right" x-text="money(grossSalary(printRow))"></td></tr>
                <tr class="border-b border-gray-200" x-show="payBasis === 'time'"><td class="py-2">Time Deduction</td><td class="py-2 text-right" x-text="money(timeDeduction(printRow))"></td></tr>
                <tr class="border-b border-gray-200"><td class="py-2">Other Deductions</td><td class="py-2 text-right" x-text="money(printRow.otherDeductions)"></td></tr>
                <tr class="border-b border-gray-800"><td class="py-2">Cash Advance</td><td class="py-2 text-right" x-text="money(printRow.cashAdvance)"></td></tr>
                <tr class="font-bold text-base"><td class="py-3">Net Pay</td><td class="py-3 text-right" x-text="money(netSalary(printRow))"></td></tr>
            </table>
        </div>
    </template>
</div>

<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    function payrollBoard() {
        return {
            employees: [],
            newEmployee: { name: '', role: '', dailyRate: '' },
            fileName: '',
            parseError: '',
            parsing: false,
            dragging: false,
            deleteHistoryModalOpen: false,
            deleteTarget: null,
            periodStart: '',
            periodEnd: '',
            rows: [],
            attendanceDates: [],
            attendanceEmployees: [],
            attendanceGrid: {},
            selectedEmployee: '__ALL__',
            history: [],
            viewingHistoryId: null,
            showRates: false,
            payBasis: 'fixed',
            printMode: null,
            printRow: null,

            init() {
                this.restoreEmployees();
                this.restoreHistory();
            },

            restoreEmployees() {
                const saved = localStorage.getItem('sm-payroll-rate-book');
                if (saved) {
                    try {
                        const parsed = JSON.parse(saved);
                        if (Array.isArray(parsed) && parsed.length) {
                            this.employees = parsed;
                            return;
                        }
                    } catch (error) {
                        this.employees = [];
                    }
                }

                this.loadEmployeesFromDatabase();
            },

            loadEmployeesFromDatabase() {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                fetch('{{ route("payrolls.employees") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                })
                    .then(async response => {
                        if (!response.ok) {
                            this.employees = [];
                            return;
                        }

                        const data = await response.json();
                        this.employees = Array.isArray(data) ? data : [];
                        this.persistEmployees();
                    })
                    .catch(() => {
                        this.employees = [];
                    });
            },

            persistEmployees() {
                localStorage.setItem('sm-payroll-rate-book', JSON.stringify(this.employees));
            },

            addEmployee() {
                if (!this.newEmployee.name || !this.newEmployee.dailyRate) return;
                const nextId = this.employees.length ? Math.max(...this.employees.map(item => item.id)) + 1 : 1;
                this.employees.push({
                    id: nextId,
                    name: this.newEmployee.name.trim().toUpperCase(),
                    role: this.newEmployee.role.trim(),
                    dailyRate: Number(this.newEmployee.dailyRate)
                });
                this.newEmployee = { name: '', role: '', dailyRate: '' };
                this.persistEmployees();
            },

            removeEmployee(employee) {
                if (!confirm(`Remove ${employee.name} from the rate book?`)) return;
                this.employees = this.employees.filter(item => item.id !== employee.id);
                this.persistEmployees();
            },

            restoreHistory() {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                fetch('{{ route("payrolls.history.index") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                })
                    .then(async response => {
                        if (!response.ok) {
                            this.history = [];
                            return;
                        }

                        const payload = await response.json();
                        this.history = Array.isArray(payload?.data) ? payload.data : [];
                        this.persistHistory();
                    })
                    .catch(() => {
                        this.history = [];
                    });
            },

            persistHistory() {
                try {
                    localStorage.setItem('sm-payroll-history', JSON.stringify(this.history));
                } catch (error) {
                    // ignore local storage issues and rely on server persistence
                }
            },

            get sortedHistory() {
                return [...this.history].sort((a, b) => b.generatedAt.localeCompare(a.generatedAt));
            },

            handleFile(file) {
                if (!file) return;
                if (!/\.(xlsx|xls)$/i.test(file.name)) {
                    this.parseError = 'Please upload a valid .xlsx or .xls file.';
                    return;
                }

                this.fileName = file.name;
                this.parsing = true;
                this.parseError = '';

                const reader = new FileReader();
                reader.onload = (event) => {
                    try {
                        const buffer = new Uint8Array(event.target.result);
                        const workbook = XLSX.read(buffer, { type: 'array' });
                        this.processWorkbook(workbook);
                    } catch (error) {
                        this.parseError = 'The Excel file could not be processed.';
                    } finally {
                        this.parsing = false;
                    }
                };
                reader.readAsArrayBuffer(file);
            },

            extractPeriodRangeFromRows(rows) {
                const patterns = [
                    /(\d{4}-\d{2}-\d{2})\s*(?:~|to|\-|–)\s*(\d{4}-\d{2}-\d{2})/i,
                    /period\s*[:\-]?\s*(\d{4}-\d{2}-\d{2})\s*(?:~|to|\-|–)\s*(\d{4}-\d{2}-\d{2})/i,
                ];

                for (const row of rows) {
                    const values = Array.isArray(row) ? row : [row];
                    for (const cell of values) {
                        const text = String(cell ?? '').trim();
                        if (!text) continue;

                        for (const pattern of patterns) {
                            const match = text.match(pattern);
                            if (match) {
                                return { start: match[1], end: match[2] };
                            }
                        }
                    }
                }

                return null;
            },

            syncImportedEmployees() {
                if (!Array.isArray(this.attendanceEmployees) || !this.attendanceEmployees.length) return;

                const payload = this.attendanceEmployees.map(name => {
                    const normalized = String(name || '').trim();
                    const match = this.employees.find(employee => String(employee.name || '').trim().toUpperCase() === normalized.toUpperCase());

                    return {
                        name: normalized,
                        role: match?.role || 'Technician',
                        daily_rate: Number(match?.dailyRate || 500),
                    };
                }).filter(item => item.name);

                if (!payload.length) return;

                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                fetch('{{ route("payrolls.sync-employees") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ employees: payload }),
                }).then(async response => {
                    if (!response.ok) return;

                    try {
                        const data = await response.json();
                        if (!data?.updated) return;

                        const merged = [...this.employees];
                        payload.forEach(item => {
                            const name = String(item.name || '').trim();
                            if (!name) return;

                            const index = merged.findIndex(employee => String(employee.name || '').trim().toUpperCase() === name.toUpperCase());
                            const entry = {
                                id: Date.now() + Math.random(),
                                name: name.toUpperCase(),
                                role: item.role || 'Technician',
                                dailyRate: Number(item.daily_rate || 500),
                            };

                            if (index >= 0) {
                                merged[index] = {
                                    ...merged[index],
                                    role: entry.role,
                                    dailyRate: entry.dailyRate,
                                };
                            } else {
                                merged.push(entry);
                            }
                        });

                        this.employees = merged;
                        this.persistEmployees();
                        this.loadEmployeesFromDatabase();
                    } catch (error) {
                        // ignore malformed response, keep the page working
                    }
                }).catch(() => {});
            },

            processWorkbook(workbook) {
                this.rows = [];
                this.viewingHistoryId = null;

                const summary = this.parseAttendanceSummarySheet(workbook);
                if (summary) {
                    this.attendanceDates = summary.dates;
                    this.attendanceEmployees = summary.employees;
                    this.attendanceGrid = summary.grid;
                    this.selectedEmployee = '__ALL__';
                    this.periodStart = summary.period?.start || '';
                    this.periodEnd = summary.period?.end || '';

                    this.rows = summary.employees.map(name => {
                        const stats = this.computeAttendanceStats(name);
                        return this.makeDraftRow({
                            name,
                            daysWorked: stats.daysWorked,
                            otherEarnings: 0,
                            otherDeductions: 0,
                            cashAdvance: 0,
                            lateMinutes: stats.lateMinutes,
                            earlyOutMinutes: stats.earlyOutMinutes,
                        });
                    });

                    this.syncImportedEmployees();
                    return;
                }

                const cardGrid = this.parseCardReportSheets(workbook);
                if (cardGrid) {
                    this.attendanceDates = cardGrid.dates;
                    this.attendanceEmployees = cardGrid.employees;
                    this.attendanceGrid = cardGrid.grid;
                    this.selectedEmployee = '__ALL__';
                    this.periodStart = cardGrid.period?.start || '';
                    this.periodEnd = cardGrid.period?.end || '';
                    this.rows = cardGrid.employees.map(name => {
                        const stats = this.computeAttendanceStats(name);
                        return this.makeDraftRow({
                            name,
                            daysWorked: stats.daysWorked,
                            otherEarnings: 0,
                            otherDeductions: 0,
                            cashAdvance: 0,
                            lateMinutes: stats.lateMinutes,
                            earlyOutMinutes: stats.earlyOutMinutes,
                        });
                    });
                    this.syncImportedEmployees();
                    return;
                }

                const statsSheetName = this.findStatsSheet(workbook);
                if (!statsSheetName) {
                    this.parseError = 'Could not find an "Attendance Summary", numeric detail sheet, or "Att. Stat." sheet in this file.';
                    return;
                }

                this.buildFromStatsSheet(statsSheetName, workbook);
                this.syncImportedEmployees();
            },

            parseAttendanceSummarySheet(workbook) {
                const candidateNames = workbook.SheetNames.filter(name => /attendance|summary|report/i.test(name));
                if (!candidateNames.length) return null;

                for (const sheetName of candidateNames) {
                    const rows = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName], { header: 1, raw: false, defval: '' });
                    if (!rows.length) continue;

                    const period = this.extractPeriodRangeFromRows(rows);

                    const headerIndex = rows.findIndex(row => {
                        const first = String(row[0] || '').trim().toLowerCase();
                        return first === 'date' || first.includes('date');
                    });
                    if (headerIndex === -1) continue;

                    const header = rows[headerIndex] || [];
                    const secondHeader = rows[headerIndex + 1] || [];
                    const columns = [];

                    for (let c = 1; c < header.length; c++) {
                        const raw = String(header[c] || '').trim();
                        if (!raw) continue;

                        const labeledMatch = raw.match(/^(.*)\s*-\s*(in|out)$/i);
                        if (labeledMatch) {
                            const name = labeledMatch[1].trim();
                            const key = labeledMatch[2].toLowerCase();
                            if (key === 'in') {
                                const nextCell = String(secondHeader[c + 1] || '').trim().toLowerCase();
                                if (nextCell === 'out' || nextCell === 'out time') {
                                    columns.push({ name, inCol: c, outCol: c + 1 });
                                }
                            }
                            continue;
                        }

                        const nextRaw = String(secondHeader[c] || '').trim().toLowerCase();
                        if ((nextRaw === 'in' || nextRaw === 'in time') && String(secondHeader[c + 1] || '').trim().toLowerCase() === 'out') {
                            const name = raw.trim();
                            if (name) columns.push({ name, inCol: c, outCol: c + 1 });
                        }
                    }

                    if (!columns.length) continue;

                    const employees = [...new Set(columns.map(item => item.name))];
                    const grid = {};
                    employees.forEach(name => { grid[name] = []; });
                    const dates = [];

                    for (let r = headerIndex + 1; r < rows.length; r++) {
                        const row = rows[r];
                        const dateLabel = String(row[0] || '').trim();
                        if (!dateLabel) continue;

                        dates.push(dateLabel);
                        columns.forEach(({ name, inCol, outCol }) => {
                            const inValue = String(row[inCol] ?? '').trim();
                            const outValue = String(row[outCol] ?? '').trim();
                            if (!grid[name]) grid[name] = [];
                            grid[name].push({
                                in: inValue || 'Absent',
                                out: outValue || 'Absent',
                            });
                        });
                    }

                    if (!dates.length || !employees.length) continue;

                    return { dates, employees, grid, period };
                }

                return null;
            },

            parseCardReportSheets(workbook) {
                const cardSheets = workbook.SheetNames.filter(name => {
                    const cleaned = String(name).replace(/,/g, '');
                    return /^\d+(\.\d+)*$/.test(cleaned);
                });

                if (!cardSheets.length) return null;

                const employees = [];
                const grid = {};
                let dates = null;
                let period = null;

                for (const sheetName of cardSheets) {
                    const rows = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName], { header: 1, raw: false, defval: '' });
                    if (rows.length < 23) continue;

                    if (!period) {
                        period = this.extractPeriodRangeFromRows(rows);
                    }

                    const nameRow = rows[2] || [];
                    const blockCount = Math.ceil((nameRow.length || 0) / 15);

                    for (let b = 0; b < blockCount; b++) {
                        const base = b * 15;
                        const name = String(nameRow[base + 9] || '').trim();
                        if (!name) continue;

                        const rowDates = [];
                        const recs = [];
                        for (let r = 11; r <= 22 && r < rows.length; r++) {
                            const row = rows[r];
                            rowDates.push(String(row[base] || '').trim());
                            recs.push({
                                in: String(row[base + 1] || '').trim() || 'Absent',
                                out: String(row[base + 3] || '').trim() || 'Absent',
                            });
                        }

                        if (!dates) dates = rowDates;
                        employees.push(name);
                        grid[name] = recs;
                    }
                }

                if (!employees.length || !dates) return null;

                return { dates, employees, grid, period };
            },

            findStatsSheet(workbook) {
                return workbook.SheetNames.find(name => /att.*stat|attendance/i.test(name)) || null;
            },

            buildFromStatsSheet(sheetName, workbook) {
                const sheet = workbook.Sheets[sheetName];
                const rows = XLSX.utils.sheet_to_json(sheet, { header: 1, raw: false, defval: '' });

                const period = this.extractPeriodRangeFromRows(rows);
                if (period) {
                    this.periodStart = period.start;
                    this.periodEnd = period.end;
                }

                const headerIndex = rows.findIndex(row => row.includes('ID') && row.includes('Name'));
                if (headerIndex === -1) {
                    this.parseError = 'The attendance sheet layout could not be matched.';
                    return;
                }

                const employees = [];
                for (let i = headerIndex + 2; i < rows.length; i++) {
                    const row = rows[i];
                    if (!row || !row[1]) continue;
                    const id = row[0];
                    if (isNaN(Number(id))) continue;
                    const name = String(row[1]).trim();
                    if (!name) continue;
                    employees.push({
                        name,
                        daysWorked: Number(String(row[11] || '0/0').split('/')[1] || 0),
                        lateMinutes: Number(row[6] || 0),
                        absentDays: Number(row[13] || 0),
                    });
                }

                if (!employees.length) {
                    this.parseError = 'No employee records were found in the attendance sheet.';
                    return;
                }

                this.attendanceEmployees = employees.map(item => item.name);
                this.selectedEmployee = '__ALL__';
                this.rows = employees.map(item => this.makeDraftRow(item));
            },

            parseClockValue(value) {
                if (!value) return 0;
                const [hours = 0, minutes = 0] = String(value).split(':').map(Number);
                return Number((hours + minutes / 60).toFixed(2));
            },

            buildAttendanceGrid(names, rows) {
                const normalized = {};
                names.forEach(name => normalized[name] = []);
                this.attendanceDates.forEach((date, index) => {
                    names.forEach(name => {
                        if (!normalized[name][index]) normalized[name][index] = { in: 'Absent', out: 'Absent' };
                    });
                });
                return normalized;
            },

            extractDates(rows) {
                return rows
                    .flat()
                    .filter(value => /\d{4}-\d{2}-\d{2}/.test(String(value)))
                    .slice(0, 12);
            },

            derivePeriodStart(rows) {
                const text = rows.flat().join(' ');
                const match = text.match(/(\d{4}-\d{2}-\d{2})/);
                return match ? match[1] : '';
            },

            derivePeriodEnd(rows) {
                const text = rows.flat().join(' ');
                const matches = [...text.matchAll(/(\d{4}-\d{2}-\d{2})/g)];
                return matches.length > 1 ? matches[matches.length - 1][1] : '';
            },

            makeDraftRow(data) {
                const employee = this.employees.find(item => item.name.toUpperCase() === String(data.name).trim().toUpperCase()) || null;
                const dailyRate = Number(employee?.dailyRate || 0);
                const daysWorked = Number(data.daysWorked || 0);
                const otherEarnings = Number(data.otherEarnings || 0);
                const otherDeductions = Number(data.otherDeductions || 0);
                const cashAdvance = Number(data.cashAdvance || 0);
                const lateMinutes = Number(data.lateMinutes || 0);
                const earlyOutMinutes = Number(data.earlyOutMinutes || 0);

                const basic = dailyRate * daysWorked;
                const gross = basic + otherEarnings;
                const net = gross - otherDeductions - cashAdvance;

                return {
                    name: String(data.name).trim(),
                    role: employee?.role || '',
                    position: employee?.role || '',
                    dailyRate,
                    daysWorked,
                    overtimeHours: 0,
                    otherEarnings,
                    otherDeductions,
                    cashAdvance,
                    lateMinutes,
                    earlyOutMinutes,
                    basicSalary: basic,
                    overtimeAmount: 0,
                    grossSalary: gross,
                    netSalary: net,
                    matched: !!employee,
                };
            },

            computeAttendanceStats(name) {
                const key = this.gridKeyFor(name);
                const entries = key ? (this.attendanceGrid[key] || []) : [];
                let workedDays = 0;
                let absentDays = 0;
                let lateMinutes = 0;
                let earlyOutMinutes = 0;

                entries.forEach(entry => {
                    const hasIn = entry && entry.in && entry.in !== 'Absent';
                    const hasOut = entry && entry.out && entry.out !== 'Absent';
                    if (hasIn || hasOut) {
                        workedDays += 1;
                    } else {
                        absentDays += 1;
                    }

                    const inMinutes = this.timeToMinutes(entry?.in);
                    const outMinutes = this.timeToMinutes(entry?.out);
                    if (outMinutes !== null) {
                        if (inMinutes !== null) lateMinutes += Math.max(0, inMinutes - (8 * 60 + 30));
                        earlyOutMinutes += Math.max(0, (17 * 60 + 30) - outMinutes);
                    }
                });

                return {
                    daysWorked: workedDays,
                    overtimeHours: 0,
                    lateMinutes,
                    earlyOutMinutes,
                    absentDays,
                };
            },

            timeToMinutes(value) {
                if (!value || value === 'Absent') return null;
                const match = String(value).trim().match(/^(\d{1,2}):(\d{2})(?:\s*([AP]M))?$/i);
                if (!match) return null;
                let hours = Number(match[1]);
                const minutes = Number(match[2]);
                const meridiem = match[3]?.toUpperCase();
                if (meridiem) {
                    if (hours === 12) hours = 0;
                    if (meridiem === 'PM') hours += 12;
                }
                return hours * 60 + minutes;
            },

            gridKeyFor(name) {
                const target = String(name || '').trim().toLowerCase();
                return Object.keys(this.attendanceGrid || {}).find(key => String(key).trim().toLowerCase() === target) || null;
            },

            basicSalary(row) {
                return Number(row.dailyRate || 0) * Number(row.daysWorked || 0);
            },

            hourlyRate(row) {
                return Number(row.dailyRate || 0) / 8;
            },

            timeDeduction(row) {
                if (this.payBasis !== 'time') return 0;
                return (Number(row.lateMinutes || 0) + Number(row.earlyOutMinutes || 0)) / 60 * this.hourlyRate(row);
            },

            grossSalary(row) {
                return this.basicSalary(row) + Number(row.otherEarnings || 0);
            },

            deductionAmount(row) {
                return Number(row.otherDeductions || 0) + Number(row.cashAdvance || 0) + this.timeDeduction(row);
            },

            netSalary(row) {
                return this.grossSalary(row) - this.deductionAmount(row);
            },

            money(value) {
                return new Intl.NumberFormat('en-PH', {
                    style: 'currency',
                    currency: 'PHP',
                    minimumFractionDigits: 2,
                }).format(Number(value || 0));
            },

            get totalGross() {
                return this.rows.reduce((sum, row) => sum + this.grossSalary(row), 0);
            },

            get totalDeductions() {
                return this.rows.reduce((sum, row) => sum + this.deductionAmount(row), 0);
            },

            get totalNet() {
                return this.rows.reduce((sum, row) => sum + this.netSalary(row), 0);
            },

            clearImport() {
                this.rows = [];
                this.fileName = '';
                this.parseError = '';
                this.periodStart = '';
                this.periodEnd = '';
                this.attendanceDates = [];
                this.attendanceEmployees = [];
                this.attendanceGrid = {};
                this.selectedEmployee = '__ALL__';
                this.viewingHistoryId = null;
                this.printMode = null;
                this.printRow = null;
            },

            savePayrollRun() {
                if (!this.rows.length) return;

                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const payload = {
                    period_start: this.periodStart,
                    period_end: this.periodEnd,
                    pay_basis: this.payBasis,
                    attendance_file: this.fileName || 'manual-import',
                    generated_at: new Date().toLocaleString('sv-SE', { hour12: false }).replace(' ', ' '),
                    attendance: {
                        periodStart: this.periodStart,
                        periodEnd: this.periodEnd,
                        attendanceFile: this.fileName || 'manual-import',
                        attendanceDates: [...this.attendanceDates],
                        attendanceEmployees: [...this.attendanceEmployees],
                        attendanceGrid: JSON.parse(JSON.stringify(this.attendanceGrid || {})),
                        payBasis: this.payBasis,
                        rows: this.rows.map(row => ({
                            name: row.name,
                            lateMinutes: Number(row.lateMinutes || 0),
                            earlyOutMinutes: Number(row.earlyOutMinutes || 0),
                        })),
                    },
                    rows: this.rows.map(row => {
                        const basicSalary = this.basicSalary(row);
                        const grossSalary = this.grossSalary(row);
                        const netSalary = this.netSalary(row);

                        return {
                            name: row.name,
                            role: row.role,
                            position: row.position,
                            dailyRate: Number(row.dailyRate || 0),
                            daysWorked: Number(row.daysWorked || 0),
                            overtimeHours: 0,
                            otherEarnings: Number(row.otherEarnings || 0),
                            otherDeductions: Number(row.otherDeductions || 0),
                            cashAdvance: Number(row.cashAdvance || 0),
                            lateMinutes: Number(row.lateMinutes || 0),
                            earlyOutMinutes: Number(row.earlyOutMinutes || 0),
                            basicSalary,
                            overtimeAmount: 0,
                            grossSalary,
                            netSalary,
                        };
                    })
                };

                fetch('{{ route("payrolls.history.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify(payload),
                })
                    .then(async response => {
                        if (!response.ok) {
                            return;
                        }

                        const saved = await response.json();
                        this.history = [saved, ...this.history];
                        this.persistHistory();
                        this.viewingHistoryId = saved.id;
                        this.printMode = null;
                    })
                    .catch(() => {});
            },

            viewHistory(run) {
                this.viewingHistoryId = run.id;
                this.periodStart = run.periodStart || (run.attendance && run.attendance.periodStart) || '';
                this.periodEnd = run.periodEnd || (run.attendance && run.attendance.periodEnd) || '';
                this.fileName = run.attendanceFile || (run.attendance && run.attendance.attendanceFile) || 'manual-import';
                this.payBasis = run.payBasis || run.pay_basis || run.attendance?.payBasis || 'fixed';

                const attendance = run.attendance || {};
                this.attendanceDates = Array.isArray(attendance.attendanceDates) ? attendance.attendanceDates : [];
                this.attendanceEmployees = Array.isArray(attendance.attendanceEmployees) ? attendance.attendanceEmployees : [];
                this.attendanceGrid = attendance.attendanceGrid || {};

                this.rows = (run.rows || []).map(row => ({
                    ...row,
                    matched: true,
                    basicSalary: Number(row.basicSalary || 0),
                    overtimeAmount: Number(row.overtimeAmount || 0),
                    grossSalary: Number(row.grossSalary || 0),
                    netSalary: Number(row.netSalary || 0),
                    lateMinutes: Number(row.lateMinutes || 0),
                    earlyOutMinutes: Number(row.earlyOutMinutes || 0),
                }));
                this.selectedEmployee = this.attendanceEmployees.length ? '__ALL__' : '__ALL__';
            },

            backToNew() {
                this.viewingHistoryId = null;
                this.rows = [];
                this.periodStart = '';
                this.periodEnd = '';
                this.fileName = '';
                this.selectedEmployee = '__ALL__';
            },

            openDeleteHistoryModal(run) {
                this.deleteTarget = run;
                this.deleteHistoryModalOpen = true;
            },

            closeDeleteHistoryModal() {
                this.deleteHistoryModalOpen = false;
                this.deleteTarget = null;
            },

            confirmDeleteHistory() {
                if (!this.deleteTarget) return;

                const run = this.deleteTarget;
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                fetch(`{{ url('/payrolls/history') }}/${run.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                })
                    .then(response => {
                        if (!response.ok) {
                            return;
                        }

                        this.history = this.history.filter(item => item.id !== run.id);
                        this.persistHistory();
                        if (this.viewingHistoryId === run.id) {
                            this.viewingHistoryId = null;
                            this.rows = [];
                        }
                    })
                    .catch(() => {})
                    .finally(() => {
                        this.closeDeleteHistoryModal();
                    });
            },

            printAttendance() {
                this.printMode = this.selectedEmployee === '__ALL__' ? 'attendance' : 'attendance-single';
                this.$nextTick(() => {
                    setTimeout(() => window.print(), 150);
                });
            },

            printRegister() {
                this.printMode = 'register';
                this.$nextTick(() => {
                    setTimeout(() => window.print(), 150);
                });
            },

            openPayslip(row) {
                this.printRow = row;
                this.printMode = 'payslip';
                this.$nextTick(() => {
                    setTimeout(() => window.print(), 150);
                });
            },
        };
    }
</script>
