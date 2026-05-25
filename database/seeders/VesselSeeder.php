<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VesselSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $vessels = [
            // Fleet Mas Farhan
            ['company_name' => 'PT VIP', 'vessel_name' => 'MT. Opus Point', 'pic_name' => 'Farhan'],
            ['company_name' => 'PT BPL', 'vessel_name' => 'MT. Eternal Oil I', 'pic_name' => 'Farhan'],
            ['company_name' => 'PT Caraka', 'vessel_name' => 'MT. Queen Protocol', 'pic_name' => 'Farhan'],
            ['company_name' => 'PT Atamimi', 'vessel_name' => 'MT. John Caine 2', 'pic_name' => 'Farhan'],
            ['company_name' => 'PT Aryana', 'vessel_name' => 'MT. Spectrum Arctic', 'pic_name' => 'Farhan'],

            // Fleet Mas Levi
            ['company_name' => 'PT ASL', 'vessel_name' => 'MT. Soviana', 'pic_name' => 'Levi'],
            ['company_name' => 'PT BPL', 'vessel_name' => 'MT. Eternal Oil II', 'pic_name' => 'Levi'],
            ['company_name' => 'PT Caraka', 'vessel_name' => 'MT. Queen Majesty', 'pic_name' => 'Levi'],
            ['company_name' => 'PT Caraka', 'vessel_name' => 'MT. Queen Century', 'pic_name' => 'Levi'],
            ['company_name' => 'PT Atamimi', 'vessel_name' => 'MT. Geraldine', 'pic_name' => 'Levi'],

            // Tambahan untuk melengkapi 12 Vessel (Sesuaikan jika namanya berbeda)
            ['company_name' => 'PT Atamimi', 'vessel_name' => 'MT. John Caine II', 'pic_name' => 'Farhan'],
            ['company_name' => 'PT VIP', 'vessel_name' => 'MT. Ocean Hero', 'pic_name' => 'Levi'],
        ];

        // Tambahkan timestamp
        foreach ($vessels as &$vessel) {
            $vessel['created_at'] = $now;
            $vessel['updated_at'] = $now;
        }

        DB::table('vessels')->insert($vessels);
    }
}
