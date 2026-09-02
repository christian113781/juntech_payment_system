<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PayrollHistoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_saves_and_reloads_a_payroll_run_from_the_server(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $this->actingAs($user);

        $employee = Employee::query()->create([
            'name' => 'MARC',
            'position' => 'Technician',
            'daily_rate' => 500,
            'status' => 'active',
            'date_started' => now()->toDateString(),
        ]);

        $response = $this->postJson('/payrolls/history', [
            'period_start' => '2026-09-01',
            'period_end' => '2026-09-15',
            'attendance_file' => 'attendance.xlsx',
            'generated_at' => '2026-09-02 12:00:00',
            'attendance' => [
                'periodStart' => '2026-09-01',
                'periodEnd' => '2026-09-15',
                'attendanceFile' => 'attendance.xlsx',
                'attendanceDates' => ['2026-09-01', '2026-09-02'],
                'attendanceEmployees' => ['MARC'],
                'attendanceGrid' => [
                    'MARC' => [
                        ['in' => '08:00', 'out' => '17:00'],
                        ['in' => 'Absent', 'out' => 'Absent'],
                    ],
                ],
            ],
            'rows' => [[
                'name' => 'MARC',
                'role' => 'Technician',
                'position' => 'Technician',
                'dailyRate' => 500,
                'daysWorked' => 10,
                'overtimeHours' => 2,
                'otherEarnings' => 0,
                'otherDeductions' => 0,
                'cashAdvance' => 0,
                'basicSalary' => 5000,
                'overtimeAmount' => 1250,
                'grossSalary' => 6250,
                'netSalary' => 6250,
            ]],
        ]);

        $response->assertOk();
        $payload = $response->json();

        $this->assertNotNull($payload['id'] ?? null);
        $this->assertSame('2026-09-01', $payload['attendance']['periodStart']);
        $this->assertSame('MARC', $payload['attendance']['attendanceEmployees'][0]);
        $this->assertDatabaseHas('payroll_runs', [
            'id' => $payload['id'],
            'period_start' => '2026-09-01 00:00:00',
            'period_end' => '2026-09-15 00:00:00',
        ]);

        $this->assertDatabaseHas('payrolls', [
            'payroll_run_id' => $payload['id'],
            'employee_id' => $employee->id,
            'daily_rate' => '500.00',
        ]);

        $list = $this->getJson('/payrolls/history');
        $list->assertOk();
        $this->assertGreaterThanOrEqual(1, count($list->json('data')));
    }
}
