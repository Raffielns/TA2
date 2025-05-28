<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Cek apakah user admin atau customer
        if (auth()->user()->role == 1) {
            // ADMIN
            $products = Product::with('category')->latest()->get();
            return view('adminMenu.products.index', compact('products'));
        } else {
            // CUSTOMER
            return redirect()->route('catalog');
        }
    }

    public function catalogView()
    {
        $products = Product::with('category')->latest()->get();
        $allCategories = Category::all();
        return view('catalog', compact('products', 'allCategories'));

        $query = Product::query();

        // Filter berdasarkan kategori jika ada
        if (request()->has('category')) {
            $query->where('category_id', request('category'));
        }

        $products = $query->latest()->paginate(12);
        return view('catalog', compact('products'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();

        return view('adminMenu.products.createProduct', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $file = $request->file('image');
        if ($file != null) {
            $originalfile = $file->getClientOriginalName();
            $encryptedfile = $file->hashName();

            $file->store('/public/files');
        }

        $product = new Product;
        $product->nama_barang = $request->productName;
        $product->harga_barang = $request->price;
        $product->deskripsi = $request->description;
        $product->category_id = $request->category;
        $product->stock = $request->stock;
        $product->code = $request->code;

        if ($file != null) {
            $product->original_filename = $originalfile;
            $product->encrypted_filename = $encryptedfile;
        }

        $product->save();
        // Log aktivitas
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Menambahkan produk baru',
            'target' => $product->nama_barang
        ]);
        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with(['category', 'images'])->findOrFail($id);
        $product->increment('views');

        $relatedProducts = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get();

        return view('catalog-detail', compact('product', 'relatedProducts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories = Category::all();
        $products  = Product::find($id);

        return view('adminMenu.products.editProduct', compact(['categories', 'products']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $file = $request->file('image');
        if ($file != null) {
            $product = Product::find($id);
            $encryptedFile = 'public/files/' . $product->encrypted_filename;
            Storage::delete($encryptedFile);
        }
        if ($file != null) {
            $originalfile = $file->getClientOriginalName();
            $encryptedfile = $file->hashName();

            $file->store('/public/files');
        }

        $product = Product::find($id);
        $product->nama_barang = $request->productName;
        $product->harga_barang = $request->price;
        $product->deskripsi = $request->description;
        $product->category_id = $request->category;
        $product->stock = $request->stock;
        $product->code = $request->code;


        if ($file != null) {
            $product->original_filename = $originalfile;
            $product->encrypted_filename = $encryptedfile;
        }

        $product->save();
        // Log aktivitasq
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Mengedit produk',
            'target' => $product->nama_barang
        ]);

        return redirect()->route('products.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        // Hapus file gambar terkait jika ada
        if ($product && $product->encrypted_filename) {
            $encryptedFile = 'public/files/' . $product->encrypted_filename;
            Storage::delete($encryptedFile);
        }

        $product = Product::findOrFail($id);
        $productName = $product->nama_barang;
        // Hapus data produk dari database
        $product->delete();
        // Log aktivitas
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Menghapus produk',
            'target' => $product->nama_barang
        ]);

        // Redirect dengan pesan sukses untuk SweetAlert
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }

    public function catalogByCategory($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::where('category_id', $category->id)
            ->with('category')
            ->latest()
            ->paginate(12);
        $allCategories = Category::all();

        return view('catalog', compact('products', 'category', 'allCategories'));
    }
}
