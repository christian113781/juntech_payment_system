<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            ['code' => 'TGM', 'name' => 'Tagum City'],
            ['code' => 'NCR', 'name' => 'New Corella'],
            ['code' => 'MCM', 'name' => 'Macgum'],
            ['code' => 'TPM', 'name' => 'Tagum Public Market'],
            ['code' => 'SWN', 'name' => 'Swawon'],
            ['code' => 'LMB', 'name' => 'Limban'],
            ['code' => 'MSY', 'name' => 'Mesaoy'],
            ['code' => 'CMB', 'name' => 'Cuambogan'],
        ];

        foreach ($areas as $area) {
            Area::firstOrCreate(
                ['code' => $area['code']],
                ['name' => $area['name']],
            );
        }
    }
}
