<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Utilities',
            'Internet & Communication',
            'Fuel & Transportation',
            'Inventory / Supplies',
            'Equipment',
            'Maintenance & Repair',
            'Rent',
            'Salaries & Wages',
            'Government & Permits',
            'Bank & Payment Fees',
            'Marketing & Advertising',
            'Meals & Representation',
            'Other',
        ];

        foreach ($categories as $category) {
            ExpenseCategory::firstOrCreate([
                'name' => $category,
            ]);
        }
    }
}
