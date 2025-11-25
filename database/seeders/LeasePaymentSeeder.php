<?php

namespace Database\Seeders;

use App\Models\LeasePayment;
use Illuminate\Database\Seeder;

class LeasePaymentSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $payments = [
            // Lease 1 payments (John Smith)
            [
                'lease_id' => 1,
                'month' => 'September 2025',
                'due_date' => '2025-09-01',
                'amount_due' => 1200.00,
                'amount_paid' => 1200.00,
                'payment_date' => '2025-08-30',
                'status' => 'paid',
                'notes' => 'Paid via bank transfer',
            ],
            [
                'lease_id' => 1,
                'month' => 'October 2025',
                'due_date' => '2025-10-01',
                'amount_due' => 1200.00,
                'amount_paid' => 1200.00,
                'payment_date' => '2025-09-28',
                'status' => 'paid',
            ],
            [
                'lease_id' => 1,
                'month' => 'November 2025',
                'due_date' => '2025-11-01',
                'amount_due' => 1200.00,
                'amount_paid' => null,
                'payment_date' => null,
                'status' => 'unpaid',
            ],
            // Lease 2 payments (Sarah Johnson)
            [
                'lease_id' => 2,
                'month' => 'September 2025',
                'due_date' => '2025-09-01',
                'amount_due' => 1800.00,
                'amount_paid' => 1800.00,
                'payment_date' => '2025-09-01',
                'status' => 'paid',
            ],
            [
                'lease_id' => 2,
                'month' => 'October 2025',
                'due_date' => '2025-10-01',
                'amount_due' => 1800.00,
                'amount_paid' => 1000.00,
                'payment_date' => '2025-10-02',
                'status' => 'partial',
                'notes' => 'Partial payment, remaining balance to be paid',
            ],
            [
                'lease_id' => 2,
                'month' => 'November 2025',
                'due_date' => '2025-11-01',
                'amount_due' => 1800.00,
                'amount_paid' => null,
                'payment_date' => null,
                'status' => 'unpaid',
            ],
            // Lease 3 payments (Michael Brown)
            [
                'lease_id' => 3,
                'month' => 'September 2025',
                'due_date' => '2025-09-01',
                'amount_due' => 1400.00,
                'amount_paid' => 1400.00,
                'payment_date' => '2025-09-05',
                'status' => 'late',
                'notes' => 'Paid 4 days late',
            ],
            [
                'lease_id' => 3,
                'month' => 'October 2025',
                'due_date' => '2025-10-01',
                'amount_due' => 1400.00,
                'amount_paid' => 1400.00,
                'payment_date' => '2025-10-01',
                'status' => 'paid',
            ],
            [
                'lease_id' => 3,
                'month' => 'November 2025',
                'due_date' => '2025-11-01',
                'amount_due' => 1400.00,
                'amount_paid' => null,
                'payment_date' => null,
                'status' => 'unpaid',
            ],
        ];

        foreach ($payments as $payment) {
            LeasePayment::create($payment);
        }
    }
}
