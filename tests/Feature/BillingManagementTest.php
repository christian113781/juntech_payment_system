<?php

namespace Tests\Feature;

use App\Livewire\Admin\Billing\Index as BillingIndex;
use App\Models\Area;
use App\Models\Billing;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BillingManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_creates_next_cycle_bill_and_keeps_latest_future_bill_on_time(): void
    {
        $area = Area::factory()->create([
            'name' => 'Main Area',
        ]);

        $customer = Customer::create([
            'name' => 'Juan Dela Cruz',
            'area_id' => $area->id,
            'contact_number' => '09171234567',
            'address' => 'Sample address',
            'monthly_price' => 1000,
            'billing_start_date' => '2026-09-02',
            'billing_cycle_days' => 31,
            'status' => 'active',
        ]);

        $billing = Billing::create([
            'customer_id' => $customer->id,
            'period_start' => '2026-09-02 00:00:00',
            'period_end' => '2026-10-01',
            'amount_due' => 1000,
            'amount_paid' => 0,
            'balance' => 1000,
            'due_date' => '2026-10-01 00:00:00',
            'status' => 'unpaid',
        ]);

        Livewire::test(BillingIndex::class)
            ->set('payForm', [
                'billing_id' => $billing->id,
                'months' => 2,
                'amount' => '1000',
                'date' => '2026-09-02',
                'method' => 'Cash',
                'reference' => '',
                'remarks' => '',
            ])
            ->call('savePayment');

        $this->assertDatabaseHas('billings', [
            'customer_id' => $customer->id,
            'period_start' => '2026-09-02 00:00:00',
            'due_date' => '2026-10-01 00:00:00',
            'amount_paid' => 1000,
            'status' => 'paid',
        ]);

        $this->assertDatabaseHas('billings', [
            'customer_id' => $customer->id,
            'period_start' => '2026-10-02 00:00:00',
            'due_date' => '2026-11-01 00:00:00',
            'amount_paid' => 1000,
            'status' => 'paid',
        ]);

        $this->assertDatabaseHas('billings', [
            'customer_id' => $customer->id,
            'period_start' => '2026-11-02 00:00:00',
            'due_date' => '2026-12-01 00:00:00',
            'amount_paid' => 0,
            'status' => 'unpaid',
        ]);
    }

    public function test_partial_billing_can_only_accept_remaining_balance_without_creating_another_billing(): void
    {
        $area = Area::factory()->create();
        $customer = Customer::create([
            'name' => 'Partial Customer',
            'area_id' => $area->id,
            'contact_number' => '09171234567',
            'address' => 'Sample address',
            'monthly_price' => 1000,
            'billing_start_date' => '2026-09-02',
            'billing_cycle_days' => 31,
            'status' => 'active',
        ]);

        $billing = Billing::create([
            'customer_id' => $customer->id,
            'period_start' => '2026-09-02',
            'period_end' => '2026-10-01',
            'amount_due' => 1000,
            'amount_paid' => 800,
            'balance' => 200,
            'due_date' => '2026-10-01',
            'status' => 'partial',
        ]);

        Livewire::test(BillingIndex::class)
            ->set('payForm', [
                'billing_id' => $billing->id,
                'months' => 1,
                'amount' => '200',
                'date' => '2026-09-02',
                'method' => 'Cash',
                'reference' => '',
                'remarks' => '',
            ])
            ->call('savePayment');

        $this->assertDatabaseCount('billings', 1);
        $this->assertDatabaseHas('billings', [
            'id' => $billing->id,
            'amount_paid' => 1000,
            'balance' => 0,
            'status' => 'paid',
        ]);
    }

    public function test_payment_cannot_exceed_billing_balance(): void
    {
        $area = Area::factory()->create();
        $customer = Customer::create([
            'name' => 'Balance Customer',
            'area_id' => $area->id,
            'contact_number' => '09171234567',
            'address' => 'Sample address',
            'monthly_price' => 1000,
            'billing_start_date' => '2026-09-02',
            'billing_cycle_days' => 31,
            'status' => 'active',
        ]);

        $billing = Billing::create([
            'customer_id' => $customer->id,
            'period_start' => '2026-09-02',
            'period_end' => '2026-10-01',
            'amount_due' => 1000,
            'amount_paid' => 800,
            'balance' => 200,
            'due_date' => '2026-10-01',
            'status' => 'partial',
        ]);

        Livewire::test(BillingIndex::class)
            ->set('payForm', [
                'billing_id' => $billing->id,
                'months' => 1,
                'amount' => '300',
                'date' => '2026-09-02',
                'method' => 'Cash',
                'reference' => '',
                'remarks' => '',
            ])
            ->call('savePayment')
            ->assertSet('payErrors.amount', 'Payment amount cannot exceed the remaining balance of 200.00.');

        $this->assertDatabaseHas('billings', [
            'id' => $billing->id,
            'amount_paid' => 800,
            'balance' => 200,
            'status' => 'partial',
        ]);
    }
}
