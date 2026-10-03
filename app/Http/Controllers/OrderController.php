<?php

namespace App\Http\Controllers;

use App\Models\Product;

class OrderController extends Controller
{
    public function index()
    {
        return view('owner.overview.orders', ['products'=>Product::with('category')->where('is_active',true)->where('stock','>',0)->orderBy('name')->get()]);
    }
}