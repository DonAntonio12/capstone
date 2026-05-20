<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SoilType;

class SoilTypeSeeder extends Seeder
{
    public function run()
    {
        if (SoilType::count() === 0) {
            SoilType::insert([
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