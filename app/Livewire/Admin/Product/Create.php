<?php

namespace App\Livewire\Admin\Product;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app', ['title' => 'Create Product'])]
class Create extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $sku = '';
    public ?int $category_id = null;
    public string $brand = '';
    public string $unit = 'Piece';
    public ?int $qty = null;
    public int $reorder_level = 1;

    public $image;

    protected function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'sku'           => ['nullable', 'string', 'max:100', 'unique:products,sku'],
            'category_id'   => ['required', 'exists:categories,id'],
            'brand'         => ['nullable', 'string', 'max:255'],
            'unit'          => ['required', 'string', 'max:50'],
            'qty'           => ['required', 'integer', 'min:0'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'image'         => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function getCategoriesProperty()
    {
        return Category::orderBy('name')->get();
    }

    public function save()
    {
        $validated = $this->validate();

        $product = Product::create([
            'name'          => $validated['name'],
            'sku'           => $validated['sku'],
            'brand'         => $validated['brand'],
            'unit'          => $validated['unit'],
            'reorder_level' => $validated['reorder_level'],
            'category_id'   => $validated['category_id'],
        ]);

        if ($this->image) {
            $product->update(['product_image' => $this->image->store('products', 'public')]);
        }

        $product->stockMovements()->create([
            'user_id'  => auth()->id(),
            'type'     => 'in',
            'quantity' => $validated['qty'],
            'remarks'  => 'Initial stock on product creation',
        ]);

        $product->stockBalance()->create([
            'quantity_on_hand' => $validated['qty'],
        ]);

        session()->flash('success', "\"{$product->name}\" was added to inventory.");

        return $this->redirect(route('products.index'));
    }

    public function render()
    {
        return view('livewire.admin.product.create');
    }
}
