<?php

namespace Database\Seeders;

use App\Models\WaterReading;
use Illuminate\Database\Seeder;

class WaterReadingSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $readings = [
            // Water Service 1 readings
            [
                'water_service_id' => 1,
                'reading_date' => '2025-09-01',
                'reading_value' => 1250.00,
                'cost' => 125.00,
                'is_paid' => true,
            ],
            [
                'water_service_id' => 1,
                'reading_date' => '2025-10-01',
                'reading_value' => 1295.00,
                'cost' => 112.50,
                'is_paid' => false,
            ],
            // Water Service 2 readings
            [
                'water_service_id' => 2,
                'reading_date' => '2025-09-01',
                'reading_value' => 850.00,
                'cost' => 175.00,
                'is_paid' => true,
            ],
            [
                'water_service_id' => 2,
                'reading_date' => '2025-10-01',
                'reading_value' => 920.00,
                'cost' => 175.00,
                'is_paid' => false,
            ],
        ];

        foreach ($readings as $reading) {
            WaterReading::create($reading);
        }
    }
}
