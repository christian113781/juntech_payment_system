<?php

namespace App\Http\Controllers\Payroll;

use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class EmployeeListController
{
    public function __invoke(): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json([], 401);
        }

        $employees = Employee::query()
            ->orderBy('name')
            ->get(['id', 'name', 'position', 'daily_rate'])
            ->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => strtoupper((string) $employee->name),
                    'role' => $employee->position ?: 'Technician',
                    'dailyRate' => (float) $employee->daily_rate ?: 500,
                ];
            })
            ->values()
            ->all();

        return response()->json($employees);
    }
}
