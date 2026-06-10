<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TransactionController;

// Rute Publik (Etalase Toko)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rute Admin (Manajemen Produk)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/pos', [TransactionController::class, 'create'])->name('pos.index');
Route::post('/pos/checkout', [TransactionController::class, 'store'])->name('pos.checkout');
Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');