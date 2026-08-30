<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\OmadaPartner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OmadaPartner>
 */
class OmadaPartnerFactory extends Factory
{
    protected $model = OmadaPartner::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'contact_number' => fake()->numerify('09#########'),
            'area_id' => Area::factory(),
            'address' => fake()->address(),
        ];
    }
}
