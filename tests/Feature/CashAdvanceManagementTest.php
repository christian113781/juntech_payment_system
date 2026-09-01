<?php

namespace Tests\Feature;

use App\Livewire\Admin\Employee\CashAdvance\Index;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CashAdvanceManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_cash_advance_page_can_create_a_cash_advance(): void
    {
        $employee = Employee::create([
            'name' => 'Maria Santos',
            'position' => 'Cashier',
            'contact_number' => '09171234567',
            'daily_rate' => 700,
            'date_started' => '2024-01-15',
            'status' => 'active',
        ]);

        Livewire::test(Index::class, ['employee' => $employee])
            ->set('advanceDate', '2026-09-01')
            ->set('amount', '3000')
            ->set('remarks', 'School supplies')
            ->call('saveAdvance')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('employee_cash_advances', [
            'employee_id' => $employee->id,
            'amount' => '3000.00',
            'remarks' => 'School supplies',
        ]);
    }
}
