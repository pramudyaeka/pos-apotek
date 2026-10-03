<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportingController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->date('from')?->startOfDay() ?? now()->startOfMonth();
        $to = $request->date('to')?->endOfDay() ?? now()->endOfDay();

        if ($from->gt($to)) {
            [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
        }

        $salesQuery = Sale::whereBetween('created_at', [$from, $to]);

        $summary = [
            'orders' => (clone $salesQuery)->count(),
            'revenue' => (clone $salesQuery)->sum('total'),
            'average' => (clone $salesQuery)->avg('total') ?? 0,
        ];

        $payments = (clone $salesQuery)
            ->selectRaw('payment_method, COUNT(*) as orders, SUM(total) as total')
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();

        $topProducts = SaleItem::query()
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->whereBetween('sales.created_at', [$from, $to])
            ->selectRaw('sale_items.product_id, sale_items.product_name, SUM(sale_items.quantity) as quantity, SUM(sale_items.subtotal) as total')
            ->groupBy('sale_items.product_id', 'sale_items.product_name')
            ->orderByDesc('quantity')
            ->limit(10)
            ->get();

        return view('owner.reporting.index', compact('from', 'to', 'summary', 'payments', 'topProducts'));
    }
}
