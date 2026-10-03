<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function index()
    {
        $todaySales=Sale::whereDate('created_at',today());
        $hourly=$todaySales->selectRaw('HOUR(created_at) as hour, SUM(total) as total')->groupBy('hour')->pluck('total','hour');
        return view('owner.overview.dashboard',[
            'totalProducts'=>Product::where('is_active',true)->count(),
            'lowStockProducts'=>Product::where('is_active',true)->whereColumn('stock','<=','min_stock')->where('stock','>',0)->count(),
            'outOfStockProducts'=>Product::where('is_active',true)->where('stock',0)->count(),
            'totalCategories'=>Category::where('is_active',true)->count(),
            'todaySales'=>$todaySales->sum('total'),
            'totalOrders'=>Sale::count(),
            'recentSales'=>Sale::with('items')->latest()->limit(5)->get(),
            'hourlySales'=>$hourly,
        ]);
    }
}
