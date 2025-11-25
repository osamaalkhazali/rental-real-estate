<?php

namespace Database\Seeders;

use App\Models\WaterService;
use Illuminate\Database\Seeder;

class WaterServiceSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $services = [
            [
                'apartment_id' => 1,
                'registration_number' => 'WTR-A101-2025',
                'meter_number' => 'MTR-001234',
                'is_active' => true,
            ],
            [
                'apartment_id' => 3,
                'registration_number' => 'WTR-B201-2025',
                'meter_number' => 'MTR-003456',
                'is_active' => true,
            ],
            [
                'apartment_id' => 5,
                'registration_number' => 'WTR-C301-2025',
                'meter_number' => 'MTR-005678',
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            WaterService::create($service);
        }
    }
}
