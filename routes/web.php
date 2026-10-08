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

    Route::post('/category',[CategoryController::class,'store'])->name('category.store');
    Route::put('/category/{category}',[CategoryController::class,'update'])->name('category.update');
    Route::post('/product',[ProductController::class,'store'])->name('product.store');

    Route::get('/user',[UserController::class,'index'])->name('user-management');
    Route::post('/user',[UserController::class,'store'])->name('user.store');
    Route::put('/user/{user}',[UserController::class,'update'])->name('user.update');
    Route::delete('/user/{user}',[UserController::class,'destroy'])->name('user.destroy');
    Route::post('/user/{user}/reset-password',[UserController::class,'resetPassword'])->name('user.reset-password');

    Route::get('/transaction',[SaleController::class,'index'])->name('transaction');
    Route::get('/reporting', [ReportingController::class, 'index'])->name('reporting');
    Route::get('/reporting/export/excel', [ReportingController::class, 'exportExcel'])->name('reporting.export.excel');
    Route::get('/reporting/export/pdf', [ReportingController::class, 'exportPdf'])->name('reporting.export.pdf');
});

Route::middleware(['auth','role:Owner,Cashier'])->group(function () {
    Route::get('/password', [UserController::class, 'password'])->name('password.edit');
    Route::put('/password', [UserController::class, 'changePassword'])->name('password.update');
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::get('/cashier', fn() => redirect()->route('orders'))->name('cashier');
    Route::get('/orders',[OrderController::class,'index'])->name('orders');
    Route::post('/sales',[SaleController::class,'store'])->name('sales.store');
    Route::get('/transaction/{sale}/receipt',[SaleController::class,'receipt'])->name('transaction.receipt');

    Route::get('/category',[CategoryController::class,'index'])->name('category');
    Route::get('/product',[ProductController::class,'index'])->name('product');
    Route::put('/product/{product}',[ProductController::class,'update'])->name('product.update');
});
