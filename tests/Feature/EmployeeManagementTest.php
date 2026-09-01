<?php

namespace Tests\Feature;

use App\Livewire\Admin\Employee\Index;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EmployeeManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_page_can_create_an_employee(): void
    {
        Livewire::test(Index::class)
            ->set('name', 'Juan Dela Cruz')
            ->set('position', 'Technician')
            ->set('contactNumber', '09171234567')
            ->set('dailyRate', '750')
            ->set('dateStarted', '2026-01-15')
            ->set('status', 'active')
            ->call('saveEmployee')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('employees', [
            'name' => 'Juan Dela Cruz',
            'position' => 'Technician',
            'daily_rate' => '750.00',
            'status' => 'active',
        ]);
    }
}
