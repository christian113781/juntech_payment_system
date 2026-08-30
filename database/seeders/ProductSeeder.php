<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockBalance;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Electronics' => 'Computer and mobile accessories',
            'Apparel' => 'Daily wear and fashion items',
            'Office' => 'Workspace and productivity essentials',
        ];

        foreach ($categories as $name => $description) {
            Category::firstOrCreate(
                ['name' => $name],
                ['description' => $description],
            );
        }

        $products = [
            [
                'name' => 'Laptop Pro 14',
                'sku' => 'LP-14-001',
                'brand' => 'Acer',
                'unit' => 'Piece',
                'reorder_level' => 6,
                'stock' => 18,
                'category' => 'Electronics',
            ],
            [
                'name' => 'Wireless Mouse',
                'sku' => 'WM-200-007',
                'brand' => 'Logitech',
                'unit' => 'Piece',
                'reorder_level' => 10,
                'stock' => 0,
                'category' => 'Electronics',
            ],
            [
                'name' => 'Classic Polo Shirt',
                'sku' => 'PS-AL-305',
                'brand' => 'Hanes',
                'unit' => 'Piece',
                'reorder_level' => 12,
                'stock' => 32,
                'category' => 'Apparel',
            ],
            [
                'name' => 'Standing Desk',
                'sku' => 'SD-OF-112',
                'brand' => 'FlexiDesk',
                'unit' => 'Piece',
                'reorder_level' => 4,
                'stock' => 3,
                'category' => 'Office',
            ],
            [
                'name' => 'Ergo Chair',
                'sku' => 'EC-OF-210',
                'brand' => 'Herman Miller',
                'unit' => 'Piece',
                'reorder_level' => 8,
                'stock' => 14,
                'category' => 'Office',
            ],
        ];

        foreach ($products as $productData) {
            $category = Category::where('name', $productData['category'])->firstOrFail();

            $product = Product::firstOrCreate(
                ['sku' => $productData['sku']],
                [
                    'name' => $productData['name'],
                    'brand' => $productData['brand'],
                    'unit' => $productData['unit'],
                    'reorder_level' => $productData['reorder_level'],
                    'category_id' => $category->id,
                ],
            );

            StockBalance::updateOrCreate(
                ['product_id' => $product->id],
                ['quantity_on_hand' => $productData['stock']],
            );
        }
    }
}
