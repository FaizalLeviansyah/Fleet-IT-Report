<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Computers', 'icon' => 'fa-desktop'],
            ['name' => 'Monitors', 'icon' => 'fa-tv'],
            ['name' => 'Network Devices', 'icon' => 'fa-network-wired'],
            ['name' => 'Printers', 'icon' => 'fa-print'],
            ['name' => 'Devices', 'icon' => 'fa-microchip'],
            ['name' => 'Peripherals', 'icon' => 'fa-keyboard'],
        ];

        foreach($categories as $cat) {
            \App\Models\AssetCategory::updateOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
