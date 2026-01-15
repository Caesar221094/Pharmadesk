<?php

namespace Database\Seeders;

use App\Models\Pharmacy;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketModule;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pharmacy = Pharmacy::first();
        $expert = User::whereHas('role', fn ($q) => $q->where('slug', 'team_expert'))->first();
        $tech = User::whereHas('role', fn ($q) => $q->where('slug', 'tech'))->first();
        $module = TicketModule::first();
        $category = TicketCategory::first();

        if (! $pharmacy || ! $expert || ! $tech || ! $module || ! $category) {
            return;
        }

        $now = Carbon::now();

        // Open
        Ticket::firstOrCreate([
            'title' => 'Contoh tiket OPEN',
        ], [
            'pharmacy_id' => $pharmacy->id,
            'expert_id' => $expert->id,
            'tech_id' => $tech->id,
            'module_id' => $module->id,
            'category_id' => $category->id,
            'description' => 'Tiket dummy dengan status OPEN.',
            'app_version' => '1.0.0',
            'priority' => 'medium',
            'status' => 'open',
            'source' => 'team_expert',
            'opened_at' => $now,
        ]);

        // In Progress
        Ticket::firstOrCreate([
            'title' => 'Contoh tiket IN PROGRESS',
        ], [
            'pharmacy_id' => $pharmacy->id,
            'expert_id' => $expert->id,
            'tech_id' => $tech->id,
            'module_id' => $module->id,
            'category_id' => $category->id,
            'description' => 'Tiket dummy dengan status IN PROGRESS.',
            'app_version' => '1.0.0',
            'priority' => 'high',
            'status' => 'in_progress',
            'source' => 'team_expert',
            'opened_at' => $now->copy()->subDay(),
        ]);

        // For Review
        Ticket::firstOrCreate([
            'title' => 'Contoh tiket FOR REVIEW',
        ], [
            'pharmacy_id' => $pharmacy->id,
            'expert_id' => $expert->id,
            'tech_id' => $tech->id,
            'module_id' => $module->id,
            'category_id' => $category->id,
            'description' => 'Tiket dummy dengan status FOR REVIEW.',
            'app_version' => '1.0.0',
            'priority' => 'low',
            'status' => 'for_review',
            'source' => 'team_expert',
            'opened_at' => $now->copy()->subDays(2),
        ]);

        // Closed
        Ticket::firstOrCreate([
            'title' => 'Contoh tiket CLOSED',
        ], [
            'pharmacy_id' => $pharmacy->id,
            'expert_id' => $expert->id,
            'tech_id' => $tech->id,
            'module_id' => $module->id,
            'category_id' => $category->id,
            'description' => 'Tiket dummy dengan status CLOSED.',
            'app_version' => '1.0.0',
            'priority' => 'medium',
            'status' => 'closed',
            'source' => 'team_expert',
            'opened_at' => $now->copy()->subDays(3),
            'closed_at' => $now->copy()->subDay(),
        ]);
    }
}
