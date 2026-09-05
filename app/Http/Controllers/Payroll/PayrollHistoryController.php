<?php

namespace App\Http\Controllers\Payroll;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollRun;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class PayrollHistoryController
{
    public function index(): JsonResponse
    {
        $runs = PayrollRun::query()
            ->with('payrolls.employee')
            ->orderByDesc('generated_at')
            ->get()
            ->map(fn (PayrollRun $run) => $this->serializeRun($run))
            ->values();

        return response()->json([
            'data' => $runs,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'period_start' => ['required', 'string'],
            'period_end' => ['required', 'string'],
            'attendance_file' => ['nullable', 'string'],
            'generated_at' => ['nullable', 'string'],
            'pay_basis' => ['nullable', 'in:fixed,time'],
            'attendance' => ['nullable', 'array'],
            'rows' => ['required', 'array'],
        ]);

        $attendanceData = $validated['attendance'] ?? [
            'periodStart' => $validated['period_start'],
            'periodEnd' => $validated['period_end'],
            'attendanceFile' => $validated['attendance_file'] ?? 'manual-import',
            'attendanceDates' => [],
            'attendanceEmployees' => [],
            'attendanceGrid' => [],
            'rows' => $validated['rows'],
        ];
        $attendanceData['payBasis'] = $validated['pay_basis'] ?? 'fixed';
        $attendanceData['rows'] = $validated['rows'];

        $run = PayrollRun::query()->create([
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'attendance_file' => $validated['attendance_file'] ?? 'manual-import',
            'attendance_file_original_name' => $validated['attendance_file'] ?? 'manual-import',
            'attendance_file_size' => 0,
            'generated_at' => $validated['generated_at'] ?? now(),
            'attendance_data' => $attendanceData,
        ]);

        $summary = [
            'id' => $run->id,
            'periodStart' => $run->period_start->format('Y-m-d'),
            'periodEnd' => $run->period_end->format('Y-m-d'),
            'attendanceFile' => $run->attendance_file_original_name,
            'generatedAt' => $run->generated_at->format('Y-m-d H:i:s'),
            'attendance' => $attendanceData,
            'payBasis' => $attendanceData['payBasis'] ?? 'fixed',
            'rows' => $validated['rows'],
        ];

        Storage::disk('local')->put('payroll-runs/' . $run->id . '.json', json_encode($summary, JSON_PRETTY_PRINT));

        foreach ($validated['rows'] as $row) {
            $name = trim((string) ($row['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $employee = Employee::query()
                ->whereRaw('UPPER(name) = ?', [strtoupper($name)])
                ->first();

            if (! $employee) {
                $employee = Employee::query()->create([
                    'name' => strtoupper($name),
                    'position' => 'Technician',
                    'daily_rate' => 500,
                    'status' => 'active',
                    'date_started' => now()->toDateString(),
                ]);
            }

            Payroll::query()->create([
                'payroll_run_id' => $run->id,
                'employee_id' => $employee->id,
                'daily_rate' => (float) ($row['dailyRate'] ?? $employee->daily_rate ?? 0),
                'days_worked' => (float) ($row['daysWorked'] ?? 0),
                'overtime_hours' => (float) ($row['overtimeHours'] ?? 0),
                'basic_salary' => (float) ($row['basicSalary'] ?? 0),
                'overtime_amount' => (float) ($row['overtimeAmount'] ?? 0),
                'other_earnings' => (float) ($row['otherEarnings'] ?? 0),
                'other_deductions' => (float) ($row['otherDeductions'] ?? 0),
                'cash_advance_deduction' => (float) ($row['cashAdvance'] ?? 0),
                'gross_salary' => (float) ($row['grossSalary'] ?? 0),
                'net_salary' => (float) ($row['netSalary'] ?? 0),
                'remarks' => null,
            ]);
        }

        return response()->json($this->serializeRun($run->fresh(['payrolls.employee'])));
    }

    public function show(PayrollRun $payrollRun): JsonResponse
    {
        return response()->json($this->serializeRun($payrollRun->fresh(['payrolls.employee'])));
    }

    public function destroy(PayrollRun $payrollRun): JsonResponse
    {
        Storage::disk('local')->delete('payroll-runs/' . $payrollRun->id . '.json');
        $payrollRun->delete();

        return response()->json(['deleted' => true]);
    }

    private function serializeRun(PayrollRun $run): array
    {
        $rows = $run->payrolls->map(function (Payroll $payroll) use ($run) {
            $employee = $payroll->employee;
            $name = $employee?->name ?? 'Unknown';
            $snapshot = collect($run->attendance_data['rows'] ?? [])
                ->first(fn (array $row) => strcasecmp((string) ($row['name'] ?? ''), $name) === 0) ?? [];

            return [
                'id' => $payroll->id,
                'name' => $name,
                'role' => $employee?->position ?? 'Technician',
                'position' => $employee?->position ?? 'Technician',
                'dailyRate' => (float) ($payroll->daily_rate ?? 0),
                'daysWorked' => (float) ($payroll->days_worked ?? 0),
                'overtimeHours' => (float) ($payroll->overtime_hours ?? 0),
                'otherEarnings' => (float) ($payroll->other_earnings ?? 0),
                'otherDeductions' => (float) ($payroll->other_deductions ?? 0),
                'cashAdvance' => (float) ($payroll->cash_advance_deduction ?? 0),
                'basicSalary' => (float) ($payroll->basic_salary ?? 0),
                'overtimeAmount' => (float) ($payroll->overtime_amount ?? 0),
                'grossSalary' => (float) ($payroll->gross_salary ?? 0),
                'netSalary' => (float) ($payroll->net_salary ?? 0),
                'lateMinutes' => (float) ($snapshot['lateMinutes'] ?? 0),
                'earlyOutMinutes' => (float) ($snapshot['earlyOutMinutes'] ?? 0),
            ];
        })->values()->all();

        return [
            'id' => $run->id,
            'periodStart' => $run->period_start?->format('Y-m-d') ?? '',
            'periodEnd' => $run->period_end?->format('Y-m-d') ?? '',
            'attendanceFile' => $run->attendance_file_original_name ?? $run->attendance_file ?? 'manual-import',
            'generatedAt' => $run->generated_at?->format('Y-m-d H:i:s') ?? '',
            'payBasis' => $run->attendance_data['payBasis'] ?? 'fixed',
            'attendance' => $run->attendance_data ?? [
                'periodStart' => $run->period_start?->format('Y-m-d') ?? '',
                'periodEnd' => $run->period_end?->format('Y-m-d') ?? '',
                'attendanceFile' => $run->attendance_file_original_name ?? $run->attendance_file ?? 'manual-import',
                'attendanceDates' => [],
                'attendanceEmployees' => [],
                'attendanceGrid' => [],
                'rows' => $rows,
            ],
            'rows' => $rows,
        ];
    }
}
