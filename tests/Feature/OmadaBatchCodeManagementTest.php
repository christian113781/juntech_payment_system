<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Area;
use App\Models\OmadaPartner;
use App\Models\OmadaVoucherBatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class OmadaBatchCodeManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_omada_batch_code_page_can_be_loaded(): void
    {
        $area = Area::factory()->create(['name' => 'Davao']);
        $partner = OmadaPartner::factory()->create(['area_id' => $area->id]);

        $response = $this->get(route('omada-batch-codes.index', $partner));

        $response->assertStatus(200);
    }

    public function test_omada_batch_code_page_can_create_a_batch(): void
    {
        $area = Area::factory()->create(['name' => 'Davao']);
        $partner = OmadaPartner::factory()->create(['area_id' => $area->id]);

        Livewire::test('admin.omada-batch-code.index', ['partner' => $partner])
            ->set('type', 'SALE')
            ->set('requestedQty', 100)
            ->set('bonusQty', 5)
            ->set('pricePerVoucher', 10)
            ->call('saveBatch')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('omada_voucher_batches', [
            'partner_id' => $partner->id,
            'type' => 'SALE',
            'requested_qty' => 100,
            'bonus_qty' => 5,
            'price_per_voucher' => 10,
            'status' => 'pending',
        ]);
    }

    public function test_omada_batch_code_status_can_be_updated_and_paid_with_collection_details(): void
    {
        $area = Area::factory()->create(['name' => 'Davao']);
        $partner = OmadaPartner::factory()->create(['area_id' => $area->id]);
        $batch = OmadaVoucherBatch::factory()->create([
            'partner_id' => $partner->id,
            'status' => 'pending',
            'requested_qty' => 100,
            'price_per_voucher' => 12.50,
        ]);

        Livewire::test('admin.omada-batch-code.index', ['partner' => $partner])
            ->call('openStatusModal', $batch->id)
            ->set('statusValue', 'delivered')
            ->call('saveStatus')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('omada_voucher_batches', [
            'id' => $batch->id,
            'status' => 'delivered',
        ]);

        Livewire::test('admin.omada-batch-code.index', ['partner' => $partner])
            ->call('openPaymentModal', $batch->id)
            ->set('paymentCollectionDate', '2026-08-30')
            ->set('paymentTotalAmount', '1250.00')
            ->set('paymentRemarks', 'Collected')
            ->call('savePayment')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('omada_voucher_batches', [
            'id' => $batch->id,
            'status' => 'paid',
        ]);

        $this->assertDatabaseHas('omada_voucher_collections', [
            'batch_id' => $batch->id,
            'collection_date' => '2026-08-30 00:00:00',
            'total_amount' => 1250,
            'remarks' => 'Collected',
        ]);
    }
}
