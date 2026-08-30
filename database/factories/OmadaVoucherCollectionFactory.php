<?php

namespace Database\Factories;

use App\Models\OmadaVoucherBatch;
use App\Models\OmadaVoucherCollection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OmadaVoucherCollection>
 */
class OmadaVoucherCollectionFactory extends Factory
{
    protected $model = OmadaVoucherCollection::class;

    public function definition(): array
    {
        return [
            'batch_id' => OmadaVoucherBatch::factory(),
            'collection_date' => now()->toDateString(),
            'total_amount' => fake()->randomFloat(2, 100, 10000),
            'remarks' => fake()->sentence(),
        ];
    }
}
