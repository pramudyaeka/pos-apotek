<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('name')->get();
        $categoryData = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'is_active' => (bool) $category->is_active,
                'products_count' => $category->products_count,
            ];
        })->values();

        return view('owner.inventory.categories', compact('categories', 'categoryData'));
    }

    public function store(Request $request)
    {
        $data=$request->validate(['name'=>'required|string|max:100|unique:categories,name','is_active'=>'sometimes|boolean']);
        $category=Category::create($data+['is_active'=>true]);
        ActivityLog::record($request->user(), 'Category', 'create', 'Membuat kategori "'.$category->name.'".', Category::class, $category->id);
        return response()->json($category->loadCount('products'),201);
    }

    public function update(Request $request, Category $category)
    {
        $data=$request->validate(['name'=>['required','string','max:100',Rule::unique('categories','name')->ignore($category->id)],'is_active'=>'required|boolean']);
        $category->update($data);
        ActivityLog::record($request->user(), 'Category', 'update', 'Memperbarui kategori "'.$category->name.'".', Category::class, $category->id);
        return response()->json($category->loadCount('products'));
    }

    public function destroy(Request $request, Category $category)
    {
        if($category->products()->exists()) return response()->json(['message'=>'Kategori masih digunakan oleh produk.'],422);
        $name = $category->name;
        $id = $category->id;
        $category->delete();
        ActivityLog::record($request->user(), 'Category', 'delete', 'Menghapus kategori "'.$name.'".', Category::class, $id);
        return response()->json(['message'=>'Kategori berhasil dihapus.']);
    }
}