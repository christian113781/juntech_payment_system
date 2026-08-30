<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_product_index_respects_per_page_setting(): void
    {
        $category = Category::create([
            'name' => 'Electronics',
            'description' => 'Test category',
        ]);

        foreach (range(1, 5) as $index) {
            Product::create([
                'name' => "Test Product {$index}",
                'sku' => "SKU-{$index}",
                'brand' => 'Test Brand',
                'unit' => 'Piece',
                'reorder_level' => 5,
                'category_id' => $category->id,
            ]);
        }

        $component = Livewire::test(\App\Livewire\Admin\Product\Index::class)
            ->set('perPage', 1)
            ->call('loadProducts');

        $this->assertCount(1, $component->get('products'));
    }
}
