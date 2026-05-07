<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PosController;


Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('guest')->group(function (){
      Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
      Route::post('/login', [AuthController::class, 'login']);
      Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
      Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function (){
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Admin Only Routes - Inventory Management
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Show the inventory page
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');

    // Process the form submission to add a category
    Route::post('/inventory/category', [InventoryController::class, 'storeCategory'])->name('category.store');
});

// Admin and Cashier Routes - Product Management
Route::middleware(['auth', 'role:admin,cashier'])->group(function () {
    // Show the products page
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    // Process the form submission to add a product
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    // Update or Add product details (like stock after a sale)
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');

    // POS Cash Register
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');


    // The Receipt Page
    Route::get('/pos/receipt/{id}', [PosController::class, 'receipt'])->name('pos.receipt');
});
