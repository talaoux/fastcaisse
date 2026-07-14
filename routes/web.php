<?php

use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/dashboard', function () {
    $products = Product::orderBy('created_at', 'desc')->get();

    return view('admin.dashboard', compact('products'));
})->name('admin.dashboard');

Route::get('/cashier/dashboard', [\App\Http\Controllers\CashierController::class, 'index'])->name('cashier.dashboard');

// Routes pour le module Produits
Route::resource('products', ProductController::class);

// Route pour la section Ventes (POS)
Route::get('/admin/sales', [\App\Http\Controllers\Admin\SalesController::class, 'index'])->name('admin.sales');
Route::post('/admin/sales', [\App\Http\Controllers\Admin\SalesController::class, 'store'])->name('admin.sales.store');

// Routes pour les sections Stock et Clients
Route::get('/admin/stock', [\App\Http\Controllers\Admin\StockController::class, 'index'])->name('admin.stock');
Route::post('/admin/stock', [\App\Http\Controllers\Admin\StockController::class, 'store'])->name('admin.stock.store');
Route::get('/admin/customers', [\App\Http\Controllers\Admin\CustomersController::class, 'index'])->name('admin.customers');
