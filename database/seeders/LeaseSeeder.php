<?php

namespace Database\Seeders;

use App\Models\Lease;
use Illuminate\Database\Seeder;

class LeaseSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $leases = [
            [
                'apartment_id' => 1,
                'tenant_name' => 'John Smith',
                'tenant_phone' => '+1-555-0101',
                'tenant_email' => 'john.smith@example.com',
                'tenant_national_id' => 'ID123456789',
                'start_date' => '2025-01-01',
                'end_date' => '2025-12-31',
                'rent_amount' => 1200.00,
                'payment_status' => 'paid',
                'deposit_amount' => 2400.00,
                'documents' => ['contract' => 'leases/contracts/john-smith.pdf'],
                'notes' => 'Renewed lease from previous year',
            ],
            [
                'apartment_id' => 3,
                'tenant_name' => 'Sarah Johnson',
                'tenant_phone' => '+1-555-0202',
                'tenant_email' => 'sarah.j@example.com',
                'tenant_national_id' => 'ID987654321',
                'start_date' => '2025-03-01',
                'end_date' => '2026-02-28',
                'rent_amount' => 1800.00,
                'payment_status' => 'unpaid',
                'deposit_amount' => 3600.00,
                'documents' => ['contract' => 'leases/contracts/sarah-johnson.pdf'],
                'notes' => 'Family with 2 children',
            ],
            [
                'apartment_id' => 5,
                'tenant_name' => 'Michael Brown',
                'tenant_phone' => '+1-555-0303',
                'tenant_email' => 'mbrown@example.com',
                'tenant_national_id' => 'ID456789123',
                'start_date' => '2025-06-01',
                'end_date' => '2026-05-31',
                'rent_amount' => 1400.00,
                'payment_status' => 'partial',
                'deposit_amount' => 2800.00,
                'documents' => ['contract' => 'leases/contracts/michael-brown.pdf'],
                'notes' => 'Works from home, needs good internet',
            ],
        ];

        foreach ($leases as $lease) {
            Lease::create($lease);
        }
    }
}
