<?php

namespace Tests\Feature;

use App\Livewire\Admin\VendoUnit\Index;
use App\Models\Area;
use App\Models\VendoPartner;
use App\Models\VendoUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class VendoUnitManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_vendo_unit_page_can_create_a_unit(): void
    {
        Livewire::test(Index::class)
            ->set('unitName', 'VM-01')
            ->set('unitKey', 'KBLUE-01')
            ->set('unitStatus', 'ready')
            ->set('unitDescription', 'Main vending unit')
            ->set('unitConditionNotes', 'Fresh install')
            ->call('saveUnit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('vendo_units', [
            'name' => 'VM-01',
            'key' => 'KBLUE-01',
            'status' => 'ready',
            'description' => 'Main vending unit',
            'condition_notes' => 'Fresh install',
        ]);
    }

    public function test_vendo_unit_cannot_be_deleted_when_assigned_to_partner(): void
    {
        $area = Area::create([
            'code' => 'AREA-01',
            'name' => 'Davao City',
        ]);

        $unit = VendoUnit::create([
            'name' => 'VM-02',
            'key' => 'KBLUE-02',
            'status' => 'ready',
        ]);

        VendoPartner::create([
            'area_id' => $area->id,
            'name' => 'Sample Partner',
            'address' => 'Davao City',
            'contact_number' => '09171234567',
            'vendo_unit_id' => $unit->id,
            'status' => 'active',
            'share_rate' => 30.00,
        ]);

        Livewire::test(Index::class)
            ->set('deleteUnitId', $unit->id)
            ->call('confirmDelete')
            ->assertSet('deleteErrorMessage', 'This vendo unit cannot be deleted because it is still assigned to a vendo partner.');

        $this->assertDatabaseHas('vendo_units', [
            'id' => $unit->id,
            'name' => 'VM-02',
        ]);
    }

    public function test_vendo_units_can_be_added_in_bulk(): void
    {
        Livewire::test(Index::class)
            ->call('openBulkCreateModal')
            ->set('bulkNamePrefix', 'VM-')
            ->set('bulkKeyPrefix', 'KBLUE-')
            ->set('bulkStartNumber', '03')
            ->set('bulkQuantity', 2)
            ->call('generateBulkUnits')
            ->assertHasNoErrors()
            ->assertSet('bulkUnits.0.name', 'VM-03')
            ->assertSet('bulkUnits.0.key', 'KBLUE-03')
            ->call('saveBulkUnits')
            ->assertHasNoErrors()
            ->assertSet('showBulkCreateModal', false);

        $this->assertDatabaseHas('vendo_units', ['name' => 'VM-03', 'key' => 'KBLUE-03', 'status' => 'ready']);
        $this->assertDatabaseHas('vendo_units', ['name' => 'VM-04', 'key' => 'KBLUE-04', 'status' => 'ready']);
    }
}
