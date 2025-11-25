<?php

namespace Database\Seeders;

use App\Models\ElectricService;
use Illuminate\Database\Seeder;

class ElectricServiceSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $services = [
            [
                'apartment_id' => 1,
                'registration_number' => 'ELC-A101-2025',
                'meter_number' => 'EMT-001234',
                'is_active' => true,
            ],
            [
                'apartment_id' => 3,
                'registration_number' => 'ELC-B201-2025',
                'meter_number' => 'EMT-003456',
                'is_active' => true,
            ],
            [
                'apartment_id' => 5,
                'registration_number' => 'ELC-C301-2025',
                'meter_number' => 'EMT-005678',
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            ElectricService::create($service);
        }
    }
}
