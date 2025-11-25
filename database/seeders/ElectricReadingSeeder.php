<?php

namespace Database\Seeders;

use App\Models\ElectricReading;
use Illuminate\Database\Seeder;

class ElectricReadingSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $readings = [
            // Electric Service 1 readings
            [
                'electric_service_id' => 1,
                'reading_date' => '2025-09-01',
                'reading_value' => 3500.00,
                'cost' => 45.00,
                'is_paid' => true,
            ],
            [
                'electric_service_id' => 1,
                'reading_date' => '2025-10-01',
                'reading_value' => 3780.00,
                'cost' => 42.00,
                'is_paid' => false,
            ],
            // Electric Service 2 readings
            [
                'electric_service_id' => 2,
                'reading_date' => '2025-09-01',
                'reading_value' => 5200.00,
                'cost' => 60.00,
                'is_paid' => true,
            ],
            [
                'electric_service_id' => 2,
                'reading_date' => '2025-10-01',
                'reading_value' => 5650.00,
                'cost' => 67.50,
                'is_paid' => false,
            ],
        ];

        foreach ($readings as $reading) {
            ElectricReading::create($reading);
        }
    }
}
