<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function index()
    {
        $todaySales = Sale::whereDate('created_at', today());

        // Build the hourly chart in PHP so the dashboard works consistently
        // with both MySQL (production) and SQLite (test environment).
        $hourly = $todaySales->get(['created_at', 'total'])
            ->groupBy(fn ($sale) => $sale->created_at->hour)
            ->map(fn ($sales) => $sales->sum('total'))
            ->sortKeys();

        return view('owner.overview.dashboard', [
            'totalProducts' => Product::where('is_active', true)->count(),
            'lowStockProducts' => Product::where('is_active', true)->whereColumn('stock', '<=', 'min_stock')->where('stock', '>', 0)->count(),
            'outOfStockProducts' => Product::where('is_active', true)->where('stock', 0)->count(),
            'totalCategories' => Category::where('is_active', true)->count(),
            'todaySales' => $todaySales->sum('total'),
            'totalOrders' => Sale::count(),
            'recentSales' => Sale::with('items')->latest()->limit(5)->get(),
            'hourlySales' => $hourly,
            'chartLabels' => $hourly->keys()->map(fn ($h) => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00')->values(),
            'chartData' => $hourly->values()->map(fn ($v) => (float) $v)->values(),
        ]);
    }
}
