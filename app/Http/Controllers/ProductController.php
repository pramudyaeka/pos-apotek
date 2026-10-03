<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        return view('owner.inventory.products',['products'=>Product::with('category')->orderBy('name')->get(),'categories'=>Category::where('is_active',true)->orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data=$request->validate(['name'=>'required|string|max:255','category_id'=>['required','exists:categories,id',Rule::exists('categories','id')->where(fn($query) => $query->where('is_active', true))],'unit'=>'required|string|max:50','price'=>'required|numeric|min:0','stock'=>'required|integer|min:0','min_stock'=>'required|integer|min:0','is_active'=>'sometimes|boolean']);
        $product=DB::transaction(function() use($data,$request){
            $product=Product::create($data+['is_active'=>true]);
            if($product->stock>0) StockMovement::create(['product_id'=>$product->id,'user_id'=>$request->user()->id,'type'=>'IN','quantity'=>$product->stock,'stock_before'=>0,'stock_after'=>$product->stock,'reference_type'=>'initial','note'=>'Initial stock']);
            return $product;
        });
        return response()->json($product->load('category'),201);
    }

    public function update(Request $request, Product $product)
    {
        $data=$request->validate(['name'=>'required|string|max:255','category_id'=>'required|exists:categories,id','unit'=>'required|string|max:50','price'=>'required|numeric|min:0','stock'=>'required|integer|min:0','min_stock'=>'required|integer|min:0','is_active'=>'required|boolean']);
        DB::transaction(function() use($data,$product,$request){
            $before=$product->stock; $product->update($data);
            if($before!==$product->stock) StockMovement::create(['product_id'=>$product->id,'user_id'=>$request->user()->id,'type'=>$product->stock>$before?'IN':'OUT','quantity'=>$product->stock-$before,'stock_before'=>$before,'stock_after'=>$product->stock,'reference_type'=>'adjustment','note'=>'Manual stock adjustment']);
        });
        return response()->json($product->fresh()->load('category'));
    }

    public function destroy(Product $product)
    {
        if($product->saleItems()->exists()) return response()->json(['message'=>'Product sudah memiliki transaksi dan tidak dapat dihapus. Nonaktifkan produk sebagai gantinya.'],422);
        $product->delete();
        return response()->json(['message'=>'Product deleted.']);
    }
}