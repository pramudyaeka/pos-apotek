<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/signup', function () {
    return view('auth.signup');
});

Route::get('/signin', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('owner.overview.dashboard');
})->name('dashboard');

Route::get('/cashier', function () {
    return view('cashier.overview.dashboard');
})->name('cashier');

Route::get('/orders', function () {
    return view('owner.overview.orders');
})->name('orders');

Route::get('/transaction', function () {
    return view('owner.overview.transaction');
})->name('transaction');

Route::get('/category', function () {
    return view('owner.inventory.categories');
})->name('category');