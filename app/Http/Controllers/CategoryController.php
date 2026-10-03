<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index() { return view('owner.inventory.categories',['categories'=>Category::withCount('products')->orderBy('name')->get()]); }

    public function store(Request $request)
    {
        $data=$request->validate(['name'=>'required|string|max:100|unique:categories,name','is_active'=>'sometimes|boolean']);
        $category=Category::create($data+['is_active'=>true]);
        return response()->json($category->loadCount('products'),201);
    }

    public function update(Request $request, Category $category)
    {
        $data=$request->validate(['name'=>['required','string','max:100',Rule::unique('categories','name')->ignore($category->id)],'is_active'=>'required|boolean']);
        $category->update($data);
        return response()->json($category->loadCount('products'));
    }

    public function destroy(Category $category)
    {
        if($category->products()->exists()) return response()->json(['message'=>'Category masih digunakan oleh produk.'],422);
        $category->delete();
        return response()->json(['message'=>'Category deleted.']);
    }
}