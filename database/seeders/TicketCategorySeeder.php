<?php

namespace Database\Seeders;

use App\Models\TicketCategory;
use Illuminate\Database\Seeder;

class TicketCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Bug Sistem', 'slug' => 'bug-sistem'],
            ['name' => 'Improvement Fitur', 'slug' => 'improvement-fitur'],
            ['name' => 'Error Data', 'slug' => 'error-data'],
            ['name' => 'Operasional Apotek', 'slug' => 'operasional-apotek'],
            ['name' => 'Lainnya', 'slug' => 'lainnya'],
        ];

        foreach ($categories as $category) {
            TicketCategory::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
