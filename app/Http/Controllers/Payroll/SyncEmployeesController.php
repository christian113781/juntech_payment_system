<?php

namespace App\Http\Controllers\Payroll;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SyncEmployeesController
{
    public function __invoke(Request $request): JsonResponse
    {
        $employees = $request->input('employees', []);

        if (!is_array($employees)) {
            return response()->json([
                'created' => 0,
                'skipped' => 0,
                'message' => 'Invalid employee list.',
            ], 422);
        }

        $created = 0;
        $skipped = 0;

        foreach ($employees as $employee) {
            $name = trim((string) ($employee['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $normalizedName = strtoupper($name);
            $existing = Employee::query()
                ->whereRaw('LOWER(name) = ?', [mb_strtolower($normalizedName)])
                ->first();

            if ($existing) {
                $skipped++;
                continue;
            }

            Employee::query()->create([
                'name' => $normalizedName,
                'position' => 'Technician',
                'daily_rate' => 500,
                'status' => 'active',
                'date_started' => now()->toDateString(),
            ]);

            $created++;
        }

        return response()->json([
            'created' => $created,
            'skipped' => $skipped,
            'updated' => $created > 0 || $skipped > 0,
        ]);
    }
}
