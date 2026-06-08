<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DummyUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. AKTOR ADMIN (Tim IT / Mas Hendri)
        User::updateOrCreate(
            ['email_work' => 'it.admin@amarin.com'],
            [
                'employee_code' => 'DUMMY-ADM', // 👈 WAJIB DIISI
                'company_id'    => 1,           // 👈 WAJIB DIISI (ID PT ASM)
                'full_name'     => 'IT Administrator',
                'password'      => Hash::make('password123'),
                'role'          => 'admin'
            ]
        );

        // 2. AKTOR EMPLOYEE (Karyawan Darat / Office)
        User::updateOrCreate(
            ['email_work' => 'staff.office@amarin.com'],
            [
                'employee_code' => 'DUMMY-EMP', 
                'company_id'    => 1,           
                'full_name'     => 'Budi (Karyawan Darat)',
                'password'      => 'password123',
                'role'          => 'employee'
            ]
        );

        // 3. AKTOR VESSEL (Kapal)
        User::updateOrCreate(
            ['email_work' => 'kapal.katarina@amarin.com'],
            [
                'employee_code' => 'DUMMY-VSL', 
                'company_id'    => 1,           
                'full_name'     => 'Kapal MV. Amarin Katarina',
                'password'      => 'password123',
                'role'          => 'vessel'
            ]
        );
    }
}
