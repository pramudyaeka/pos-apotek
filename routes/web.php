<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('auth.login'))->name('login');
Route::post('/login', [AuthController::class,'login'])->name('login.store');
Route::post('/logout', [AuthController::class,'logout'])->name('logout');

Route::middleware(['auth','role:Owner'])->group(function () {
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');

    Route::delete('/category/{category}',[CategoryController::class,'destroy'])->name('category.destroy');

    Route::delete('/product/{product}',[ProductController::class,'destroy'])->name('product.destroy');

    Route::get('/user',[UserController::class,'index'])->name('user-management');
    Route::post('/user',[UserController::class,'store'])->name('user.store');
    Route::put('/user/{user}',[UserController::class,'update'])->name('user.update');
    Route::delete('/user/{user}',[UserController::class,'destroy'])->name('user.destroy');

    Route::get('/transaction',[SaleController::class,'index'])->name('transaction');
    Route::get('/transaction/{sale}/receipt',[SaleController::class,'receipt'])->name('transaction.receipt');
    Route::get('/reporting', [ReportingController::class, 'index'])->name('reporting');
});

Route::middleware(['auth','role:Owner,Cashier'])->group(function () {
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::get('/cashier', fn() => redirect()->route('orders'))->name('cashier');
    Route::get('/orders',[OrderController::class,'index'])->name('orders');
    Route::post('/sales',[SaleController::class,'store'])->name('sales.store');

    Route::get('/category',[CategoryController::class,'index'])->name('category');
    Route::post('/category',[CategoryController::class,'store'])->name('category.store');
    Route::put('/category/{category}',[CategoryController::class,'update'])->name('category.update');

    Route::get('/product',[ProductController::class,'index'])->name('product');
    Route::post('/product',[ProductController::class,'store'])->name('product.store');
    Route::put('/product/{product}',[ProductController::class,'update'])->name('product.update');
});
