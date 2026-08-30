<?php

namespace Tests\Feature;

use App\Livewire\Admin\Area\Index;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AreaManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_area_page_can_create_an_area(): void
    {
        Livewire::test(Index::class)
            ->set('areaCode', 'A-101')
            ->set('areaName', 'Warehouse A')
            ->call('saveArea')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('areas', [
            'code' => 'A-101',
            'name' => 'Warehouse A',
        ]);
    }
}
