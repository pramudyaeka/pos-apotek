<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        $data=$request->validate(['items'=>'required|array|min:1','items.*.product_id'=>'required|integer|exists:products,id','items.*.quantity'=>'required|integer|min:1','payment_method'=>'required|string|max:30']);
        $sale=DB::transaction(function() use($data,$request){
            $subtotal=0; $rows=[];
            foreach($data['items'] as $item){
                $product=Product::lockForUpdate()->findOrFail($item['product_id']);
                if(!$product->is_active) abort(422,'Product tidak aktif: '.$product->name);
                if($product->stock<$item['quantity']) abort(422,'Stok tidak cukup untuk '.$product->name.'. Tersedia '.$product->stock.'.');
                $line=(float)$product->price*$item['quantity']; $subtotal+=$line;
                $rows[]=['product'=>$product,'quantity'=>$item['quantity'],'subtotal'=>$line];
            }
            $sale=Sale::create(['invoice_number'=>$this->nextInvoiceNumber(),'user_id'=>$request->user()->id,'subtotal'=>$subtotal,'tax'=>0,'total'=>$subtotal,'payment_method'=>$data['payment_method'],'status'=>'Success']);
            foreach($rows as $row){
                $product=$row['product']; $before=$product->stock; $product->decrement('stock',$row['quantity']);
                $sale->items()->create(['product_id'=>$product->id,'product_name'=>$product->name,'unit'=>$product->unit,'quantity'=>$row['quantity'],'unit_price'=>$product->price,'subtotal'=>$row['subtotal']]);
                StockMovement::create(['product_id'=>$product->id,'user_id'=>$request->user()->id,'type'=>'OUT','quantity'=>-$row['quantity'],'stock_before'=>$before,'stock_after'=>$before-$row['quantity'],'reference_type'=>'sale','reference_id'=>$sale->id,'note'=>'Sale '.$sale->invoice_number]);
            }
            return $sale->load('items','user');
        });
        return response()->json($sale,201);
    }

    public function index()
    {
        return view('owner.overview.transaction',['transactions'=>Sale::with('items','user')->latest()->paginate(20)]);
    }

    private function nextInvoiceNumber(): string
    {
        return '#'.str_pad((string)((int)Sale::max('id')+1),4,'0',STR_PAD_LEFT);
    }
}