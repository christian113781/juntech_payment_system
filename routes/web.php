<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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

// Vendo Units
use App\Livewire\Admin\VendoUnit\Index as VendoUnitsIndex;
use App\Livewire\Admin\VendoPartner\Index as VendoPartnersIndex;
use App\Livewire\Admin\VendoCollection\Index as VendoCollectionsIndex;



// Inventory Management
use App\Livewire\Admin\Category\Index as CategoriesIndex;
use App\Livewire\Admin\StockMovement\Index as StockMovementsIndex;

// Employee
use App\Livewire\Admin\Employee\Index as EmployeesIndex;
use App\Livewire\Admin\Employee\CashAdvance\Index as EmployeeCashAdvancesIndex;
use App\Livewire\Admin\Expenses\Index as ExpensesIndex;


// Payroll
use App\Livewire\Admin\Payroll\Index as PayrollsIndex;
use App\Http\Controllers\Payroll\EmployeeListController;
use App\Http\Controllers\Payroll\PayrollHistoryController;
use App\Http\Controllers\Payroll\SyncEmployeesController;

// Billing
use App\Livewire\Admin\Billing\Index as BillingsIndex;
use App\Livewire\Admin\Customer\Index as CustomersIndex;
use App\Livewire\Admin\CustomerDetail\Index as CustomerDetailsIndex;

Route::view('/', 'welcome');


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


    Route::get('/vendo-units', VendoUnitsIndex::class)->name('vendo-units.index');
    Route::get('/vendo-partners', VendoPartnersIndex::class)->name('vendo-partners.index');
    Route::get('/vendo-partners/{partner}/collections', VendoCollectionsIndex::class)->name('vendo-collections.index');

    Route::get('/employees', EmployeesIndex::class)->name('employees.index');
    Route::get('/employees/{employee}/cash-advances', EmployeeCashAdvancesIndex::class)->name('employee-cash-advances.index');

    Route::get('/expenses', ExpensesIndex::class)->name('expenses.index');

    Route::get('/payrolls', PayrollsIndex::class)->name('payrolls.index');
    Route::get('/payrolls/employees', EmployeeListController::class)->name('payrolls.employees');
    Route::get('/payrolls/history', [PayrollHistoryController::class, 'index'])->name('payrolls.history.index');
    Route::post('/payrolls/history', [PayrollHistoryController::class, 'store'])->name('payrolls.history.store');
    Route::get('/payrolls/history/{payrollRun}', [PayrollHistoryController::class, 'show'])->name('payrolls.history.show');
    Route::delete('/payrolls/history/{payrollRun}', [PayrollHistoryController::class, 'destroy'])->name('payrolls.history.destroy');
    Route::post('/payrolls/sync-employees', SyncEmployeesController::class)->name('payrolls.sync-employees');


    Route::get('/billings', BillingsIndex::class)->name('billings.index');
    Route::get('/customers', CustomersIndex::class)->name('customers.index');
    Route::get('/customers/{customer}/details', CustomerDetailsIndex::class)->name('customer-details.index');
});

Route::view('/settings', 'temp.placeholder', ['title' => 'Settings'])->name('settings.index');
Route::view('/settings', 'temp.placeholder', ['title' => 'Settings'])->name('settings.index');

Route::post('/logout', function () {
    Auth::guard('web')->logout();
    Session::invalidate();
    Session::regenerateToken();

    return redirect()->route('login');
})->middleware('auth')->name('logout');

require __DIR__.'/auth.php';
