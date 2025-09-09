<?php

declare(strict_types=1);

use App\Orchid\Screens\CompanyEditScreen;
use App\Orchid\Screens\CompanyListScreen;
use App\Orchid\Screens\CustomerEditScreen;
use App\Orchid\Screens\CustomerListScreen;
use App\Orchid\Screens\Examples\ExampleActionsScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleGridScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\InvoiceSaleEditScreen;
use App\Orchid\Screens\InvoiceSaleListScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
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
Route::screen('/main', PlatformScreen::class)
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
Route::screen('company', CompanyEditScreen::class, 'platform.screens.company');
Route::screen('companies', CompanyListScreen::class, 'platform.screens.companies');
//Rutas para customer edicion y listado
Route::screen('customer', CustomerEditScreen::class, 'platform.screens.customer');
Route::screen('customers', CustomerListScreen::class, 'platform.screens.customers');
//Rutas para Invoice Sales edicion y listado
Route::screen('invoice-sale', InvoiceSaleEditScreen::class, 'platform.screens.invoice-sale');
Route::screen('invoice-sales', InvoiceSaleListScreen::class, 'platform.screens.invoice-sales');
//Rlutes para Invoice Purchase
Route::screen('invoice-purchase', InvoiceSaleEditScreen::class, 'platform.screens.invoice-purchase');
Route::screen('invoice-purchases', InvoiceSaleListScreen::class, 'platform.screens.invoice-purchases');
//Route para Product
Route::screen('product', InvoiceSaleEditScreen::class, 'platform.screens.product');
Route::screen('products', InvoiceSaleListScreen::class, 'platform.screens.products');
//Route para Stock
Route::screen('stock', InvoiceSaleEditScreen::class, 'platform.screens.stock');
Route::screen('stocks', InvoiceSaleListScreen::class, 'platform.screens.stocks');
//Route para Movements Stocks
Route::screen('movement-stock', InvoiceSaleEditScreen::class, 'platform.screens.movement-stock');
Route::screen('movement-stocks', InvoiceSaleListScreen::class, 'platform.screens.movement-stocks');
//Routes para Supplier
Route::screen('supplier', CustomerEditScreen::class, 'platform.screens.supplier');
Route::screen('suppliers', CustomerListScreen::class, 'platform.screens.suppliers');
//Route para Wharehouse
Route::screen('wharehouse', InvoiceSaleEditScreen::class, 'platform.screens.wharehouse');
Route::screen('wharehouses', InvoiceSaleListScreen::class, 'platform.screens.wharehouses');
