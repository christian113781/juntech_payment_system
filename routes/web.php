<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Admin\Dashboard\Index as AdminDashboard;
use App\Livewire\Admin\Area\Index as AreasIndex;

// Inventory Management
use App\Livewire\Admin\Product\Index as ProductsIndex;
use App\Livewire\Admin\Product\Create as ProductCreate;
use App\Livewire\Admin\Product\Edit as ProductEdit;


// Omada Cloud
use App\Livewire\Admin\Omada\Index as OmadaIndex;
use App\Livewire\Admin\OmadaBatchCode\Index as OmadaBatchCodesIndex;
use App\Livewire\Admin\OmadaVoucherTool\Index as OmadaVoucherToolIndex;



use App\Livewire\Admin\Category\Index as CategoriesIndex;
use App\Livewire\Admin\StockMovement\Index as StockMovementsIndex;

Route::view('/', 'welcome');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');

    Route::get('/areas', AreasIndex::class)->name('areas.index');

    Route::get('/products', ProductsIndex::class)->name('products.index');
    Route::get('/products/create', ProductCreate::class)->name('products.create');
    Route::get('/products/{product}/edit', ProductEdit::class)->name('products.edit');
    Route::get('/categories', CategoriesIndex::class)->name('categories.index');
    Route::get('/stock-movements', StockMovementsIndex::class)->name('stock-movements.index');



    Route::get('/omada', OmadaIndex::class)->name('omada.index');
    Route::get('/omada/{partner}/batch-codes', OmadaBatchCodesIndex::class)->name('omada-batch-codes.index');
    Route::get('/omada/voucher-tool', OmadaVoucherToolIndex::class)->name('omada-voucher-tool.index');

});

Route::view('/pos', 'temp.placeholder', ['title' => 'Point of Sale'])->name('pos');
Route::view('/purchase-orders', 'temp.placeholder', ['title' => 'Purchase Orders'])->name('purchase-orders.index');
Route::view('/suppliers', 'temp.placeholder', ['title' => 'Suppliers'])->name('suppliers.index');
Route::view('/warehouses', 'temp.placeholder', ['title' => 'Warehouses'])->name('warehouses.index');
Route::view('/profile', 'temp.placeholder', ['title' => 'My Profile'])->name('profile.show');
Route::view('/settings', 'temp.placeholder', ['title' => 'Settings'])->name('settings.index');
Route::view('/profile', 'temp.placeholder', ['title' => 'My Profile'])->name('profile.show');
Route::view('/settings', 'temp.placeholder', ['title' => 'Settings'])->name('settings.index');

Route::post('/logout', function () {
    return redirect('/dashboard');
})->name('logout');

require __DIR__.'/auth.php';
