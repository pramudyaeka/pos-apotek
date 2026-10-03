<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        if ($request->filled('module') && $request->module !== 'all') {
            $query->where('module', $request->module);
        }

        if ($request->filled('action') && $request->action !== 'all') {
            $query->where('action', $request->action);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('module', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($user) => $user->where('name', 'like', "%{$search}%"));
            });
        }

        $activities = $query->paginate(20)->withQueryString();

        $stockQuery = StockMovement::with(['product', 'user'])->latest();

        if ($request->filled('stock_type') && $request->stock_type !== 'all') {
            $stockQuery->where('type', $request->stock_type);
        }

        $stockMovements = $stockQuery->paginate(15, ['*'], 'stock_page')->withQueryString();

        return view('history.index', compact('activities', 'stockMovements'));
    }
}