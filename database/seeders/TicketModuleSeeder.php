<?php

namespace Database\Seeders;

use App\Models\TicketModule;
use Illuminate\Database\Seeder;

class TicketModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            ['name' => 'Penjualan', 'slug' => 'penjualan'],
            ['name' => 'Stok', 'slug' => 'stok'],
            ['name' => 'Resep Dokter', 'slug' => 'resep-dokter'],
            ['name' => 'Laporan', 'slug' => 'laporan'],
            ['name' => 'Retur Obat', 'slug' => 'retur-obat'],
            ['name' => 'Lainnya', 'slug' => 'lainnya'],
        ];

        foreach ($modules as $module) {
            TicketModule::firstOrCreate(['slug' => $module['slug']], $module);
        }
    }
}
