<?php

use App\Http\Controllers\Admin\QuotePdfController;
use App\Livewire\Admin\Categories;
use App\Livewire\Admin\Customers;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Inventory;
use App\Livewire\Admin\Login;
use App\Livewire\Admin\Products;
use App\Livewire\Admin\QuoteCreate;
use App\Livewire\Admin\Quotes;
use App\Livewire\Admin\QuoteShow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Livewire\Catalog\ProductCatalog;

// Catálogo público
Route::get('/', ProductCatalog::class)->name('catalog');

// Auth
Route::get('/admin/login', Login::class)->name('admin.login')->middleware('guest');
Route::post('/admin/logout', function () {
    Auth::logout();
    return redirect()->route('catalog');
})->name('admin.logout');

// Panel admin (protegido)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/products', Products::class)->name('products');
    Route::get('/categories', Categories::class)->name('categories');
    Route::get('/customers', Customers::class)->name('customers');
    Route::get('/inventory', Inventory::class)->name('inventory');
    Route::get('/quotes', Quotes::class)->name('quotes');
    Route::get('/quotes/create', QuoteCreate::class)->name('quotes.create');
    Route::get('/quotes/{quote}', QuoteShow::class)->name('quotes.show');
    Route::get('/quotes/{quote}/pdf', QuotePdfController::class)->name('quotes.pdf');
});

// Redirigir /admin a dashboard
Route::get('/admin', fn() => redirect()->route('admin.dashboard'));
