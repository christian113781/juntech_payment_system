<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SyncEmployeesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_default_employee_records_when_imported_names_are_missing(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->assertDatabaseCount('employees', 0);

        $response = $this->postJson('/payrolls/sync-employees', [
            'employees' => [
                ['name' => 'MARC'],
                ['name' => 'JOJO'],
            ],
        ]);

        $response->assertOk();
        $response->assertJsonPath('created', 2);
        $response->assertJsonPath('updated', true);

        $this->assertDatabaseHas('employees', [
            'name' => 'MARC',
            'position' => 'Technician',
            'daily_rate' => '500.00',
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('employees', [
            'name' => 'JOJO',
            'position' => 'Technician',
            'daily_rate' => '500.00',
            'status' => 'active',
        ]);

        $this->assertSame(2, Employee::query()->count());
    }

    public function test_it_does_not_overwrite_existing_employee_details_on_import(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $employee = Employee::query()->create([
            'name' => 'MARC',
            'position' => 'Supervisor',
            'daily_rate' => 750,
            'status' => 'active',
            'date_started' => now()->toDateString(),
        ]);

        $response = $this->postJson('/payrolls/sync-employees', [
            'employees' => [
                ['name' => 'MARC'],
                ['name' => 'JOJO'],
            ],
        ]);

        $response->assertOk();
        $response->assertJsonPath('created', 1);
        $response->assertJsonPath('skipped', 1);

        $employee->refresh();
        $this->assertSame('Supervisor', $employee->position);
        $this->assertSame('750.00', (string) $employee->daily_rate);

        $this->assertDatabaseHas('employees', [
            'name' => 'JOJO',
            'position' => 'Technician',
            'daily_rate' => '500.00',
        ]);
    }
}
