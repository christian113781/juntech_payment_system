<?php

namespace Tests\Feature;

use App\Livewire\Admin\VendoPartner\Index;
use App\Models\Area;
use App\Models\VendoPartner;
use App\Models\VendoUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class VendoPartnerManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_vendo_partner_page_can_create_a_partner(): void
    {
        $area = Area::create([
            'code' => 'AREA-01',
            'name' => 'Davao City',
        ]);

        $unit = VendoUnit::create([
            'name' => 'VM-20',
            'key' => 'KBLUE-20',
            'status' => 'ready',
        ]);

        Livewire::test(Index::class)
            ->set('partnerName', 'John Doe')
            ->set('partnerAreaId', $area->id)
            ->set('partnerUnitId', $unit->id)
            ->set('partnerContactNumber', '09171234567')
            ->set('partnerAddress', 'Davao City')
            ->set('partnerStatus', 'active')
            ->set('partnerShareRate', '30.00')
            ->call('savePartner')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('vendo_partners', [
            'name' => 'John Doe',
            'area_id' => $area->id,
            'vendo_unit_id' => $unit->id,
            'status' => 'active',
            'share_rate' => '30.00',
        ]);
    }

    public function test_vendo_partner_page_only_shows_ready_units_for_assignment(): void
    {
        $area = Area::create([
            'code' => 'AREA-02',
            'name' => 'Cebu City',
        ]);

        $ready = VendoUnit::create([
            'name' => 'VM-READY',
            'key' => 'READY-01',
            'status' => 'ready',
        ]);

        VendoUnit::create([
            'name' => 'VM-ASSIGNED',
            'key' => 'ASSIGNED-01',
            'status' => 'assigned',
        ]);

        Livewire::test(Index::class)
            ->call('refreshAssignableUnits')
            ->assertSet('assignableUnits', [[
                'id' => $ready->id,
                'name' => 'VM-READY',
                'key' => 'READY-01',
                'status' => 'ready',
            ]]);

        $this->assertDatabaseHas('areas', [
            'id' => $area->id,
            'name' => 'Cebu City',
        ]);
    }

    public function test_vendo_partner_page_can_delete_a_partner(): void
    {
        $area = Area::create([
            'code' => 'AREA-03',
            'name' => 'Cebu City',
        ]);

        $partner = VendoPartner::create([
            'area_id' => $area->id,
            'name' => 'Jane Smith',
            'contact_number' => '09181234567',
            'address' => 'Cebu City',
            'status' => 'inactive',
            'share_rate' => 25.00,
        ]);

        Livewire::test(Index::class)
            ->set('deletePartnerId', $partner->id)
            ->call('confirmDelete')
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('vendo_partners', [
            'id' => $partner->id,
        ]);
    }

    public function test_vendo_partner_page_shows_days_left_until_collection_due(): void
    {
        $area = Area::create([
            'code' => 'AREA-04',
            'name' => 'Digos City',
        ]);

        VendoPartner::create([
            'area_id' => $area->id,
            'name' => 'Due Soon Partner',
            'contact_number' => '09181234568',
            'address' => 'Digos City',
            'status' => 'active',
            'share_rate' => 30.00,
            'last_collected_at' => now()->subDays(31)->toDateString(),
            'collection_interval_days' => 32,
        ]);

        Livewire::test(Index::class)
            ->call('loadPartners')
            ->assertSet('partners.0.days_left', 1)
            ->assertSet('partners.0.collection_label', '1 day left');
    }

    public function test_vendo_partner_page_can_record_a_collection_from_collect_now_modal(): void
    {
        $area = Area::create([
            'code' => 'AREA-05',
            'name' => 'General Santos City',
        ]);

        $unit = VendoUnit::create([
            'name' => 'VM-30',
            'key' => 'GSC-30',
            'status' => 'assigned',
        ]);

        $partner = VendoPartner::create([
            'area_id' => $area->id,
            'vendo_unit_id' => $unit->id,
            'name' => 'Collection Partner',
            'contact_number' => '09181234569',
            'address' => 'General Santos City',
            'status' => 'active',
            'share_rate' => 30.00,
            'last_collected_at' => now()->subDays(10)->toDateString(),
            'collection_interval_days' => 32,
        ]);

        Livewire::test(Index::class)
            ->call('openCollectModal', $partner->id)
            ->set('collectionDate', now()->toDateString())
            ->set('collectionAmount', '2500.00')
            ->call('saveCollection')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('vendo_collections', [
            'partner_id' => $partner->id,
            'total_amount' => '2500.00',
        ]);

        $collection = \App\Models\VendoCollection::query()->where('partner_id', $partner->id)->latest()->first();

        $this->assertNotNull($collection);
        $this->assertSame(now()->toDateString(), $collection->collection_date->toDateString());

        $partnerFresh = \App\Models\VendoPartner::find($partner->id);

        $this->assertNotNull($partnerFresh);
        $this->assertSame(now()->toDateString(), $partnerFresh->last_collected_at->toDateString());
    }

    public function test_vendo_collection_page_uses_the_selected_partner_id(): void
    {
        $area = Area::create([
            'code' => 'AREA-07',
            'name' => 'Butuan City',
        ]);

        $unit = VendoUnit::create([
            'name' => 'VM-50',
            'key' => 'BUT-50',
            'status' => 'assigned',
        ]);

        $partner = VendoPartner::create([
            'area_id' => $area->id,
            'vendo_unit_id' => $unit->id,
            'name' => 'Butuan Partner',
            'contact_number' => '09181234571',
            'address' => 'Butuan City',
            'status' => 'active',
            'share_rate' => 25.00,
            'last_collected_at' => now()->subDays(20)->toDateString(),
            'collection_interval_days' => 32,
        ]);

        $collection = \App\Models\VendoCollection::create([
            'partner_id' => $partner->id,
            'collection_date' => now()->subDay()->toDateString(),
            'total_amount' => 1500.00,
            'share_amount' => 375.00,
            'owner_amount' => 1125.00,
            'remarks' => 'Cash collection',
        ]);

        $component = Livewire::test(\App\Livewire\Admin\VendoCollection\Index::class, ['partner' => $partner]);

        $component->assertSet('partner.id', $partner->id)
            ->assertSet('collections.0.id', $collection->id)
            ->assertSet('collections.0.partner_id', $partner->id);
    }

    public function test_edit_modal_keeps_current_assigned_unit_visible(): void
    {
        $area = Area::create([
            'code' => 'AREA-06',
            'name' => 'Tagum City',
        ]);

        $unit = VendoUnit::create([
            'name' => 'VM-40',
            'key' => 'TAG-40',
            'status' => 'assigned',
        ]);

        $partner = VendoPartner::create([
            'area_id' => $area->id,
            'vendo_unit_id' => $unit->id,
            'name' => 'Assigned Partner',
            'contact_number' => '09181234570',
            'address' => 'Tagum City',
            'status' => 'active',
            'share_rate' => 35.00,
            'last_collected_at' => now()->subDays(5)->toDateString(),
            'collection_interval_days' => 32,
        ]);

        $component = Livewire::test(Index::class);
        $component->call('openEditModal', $partner->id);

        $this->assertTrue(collect($component->get('assignableUnits'))->contains(fn ($availableUnit) => (int) $availableUnit['id'] === $unit->id));
    }
}
