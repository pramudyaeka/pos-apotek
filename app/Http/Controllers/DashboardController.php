<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function index()
    {
        return view('owner.overview.dashboard',['totalProducts'=>Product::where('is_active',true)->count(),'lowStockProducts'=>Product::where('is_active',true)->whereColumn('stock','<=','min_stock')->count(),'totalCategories'=>Category::where('is_active',true)->count(),'todaySales'=>Sale::whereDate('created_at',today())->sum('total'),'recentSales'=>Sale::with('items')->latest()->limit(5)->get()]);
    }
}