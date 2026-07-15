<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;

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

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Guest Routes (for registration)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// Protected Routes (require authentication)
Route::middleware('auth')->group(function () {
    // Admin Routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        
        // Admin sales and stock routes
        Route::get('/admin/sales', [\App\Http\Controllers\Admin\SalesController::class, 'index'])->name('admin.sales');
        Route::post('/admin/sales', [\App\Http\Controllers\Admin\SalesController::class, 'store'])->name('admin.sales.store');

        Route::get('/admin/stock', [\App\Http\Controllers\Admin\StockController::class, 'index'])->name('admin.stock');
        Route::post('/admin/stock', [\App\Http\Controllers\Admin\StockController::class, 'store'])->name('admin.stock.store');
        Route::get('/admin/customers', [\App\Http\Controllers\Admin\CustomersController::class, 'index'])->name('admin.customers');
    });

    // Cashier Routes
    Route::middleware('role:cashier')->group(function () {
        Route::get('/cashier/dashboard', [\App\Http\Controllers\CashierController::class, 'index'])->name('cashier.dashboard');
    });

    // Routes for both admin and cashier
    Route::resource('products', ProductController::class);
    
    // Profile routes
    Route::post('/profile/avatar', [App\Http\Controllers\ProfileController::class, 'updateAvatar'])->name('profile.avatar');
});
