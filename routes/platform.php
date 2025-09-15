<?php
declare(strict_types=1);

use App\Models\Customer;
use App\Orchid\Screens\CompanyEditScreen;
use App\Orchid\Screens\CompanyListScreen;
use App\Orchid\Screens\CustomerEditScreen;
use App\Orchid\Screens\CustomerListScreen;
use App\Orchid\Screens\DashboardScreen;
use App\Orchid\Screens\InvoicePurchaseEditScreen;
use App\Orchid\Screens\InvoicePurchaseListScreen;
use App\Orchid\Screens\InvoiceSaleEditScreen;
use App\Orchid\Screens\InvoiceSaleListScreen;
use App\Orchid\Screens\ProductEditScreen;
use App\Orchid\Screens\ProductListScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\StockEditScreen;
use App\Orchid\Screens\StockListScreen;
use App\Orchid\Screens\StockMovementsEditScreen;
use App\Orchid\Screens\StockMovementsListScreen;
use App\Orchid\Screens\SupplierEditScreen;
use App\Orchid\Screens\SupplierListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use App\Orchid\Screens\WharehouseEditScreen;
use App\Orchid\Screens\WharehouseListScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', DashboardScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));

// Rutas para compay edicion y listado
Route::screen('company/{company?}', CompanyEditScreen::class)
    ->name('platform.company.edit');
Route::screen('companies', CompanyListScreen::class)
    ->name('platform.companies.list');

//Rutas para customer edicion y listado
Route::screen('customer/{customer?}', CustomerEditScreen::class)
    ->name('platform.customer.edit');
Route::screen('customers', CustomerListScreen::class)
    ->name('platform.customer.list');

//Rutas para Invoice Sales edicion y listado
Route::screen('invoice-sale/{invoiceSale?}', InvoiceSaleEditScreen::class)
    ->name('platform.invoice.sale.edit');
Route::screen('invoice-sales', InvoiceSaleListScreen::class)
    ->name('platform.invoice.sale.list');

//Rlutes para Invoice Purchase
Route::screen('invoice-purchase/{invoice-purchase?}', InvoicePurchaseEditScreen::class)
    ->name('platform.screens.invoice-purchase');
Route::screen('invoice-purchases', InvoicePurchaseListScreen::class)
    ->name('platform.invoicepurchases.list');

//Route para Product
Route::screen('product/{product?}', ProductEditScreen::class)
    ->name('platform.product.edit');
Route::screen('products', ProductListScreen::class)
    ->name('platform.products.list');

//Route para Stock
Route::screen('stock/{stock}', StockEditScreen::class)
    ->name('platform.stock.edit');
Route::screen('stocks', StockListScreen::class)
    ->name('platform.stocks.list');

//Route para Movements Stocks
Route::screen('movement-stock/{movement-stock?}', StockMovementsEditScreen::class)
    ->name('platform.movement-stock.edit');
Route::screen('movement-stocks', StockMovementsListScreen::class)
    ->name('platform.movement-stocks.list');

//Routes para Supplier
Route::screen('supplier/{supplier?}', SupplierEditScreen::class)
    ->name('platform.supplier.edit');
Route::screen('suppliers', SupplierListScreen::class)
    ->name('platform.supplier.list');

//Route para Wharehouse
Route::screen('wharehouse/{wharehouse?}', WharehouseEditScreen::class)
    ->name('platform.wharehouse.edit');
Route::screen('wharehouses', WharehouseListScreen::class)
    ->name('platform.wharehouses.list');

    // API para obtener el precio de un producto por ID (accesible desde Orchid)
use App\Models\Product;
use Illuminate\Http\Request;

Route::get('/api/product-price/{id}', function ($id) {
    $product = Product::find($id);
    return response()->json([
        'price' => $product ? $product->price : 0
    ]);
});

Route::get('/api/customer-by-nit/{nit}', function($nit) {
    return Customer::where('nit', $nit)->first();
})->name('api.customer-by-nit');
