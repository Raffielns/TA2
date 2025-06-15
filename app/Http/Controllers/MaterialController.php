<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::all();
        return view('adminMenu.material.index', compact('materials'));
    }

    public function create()
    {
        return view('adminMenu.material.createMaterial');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'stock' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'price' => 'required|numeric|min:0',
            'supplier' => 'required|string|max:255',
            'minimum_stock' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('materials', 'public');
        }

        Material::create($validated);

        // Log the creation of a new material
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Menambahkan bahan material baru',
            'target' => $validated['name']
        ]);


        return redirect()->route('materials.index')
            ->with('success', 'Bahan baku berhasil ditambahkan!');
    }

    public function show(Material $material)
    {
        return view('admin.material.show', compact('material'));
    }

    public function edit(Material $material)
    {
        return view('adminMenu.material.index', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'stock' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'price' => 'required|numeric|min:0',
            'supplier' => 'required|string|max:255',
            'minimum_stock' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($material->image) {
                Storage::disk('public')->delete($material->image);
            }
            $validated['image'] = $request->file('image')->store('materials', 'public');
        }

        $material->update($validated);

        return redirect()->route('materials.index')
            ->with('success', 'Bahan baku berhasil diperbarui!');
    }

    public function destroy(Material $material)
    {
        // Hapus gambar jika ada
        if ($material->image) {
            Storage::disk('public')->delete($material->image);
        }

        $material->delete();

        return redirect()->route('materials.index')
            ->with('success', 'Bahan baku berhasil dihapus!');
    }
}
