<div class="space-y-6">

    <div>
        <div class="flex items-center gap-2 text-xs text-gray-400 mb-1">
            <a href="{{ route('products.index') }}" wire:navigate class="hover:text-brand-600">Products</a>
            <span>/</span>
            <span class="text-gray-500">Edit Product</span>
        </div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Edit Product</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Update the product details and stock information</p>
    </div>

    <form wire:submit="save" class="space-y-6">
        <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-card p-5 space-y-4">
            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Basic Info</h2>

            <div>
                <label for="prod-name" class="block text-xs font-semibold text-gray-600 mb-1.5">
                    Product Name <span class="text-red-500" aria-hidden="true">*</span>
                </label>
                <input id="prod-name" type="text" wire:model.blur="name" class="field @error('name') error @enderror" placeholder="e.g. Samsung 55″ QLED TV" autocomplete="off">
                @error('name')
                    <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="prod-sku" class="block text-xs font-semibold text-gray-600 mb-1.5">
                        SKU <span class="text-xs font-normal text-gray-400">(optional)</span>
                    </label>
                    <input id="prod-sku" type="text" wire:model.blur="sku" class="field @error('sku') error @enderror" placeholder="e.g. TV-SAM-001">
                    @error('sku')
                        <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="prod-category" class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Category <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <select id="prod-category" wire:model="category_id" class="field @error('category_id') error @enderror">
                        <option value="">Select category</option>
                        @foreach ($this->categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="prod-brand" class="block text-xs font-semibold text-gray-600 mb-1.5">Brand</label>
                <input id="prod-brand" type="text" wire:model.blur="brand" class="field" placeholder="e.g. Samsung">
            </div>
        </section>

        <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-card p-5 space-y-4">
            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Stock Settings</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="prod-reorder" class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Reorder Level <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <input id="prod-reorder" type="number" min="0" wire:model.blur="reorder_level" class="field @error('reorder_level') error @enderror" placeholder="1">
                    @error('reorder_level')
                        <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="prod-unit" class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Unit <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <select id="prod-unit" wire:model="unit" class="field">
                        <option>Piece</option>
                        <option>Box</option>
                        <option>Kilogram</option>
                        <option>Liter</option>
                        <option>Pack</option>
                    </select>
                </div>
            </div>
        </section>

        <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-card p-5 space-y-4">
            <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                Product Image <span class="text-xs font-normal text-gray-400">(optional)</span>
            </h2>

            <label for="prod-image" class="flex flex-col items-center justify-center gap-2 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-600 p-6 text-center cursor-pointer hover:border-brand-300 transition-colors">
                @if ($image)
                    <img src="{{ $image->temporaryUrl() }}" alt="" class="w-24 h-24 object-cover rounded-xl">
                @elseif ($product->product_image)
                    <img src="{{ asset('storage/' . $product->product_image) }}" alt="" class="w-24 h-24 object-cover rounded-xl">
                @else
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-xs text-gray-400">Click to upload an image</span>
                @endif
                <input id="prod-image" type="file" wire:model="image" class="sr-only" accept="image/*">
            </label>
            @error('image')
                <p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>
            @enderror
            <div wire:loading wire:target="image" class="text-xs text-gray-400">Uploading…</div>
        </section>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('products.index') }}" wire:navigate class="px-5 py-2.5 border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-semibold rounded-xl transition-colors min-h-[44px] flex items-center">
                Cancel
            </a>
            <button type="submit" wire:loading.attr="disabled" wire:target="save" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 disabled:opacity-60 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm min-h-[44px]">
                <svg wire:loading wire:target="save" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <span wire:loading.remove wire:target="save">Update Product</span>
                <span wire:loading wire:target="save">Updating…</span>
            </button>
        </div>
    </form>
</div>
