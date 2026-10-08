<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ActivityLog;
use App\Models\Sale;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        $data=$request->validate([
            'items'=>'required|array|min:1',
            'items.*.product_id'=>'required|integer|exists:products,id',
            'items.*.quantity'=>'required|integer|min:1',
            'payment_method'=>['required',Rule::in(['Cash','Debit','QRIS'])],
            'amount_received'=>'nullable|numeric|min:0',
        ]);

        if ($data['payment_method'] === 'Cash' && (!array_key_exists('amount_received', $data) || $data['amount_received'] === null)) {
            throw \Illuminate\Validation\ValidationException::withMessages(['amount_received' => 'Jumlah uang diterima wajib diisi untuk pembayaran tunai.']);
        }
        $sale=DB::transaction(function() use($data,$request){
            $subtotal=0; $rows=[];
            $quantities = [];
            foreach ($data['items'] as $item) {
                $productId = (int) $item['product_id'];
                $quantities[$productId] = ($quantities[$productId] ?? 0) + (int) $item['quantity'];
            }

            foreach ($quantities as $productId => $quantity) {
                $product = Product::lockForUpdate()->findOrFail($productId);
                if (!$product->is_active) {
                    abort(422, 'Product tidak aktif: '.$product->name);
                }
                if ($product->stock < $quantity) {
                    abort(422, 'Stok tidak cukup untuk '.$product->name.'. Tersedia '.$product->stock.'.');
                }

                $line = (float) $product->price * $quantity;
                $subtotal += $line;
                $rows[] = ['product' => $product, 'quantity' => $quantity, 'subtotal' => $line];
            }
            $amountReceived = $data['payment_method'] === 'Cash' ? (float) $data['amount_received'] : null;
            $changeAmount = $amountReceived !== null ? $amountReceived - $subtotal : null;
            if ($changeAmount !== null && $changeAmount < 0) {
                abort(422, 'Jumlah uang diterima kurang dari total transaksi.');
            }

            $sale=Sale::create(['invoice_number'=>'TMP-'.bin2hex(random_bytes(8)),'user_id'=>$request->user()->id,'subtotal'=>$subtotal,'tax'=>0,'total'=>$subtotal,'amount_received'=>$amountReceived,'change_amount'=>$changeAmount,'payment_method'=>$data['payment_method'],'status'=>'Success']);
            $sale->update(['invoice_number' => '#'.str_pad((string)$sale->id, 4, '0', STR_PAD_LEFT)]);
            foreach($rows as $row){
                $product=$row['product']; $before=$product->stock; $product->decrement('stock',$row['quantity']);
                $sale->items()->create(['product_id'=>$product->id,'product_name'=>$product->name,'unit'=>$product->unit,'quantity'=>$row['quantity'],'unit_price'=>$product->price,'subtotal'=>$row['subtotal']]);
                StockMovement::create(['product_id'=>$product->id,'user_id'=>$request->user()->id,'type'=>'OUT','quantity'=>-$row['quantity'],'stock_before'=>$before,'stock_after'=>$before-$row['quantity'],'reference_type'=>'sale','reference_id'=>$sale->id,'note'=>'Sale '.$sale->invoice_number]);
            }
            ActivityLog::record($request->user(), 'Sale', 'sale', 'Membuat transaksi '.$sale->invoice_number.' sebesar Rp '.number_format($sale->total, 0, ',', '.').'.', Sale::class, $sale->id, ['payment_method' => $sale->payment_method]);
            return $sale->load('items','user');
        });
        return response()->json($sale,201);
    }

    public function index(Request $request)
    {
        $query = Sale::with('items', 'user');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($user) => $user->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('payment_method') && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->date('from')->toDateString());
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->date('to')->toDateString());
        }

        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'oldest' => $query->oldest('created_at'),
            'highest' => $query->orderByDesc('total'),
            'lowest' => $query->orderBy('total'),
            default => $query->latest('created_at'),
        };

        $transactions = $query->paginate(20)->withQueryString();
        $transactionData = $transactions->getCollection()->map(fn ($sale) => [
            'id' => $sale->id,
            'date' => $sale->created_at->format('d F Y'),
            'invoice' => $sale->invoice_number,
            'method' => $sale->payment_method,
            'amount' => (float) $sale->total,
            'amount_received' => $sale->amount_received !== null ? (float) $sale->amount_received : null,
            'change' => $sale->change_amount !== null ? (float) $sale->change_amount : null,
            'status' => $sale->status,
            'time' => $sale->created_at->format('H:i, D, d F Y'),
            'items' => $sale->items->map(fn ($item) => [
                'name' => $item->quantity . 'x ' . $item->product_name,
                'price' => (float) $item->subtotal,
            ])->values(),
            'receipt_url' => route('transaction.receipt', $sale),
        ])->values();

        return view('owner.overview.transaction', compact('transactions', 'transactionData'));
    }

    public function receipt(Request $request, Sale $sale)
    {
        if ($request->user()->isCashier() && $sale->user_id !== $request->user()->id) {
            abort(403);
        }

        $sale->load('items', 'user');
        return view('owner.overview.receipt', compact('sale'));
    }

    private function nextInvoiceNumber(): string
    {
        return '#'.str_pad((string)((int)Sale::max('id')+1),4,'0',STR_PAD_LEFT);
    }
}