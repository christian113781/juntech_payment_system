<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_can_be_saved_with_stock_data(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Electronics',
            'description' => 'Test category',
        ]);

        auth()->login($user);

        Livewire::test(\App\Livewire\Admin\Product\Create::class)
            ->set('name', 'Laptop Pro')
            ->set('sku', 'LP-001')
            ->set('category_id', $category->id)
            ->set('brand', 'Dell')
            ->set('unit', 'Piece')
            ->set('qty', 12)
            ->set('reorder_level', 4)
            ->call('save');

        $this->assertDatabaseHas('products', [
            'name' => 'Laptop Pro',
            'sku' => 'LP-001',
            'category_id' => $category->id,
        ]);

        $product = Product::where('sku', 'LP-001')->first();

        $this->assertNotNull($product);
        $this->assertDatabaseHas('stock_balances', [
            'product_id' => $product->id,
            'quantity_on_hand' => 12,
        ]);
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => 12,
        ]);
    }

    public function test_stock_out_cannot_exceed_current_quantity_and_adjustment_sets_new_value(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Electronics',
            'description' => 'Test category',
        ]);

        auth()->login($user);

        $product = Product::create([
            'name' => 'Laptop Pro',
            'sku' => 'LP-002',
            'category_id' => $category->id,
            'brand' => 'Dell',
            'unit' => 'Piece',
            'reorder_level' => 4,
        ]);

        $product->stockBalance()->create(['quantity_on_hand' => 10]);

        Livewire::test(\App\Livewire\Admin\Product\Index::class)
            ->set('stockMovementProductId', $product->id)
            ->set('stockMovementType', 'out')
            ->set('stockMovementQuantity', 11)
            ->call('saveStockMovement');

        $this->assertDatabaseHas('stock_balances', [
            'product_id' => $product->id,
            'quantity_on_hand' => 10,
        ]);

        Livewire::test(\App\Livewire\Admin\Product\Index::class)
            ->set('stockMovementProductId', $product->id)
            ->set('stockMovementType', 'adjustment')
            ->set('stockMovementQuantity', 25)
            ->call('saveStockMovement');

        $this->assertDatabaseHas('stock_balances', [
            'product_id' => $product->id,
            'quantity_on_hand' => 25,
        ]);
    }
}
