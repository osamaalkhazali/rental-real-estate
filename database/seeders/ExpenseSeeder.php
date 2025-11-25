<?php

namespace Database\Seeders;

use App\Models\Expense;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Seed the application's expenses.
     */
    public function run(): void
    {
        $expenses = [
            [
                'apartment_id' => 1,
                'lease_id' => 1,
                'type' => 'maintenance',
                'title' => 'HVAC Tune-Up',
                'description' => 'Seasonal HVAC inspection and filter replacement.',
                'expense_date' => '2025-02-10',
                'amount' => 250.00,
                'vendor_name' => 'CoolAir Services',
                'receipt_file' => 'expenses/receipts/hvac_tuneup.pdf',
                'attachments' => ['photos/hvac_before.jpg', 'photos/hvac_after.jpg'],
                'notes' => 'Performed before summer heat wave.',
            ],
            [
                'apartment_id' => 3,
                'lease_id' => 2,
                'type' => 'lighting',
                'title' => 'LED Fixture Replacement',
                'description' => 'Upgraded hallway bulbs to LED fixtures for energy savings.',
                'expense_date' => '2025-04-05',
                'amount' => 120.50,
                'vendor_name' => 'BrightSpark Electrical',
                'receipt_file' => null,
                'attachments' => ['photos/led_installation.jpg'],
                'notes' => 'Warranty: 2 years.',
            ],
            [
                'apartment_id' => 4,
                'lease_id' => null,
                'type' => 'painting',
                'title' => 'Unit Refresh',
                'description' => 'Full repaint before onboarding new tenant.',
                'expense_date' => '2025-01-18',
                'amount' => 680.00,
                'vendor_name' => 'ColorCo Painters',
                'receipt_file' => 'expenses/receipts/painting_invoice.pdf',
                'attachments' => null,
                'notes' => 'Neutral palette requested by owner.',
            ],
        ];

        foreach ($expenses as $expense) {
            Expense::create($expense);
        }
    }
}
