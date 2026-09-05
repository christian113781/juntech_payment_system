<?php

namespace Tests\Feature;

use App\Livewire\Admin\Area\Index;
use App\Models\Area;
use App\Models\OmadaPartner;
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

    public function test_assigned_area_cannot_be_deleted(): void
    {
        $area = Area::factory()->create();
        $partner = OmadaPartner::create([
            'name' => 'Omada Partner',
            'contact_number' => '09170000000',
            'area_id' => $area->id,
            'address' => 'Main office',
        ]);

        Livewire::test(Index::class)
            ->call('openDeleteModal', $area->id)
            ->call('confirmDelete')
            ->assertNoRedirect();

        $this->assertDatabaseHas('areas', ['id' => $area->id]);
        $this->assertDatabaseHas('omada_partners', ['id' => $partner->id]);
    }
}
