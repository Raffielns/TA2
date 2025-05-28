<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categoryName = request('categoryName');
        $categories = \App\Models\Category::withCount('products')
            ->when($categoryName, function ($query, $categoryName) {
                return $query->where('name', 'like', '%' . $categoryName . '%');
            })
            ->latest()
            ->paginate(10);

        return view('adminMenu.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('adminMenu.categories.createCategory');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $category = new Category;
        $category->name = $request->categoryName;
        $category->save();

        // Log the creation of a new category
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Menambahkan kategori produk',
            'target' => $category->name
        ]);

        return redirect()->route('category.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('adminMenu.categories.editCategory', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->name = $request->categoryName;
        $category->save();

        // Log the update of a category
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Mengubah kategori produk',
            'target' => $category->name
        ]);

        return redirect()->route('category.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        // Log the deletion of a category
        $categoryName = $category->name;
        $category->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Menghapus kategori produk',
            'target' => $categoryName
        ]);

        return redirect()->route('category.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
