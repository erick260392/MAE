<?php

use App\Livewire\Admin\Categories;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Login;
use App\Livewire\Admin\Products;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Catálogo público
Route::get('/', function () {
    return view('welcome');
});

// Auth
Route::get('/admin/login', Login::class)->name('admin.login')->middleware('guest');
Route::post('/admin/logout', function () {
    Auth::logout();
    return redirect()->route('admin.login');
})->name('admin.logout');

// Panel admin (protegido)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/products', Products::class)->name('products');
    Route::get('/categories', Categories::class)->name('categories');
    Route::get('/customers', fn() => 'Clientes - próximamente')->name('customers');
    Route::get('/quotes', fn() => 'Cotizaciones - próximamente')->name('quotes');
});

// Redirigir /admin a dashboard
Route::get('/admin', fn() => redirect()->route('admin.dashboard'));
