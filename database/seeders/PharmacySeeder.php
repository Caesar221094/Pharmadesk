<?php

namespace Database\Seeders;

use App\Models\Pharmacy;
use Illuminate\Database\Seeder;

class PharmacySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pharmacy::firstOrCreate([
            'name' => 'Apotek Contoh Sehat',
        ], [
            'pic_name' => 'PIC Apotek',
            'phone' => '021000000',
            'whatsapp' => '081234567890',
            'address' => 'Jl. Contoh No. 1, Jakarta',
            'city' => 'Jakarta',
        ]);
    }
}
