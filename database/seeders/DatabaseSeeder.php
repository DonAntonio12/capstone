<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        // Seed admin user
        Admin::create([
            'name' => 'Admin',
            'email' => 'admin@soilsense.com',
            'password' => bcrypt('admin12345'),
        ]);

        // Seed default soil types if not present
        if (\App\Models\SoilType::count() === 0) {
            \App\Models\SoilType::insert([
                [
                    'name' => 'Loamy Soil',
                    'description' => 'Best for most crops, balanced nutrients',
                    'thresholds' => json_encode(['N' => [0.18, 0.25], 'P' => [0.10, 0.15], 'K' => [0.25, 0.35]]),
                    'best_crops' => json_encode(['Rice', 'Corn', 'Vegetables']),
                    'created_at' => now(), 'updated_at' => now()
                ],
                [
                    'name' => 'Clay Soil',
                    'description' => 'Rich in minerals, good water retention',
                    'thresholds' => json_encode(['N' => [0.08, 0.12], 'P' => [0.18, 0.22], 'K' => [0.35, 0.45]]),
                    'best_crops' => json_encode(['Rice', 'Soybean']),
                    'created_at' => now(), 'updated_at' => now()
                ],
                [
                    'name' => 'Sandy Soil',
                    'description' => 'Well-draining, warms quickly',
                    'thresholds' => json_encode(['N' => [0.03, 0.07], 'P' => [0.06, 0.10], 'K' => [0.12, 0.18]]),
                    'best_crops' => json_encode(['Root crops', 'Watermelon']),
                    'created_at' => now(), 'updated_at' => now()
                ],
            ]);
        }
    }
}
