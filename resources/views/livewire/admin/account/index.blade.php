@php
    $accounts = [
        [
            'name' => 'PLDT Palengke',
            'account_number' => '0357650186',
            'amount' => null,
            'plan' => '2599',
            'due' => 'September 8, 2026',
            'holder' => 'EUGENIO MAMITES FERNANDEZ JR',
            'landline' => '0846459635',
            'mobile' => '09068237158',
            'service' => 'Fiber Unli Plan 2099 - Voice',
            'status' => 'Due Sep 8',
            'status_class' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300',
        ],
        [
            'name' => 'PLDT CUAMBUGAN',
            'account_number' => '0387817115',
            'amount' => '1,795.00',
            'plan' => null,
            'due' => 'September 24, 2026',
            'holder' => 'EDGAR JUDILLA CRIBELLO',
            'landline' => '0842167266',
            'mobile' => null,
            'service' => 'Fiber Unli Plan 1699 - Voice',
            'status' => 'Due Sep 24',
            'status_class' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300',
        ],
        [
            'name' => 'PLDT Macgum',
            'account_number' => null,
            'amount' => '1,889.00',
            'plan' => null,
            'due' => 'September 24, 2026',
            'holder' => 'EUGENIO JR MAMITES FERNANDEZ',
            'landline' => '0842168766',
            'mobile' => null,
            'service' => 'Fiber Unli Plan 2099 - Voice',
            'status' => 'Due Sep 24',
            'status_class' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300',
        ],
        [
            'name' => 'PLDT Macgum',
            'account_number' => '0340486617',
            'amount' => '3,787.00',
            'plan' => null,
            'due' => 'September 24, 2026',
            'holder' => 'EUGENIO MAMITES FERNANDEZ JR',
            'landline' => '0848292390',
            'mobile' => null,
            'service' => 'Fiber Unli Plan 3799 - Voice',
            'status' => 'Due Sep 24',
            'status_class' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300',
        ],
        [
            'name' => 'PLDT Sasutel',
            'account_number' => '0364503008',
            'amount' => '2,092.00',
            'plan' => null,
            'due' => 'September 5, 2026',
            'holder' => 'EUGENIO JR MAMITES FERNANDEZ',
            'landline' => '0848292763',
            'mobile' => null,
            'service' => 'Fiber Unli Plan 2099 - Voice',
            'status' => 'Due today',
            'status_class' => 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-300',
        ],
        [
            'name' => 'SWAWON',
            'account_number' => '0393678321',
            'amount' => null,
            'plan' => null,
            'due' => null,
            'holder' => 'EDRED FERNANDEZ ANONAS',
            'landline' => '0842171871',
            'mobile' => null,
            'service' => 'Fiber Unli Plan 1699 - Voice',
            'plan_type' => 'Postpaid',
            'status' => 'No due date',
            'status_class' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
        ],
    ];
@endphp

<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white sm:text-2xl">Accounts</h1>
            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">PLDT account details and payment reminders</p>
        </div>
        <span class="inline-flex items-center gap-2 rounded-xl bg-brand-50 px-3 py-2 text-xs font-semibold text-brand-700 dark:bg-brand-900/20 dark:text-brand-300">
            <span class="h-2 w-2 rounded-full bg-brand-500" aria-hidden="true"></span>
            Reference information
        </span>
    </div>

    <section aria-label="Account summary" class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-900/20 dark:text-brand-300" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7zm0 2h18m-5 4h2" /></svg>
                </div>
                <div><p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Accounts</p><p class="text-lg font-bold text-gray-900 dark:text-white">{{ count($accounts) }}</p></div>
            </div>
        </div>
        <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-900/20 dark:text-amber-300" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div><p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Upcoming Due</p><p class="text-lg font-bold text-gray-900 dark:text-white">4</p></div>
            </div>
        </div>
        <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-300" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <div><p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Due Today</p><p class="text-lg font-bold text-gray-900 dark:text-white">1</p></div>
            </div>
        </div>
        <div class="rounded-2xl bg-white p-4 shadow-card dark:bg-gray-800">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-300" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622C17.176 19.29 21 14.591 21 9c0-.695-.06-1.376-.176-2.016z" /></svg>
                </div>
                <div><p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Data Source</p><p class="text-lg font-bold text-gray-900 dark:text-white">Manual</p></div>
            </div>
        </div>
    </section>

    <section aria-label="PLDT accounts" class="grid grid-cols-1 gap-4 xl:grid-cols-2">
        @foreach ($accounts as $account)
            <article class="rounded-2xl bg-white p-5 shadow-card dark:bg-gray-800">
                <div class="flex items-start justify-between gap-3 border-b border-gray-100 pb-4 dark:border-gray-700">
                    <div class="flex min-w-0 items-center gap-3">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-brand-50 text-sm font-bold text-brand-600 dark:bg-brand-900/20 dark:text-brand-300" aria-hidden="true">{{ strtoupper(substr($account['name'], 0, 2)) }}</div>
                        <div class="min-w-0">
                            <h2 class="truncate text-base font-bold text-gray-900 dark:text-white">{{ $account['name'] }}</h2>
                            <p class="mt-0.5 truncate text-xs text-gray-500 dark:text-gray-400">{{ $account['service'] }}</p>
                        </div>
                    </div>
                    <span class="flex-shrink-0 rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $account['status_class'] }}">{{ $account['status'] }}</span>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-x-5 gap-y-3 text-sm">
                    <div><p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">Account No.</p><p class="mt-0.5 font-medium text-gray-800 dark:text-gray-200">{{ $account['account_number'] ?? 'Not provided' }}</p></div>
                    <div><p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">Amount</p><p class="mt-0.5 font-semibold text-gray-900 dark:text-white">{{ $account['amount'] ? 'Php ' . $account['amount'] : 'Not provided' }}</p></div>
                    <div><p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">Due Date</p><p class="mt-0.5 font-medium text-gray-800 dark:text-gray-200">{{ $account['due'] ?? 'Not provided' }}</p></div>
                    <div><p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">Plan</p><p class="mt-0.5 font-medium text-gray-800 dark:text-gray-200">{{ $account['plan'] ?? 'Not provided' }}</p></div>
                </div>

                <div class="mt-4 rounded-xl bg-gray-50 p-3 dark:bg-gray-900/60">
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">Account Holder</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $account['holder'] }}</p>
                    <div class="mt-3 grid grid-cols-1 gap-2 text-xs text-gray-600 dark:text-gray-300 sm:grid-cols-2">
                        <p><span class="text-gray-400">Landline:</span> {{ $account['landline'] }}</p>
                        @if ($account['mobile'])<p><span class="text-gray-400">Mobile:</span> {{ $account['mobile'] }}</p>@endif
                        @if (isset($account['plan_type']))<p><span class="text-gray-400">Plan Type:</span> {{ $account['plan_type'] }}</p>@endif
                    </div>
                </div>
            </article>
        @endforeach
    </section>

    <p class="text-xs text-gray-400 dark:text-gray-500">These account details are manually maintained reference information and are not stored in the database.</p>
</div>
