<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();
        $expertRole = Role::where('slug', 'team_expert')->first();
        $techRole = Role::where('slug', 'tech')->first();

        if ($adminRole) {
            User::firstOrCreate([
                'email' => 'admin@pharmadesk.test',
            ], [
                'name' => 'Admin Pharmadesk',
                'role_id' => $adminRole->id,
                'password' => Hash::make('password123'),
            ]);
        }

        if ($expertRole) {
            User::firstOrCreate([
                'email' => 'expert@pharmadesk.test',
            ], [
                'name' => 'Team Expert',
                'role_id' => $expertRole->id,
                'password' => Hash::make('password123'),
            ]);
        }

        if ($techRole) {
            User::firstOrCreate([
                'email' => 'tech@pharmadesk.test',
            ], [
                'name' => 'Tim Tech',
                'role_id' => $techRole->id,
                'password' => Hash::make('password123'),
            ]);
        }
    }
}
