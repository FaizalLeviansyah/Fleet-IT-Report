<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VesselSeeder extends Seeder
{
    public function run(): void
    {
        $vessels = [
            // Fleet Mas Farhan
            ['company_name' => 'PT VIP', 'vessel_name' => 'Opus Point', 'pic_name' => 'Farhan'],
            ['company_name' => 'PT BPL', 'vessel_name' => 'Eternal Oil 1', 'pic_name' => 'Farhan'],
            ['company_name' => 'PT Caraka', 'vessel_name' => 'Queen Protocol', 'pic_name' => 'Farhan'],
            ['company_name' => 'PT Atamimi', 'vessel_name' => 'John Caine 2', 'pic_name' => 'Farhan'],
            ['company_name' => 'PT Aryana', 'vessel_name' => 'Spectrum Arctic', 'pic_name' => 'Farhan'],

            // Fleet Mas Levi
            ['company_name' => 'PT ASL', 'vessel_name' => 'SOVIANA', 'pic_name' => 'Levi'],
            ['company_name' => 'PT Caraka', 'vessel_name' => 'Queen Century', 'pic_name' => 'Levi'],
            ['company_name' => 'PT Caraka', 'vessel_name' => 'Queen Majesty', 'pic_name' => 'Levi'],
            ['company_name' => 'PT Atamimi', 'vessel_name' => 'Geraldine', 'pic_name' => 'Levi'],
            ['company_name' => 'PT BPL', 'vessel_name' => 'Eternal Oil 2', 'pic_name' => 'Levi'],
        ];

        DB::table('vessels')->insert($vessels);
    }
}
