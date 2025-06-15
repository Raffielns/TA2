@extends('layouts.admin')

@section('main-content')
    <div class="container px-5 my-5">
        <h3 class="mb-5">Tambah Bahan Baku</h3>
        <form method="POST" action="{{ route('materials.index') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="name">Nama Bahan Baku</label>
                <input class="form-control" id="name" name="name" type="text" placeholder="Nama bahan baku" required />
            </div>

            <div class="mb-3">
                <label class="form-label" for="description">Deskripsi</label>
                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Deskripsi bahan baku" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label" for="category">Kategori Bahan</label>
                <select name="category" id="category" class="form-control" required>
                    <option value="">Pilih Kategori</option>
                    <option value="logam">Logam</option>
                    <option value="plastik">Plastik</option>
                    <option value="karet">Karet</option>
                    <option value="kimia">Bahan Kimia</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="stock">Stok</label>
                    <div class="input-group">
                        <input class="form-control" id="stock" name="stock" type="number" placeholder="Jumlah stok" required />
                        <span class="input-group-text">unit</span>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label" for="unit">Satuan</label>
                    <select name="unit" id="unit" class="form-control" required>
                        <option value="kg">Kilogram (kg)</option>
                        <option value="g">Gram (g)</option>
                        <option value="liter">Liter</option>
                        <option value="ml">Mililiter (ml)</option>
                        <option value="pcs">Pieces (pcs)</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="price">Harga per Unit</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input class="form-control" id="price" name="price" type="number" placeholder="Harga per unit" required />
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label" for="supplier">Supplier</label>
                    <input class="form-control" id="supplier" name="supplier" type="text" placeholder="Nama supplier" required />
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="minimum_stock">Stok Minimum</label>
                <input class="form-control" id="minimum_stock" name="minimum_stock" type="number" placeholder="Stok minimum sebelum restock" required />
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Gambar Bahan Baku</label>
                <input type="file" class="form-control" name="image" id="image" accept="image/*">
                <small class="text-muted">Format: JPG, PNG, JPEG (Maks. 2MB)</small>
            </div>

            <div class="d-grid">
                <button class="btn btn-primary" type="submit">Simpan</button>
            </div>
        </form>
    </div>
@endsection
