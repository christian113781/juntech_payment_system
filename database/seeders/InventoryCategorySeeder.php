<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class InventoryCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Network Devices',
                'description' => 'Routers, switches, access points, and ONU/ONT devices used for network connectivity and infrastructure.',
            ],
            [
                'name' => 'Network Cables',
                'description' => 'LAN cables, fiber optic cables, and related transmission lines for wired network setups.',
            ],
            [
                'name' => 'Connectors & Adapters',
                'description' => 'RJ45 connectors, fiber connectors, couplers, and adapters for wiring and signal conversion.',
            ],
            [
                'name' => 'Computer Components',
                'description' => 'CPU, RAM, motherboard, GPU, and other internal parts for computer assembly and upgrades.',
            ],
            [
                'name' => 'Storage',
                'description' => 'Hard drives and solid-state storage devices including HDD, SSD, and NVMe solutions.',
            ],
            [
                'name' => 'Power Equipment',
                'description' => 'Power supply units, UPS, AVR, and power adapters used to stabilize and deliver electricity.',
            ],
            [
                'name' => 'Peripherals',
                'description' => 'External input and output devices such as keyboards, mice, webcams, and headsets.',
            ],
            [
                'name' => 'Displays',
                'description' => 'Monitors and projectors used for visual output and presentation setups.',
            ],
            [
                'name' => 'CCTV & Security',
                'description' => 'IP cameras, DVR/NVR equipment, and CCTV accessories for surveillance and monitoring.',
            ],
            [
                'name' => 'Tools & Equipment',
                'description' => 'Crimping tools, cable testers, fiber tools, and other essential technical equipment.',
            ],
            [
                'name' => 'Accessories',
                'description' => 'Useful add-ons such as USB hubs, patch panels, PC cases, and laptop stands.',
            ],
            [
                'name' => 'Consumables',
                'description' => 'Reusable and replaceable supplies like thermal paste, cable ties, electrical tape, and cleaning materials.',
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']],
            );
        }
    }
}
