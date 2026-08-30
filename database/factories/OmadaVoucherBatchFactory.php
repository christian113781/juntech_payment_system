<?php

namespace Database\Factories;

use App\Models\OmadaPartner;
use App\Models\OmadaVoucherBatch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OmadaVoucherBatch>
 */
class OmadaVoucherBatchFactory extends Factory
{
    protected $model = OmadaVoucherBatch::class;

    public function definition(): array
    {
        return [
            'partner_id' => OmadaPartner::factory(),
            'batch_code' => 'OM-' . now()->format('Y-m') . '-' . fake()->numerify('######'),
            'type' => fake()->randomElement(['FREE', 'SALE', 'SALE + FREE']),
            'requested_qty' => fake()->numberBetween(50, 500),
            'bonus_qty' => fake()->numberBetween(0, 20),
            'price_per_voucher' => fake()->randomFloat(2, 5, 50),
            'generated_date' => now()->toDateString(),
            'status' => 'pending',
            'remarks' => fake()->sentence(),
        ];
    }
}
