<?php

namespace Tests\Feature;

use App\Livewire\Admin\Omada\Index;
use App\Models\Area;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class OmadaPartnerManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_omada_partner_page_can_create_a_partner(): void
    {
        $area = Area::create([
            'code' => 'AREA-01',
            'name' => 'Davao City',
        ]);

        Livewire::test(Index::class)
            ->set('partnerName', 'Coastal Net Cafe')
            ->set('partnerAreaId', $area->id)
            ->set('partnerContactNumber', '+63 917 555 0142')
            ->set('partnerAddress', 'Davao City')
            ->call('savePartner')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('omada_partners', [
            'name' => 'Coastal Net Cafe',
            'area_id' => $area->id,
            'contact_number' => '639175550142',
            'address' => 'Davao City',
        ]);
    }
}
