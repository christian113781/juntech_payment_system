<?php

namespace App\Livewire\Admin\Product;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app', ['title' => 'Edit Product'])]
class Edit extends Component
{
    use WithFileUploads;

    public Product $product;

    public string $name = '';
    public string $sku = '';
    public ?int $category_id = null;
    public string $brand = '';
    public string $unit = 'Piece';
    public int $qty = 0;
    public int $reorder_level = 1;

    public $image;

    public function mount(Product $product): void
    {
        $this->product = $product;
        $this->name = $product->name;
        $this->sku = $product->sku ?? '';
        $this->category_id = $product->category_id;
        $this->brand = $product->brand ?? '';
        $this->unit = $product->unit ?? 'Piece';
        $this->qty = (int) $product->quantity_on_hand;
        $this->reorder_level = (int) $product->reorder_level;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:100', 'unique:products,sku,' . $this->product->id],
            'category_id' => ['required', 'exists:categories,id'],
            'brand' => ['nullable', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:50'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function getCategoriesProperty()
    {
        return Category::orderBy('name')->get();
    }

    protected function deleteStoredImageIfExists(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function save()
    {
        $validated = $this->validate();

        $imagePath = $this->product->product_image;

        if ($this->image) {
            $this->deleteStoredImageIfExists($this->product->product_image);
            $imagePath = $this->image->store('products', 'public');
        }

        $this->product->update([
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'brand' => $validated['brand'],
            'unit' => $validated['unit'],
            'reorder_level' => $validated['reorder_level'],
            'category_id' => $validated['category_id'],
            'product_image' => $imagePath,
        ]);

        session()->flash('success', "\"{$this->product->name}\" was updated.");

        return $this->redirect(route('products.index'));
    }

    public function render()
    {
        return view('livewire.admin.product.edit');
    }
}
