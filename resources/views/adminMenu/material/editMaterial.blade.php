@extends('layouts.admin')

@section('main-content')
    <div class="container px-5 my-5">
        <h3 class="mb-5">Edit Bahan Baku</h3>
        <form method="POST" action="{{ route('materials.update', $material->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label" for="name">Nama Bahan Baku</label>
                <input class="form-control" id="name" name="name" type="text" value="{{ $material->name }}" required />
            </div>

            <div class="mb-3">
                <label class="form-label" for="description">Deskripsi</label>
                <textarea class="form-control" id="description" name="description" rows="3" required>{{ $material->description }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label" for="category">Kategori Bahan</label>
                <select name="category" id="category" class="form-control" required>
                    <option value="">Pilih Kategori</option>
                    @foreach ([
                        'Semua Kategori Seal',
                        'Komponen Mekanik & Bengkel',
                    ] as $kategori)
                        <option value="{{ $kategori }}" {{ $material->category == $kategori ? 'selected' : '' }}>
                            {{ $kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="stock">Stok</label>
                    <div class="input-group">
                        <input class="form-control" id="stock" name="stock" type="number" value="{{ $material->stock }}" required />
                        <span class="input-group-text">unit</span>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label" for="unit">Satuan</label>
                    <select name="unit" id="unit" class="form-control" required>
                        @foreach (['kg', 'g', 'liter', 'roll', 'pcs'] as $unit)
                            <option value="{{ $unit }}" {{ $material->unit == $unit ? 'selected' : '' }}>{{ strtoupper($unit) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="price">Harga per Unit</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input class="form-control" id="price" name="price" type="number" value="{{ $material->price }}" required />
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label" for="supplier">Supplier</label>
                    <input class="form-control" id="supplier" name="supplier" type="text" value="{{ $material->supplier }}" required />
                </div>
            </div>

            {{-- <div class="mb-3">
                <label class="form-label" for="minimum_stock">Stok Minimum</label>
                <input class="form-control" id="minimum_stock" name="minimum_stock" type="number" value="{{ $material->minimum_stock }}" required />
            </div> --}}

            <div class="mb-3">
                <label for="image" class="form-label">Gambar Bahan Baku</label>
                <input type="file" class="form-control" name="image" id="image" accept="image/*">
                <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
                @if ($material->image)
                    <div class="mt-2">
                        <img src="{{ asset('storage/files/' . $material->image) }}" alt="Gambar Bahan" class="img-thumbnail" style="max-width: 150px;">
                    </div>
                @endif
            </div>

            <div class="d-grid">
                <button class="btn btn-success" type="update">Perbarui</button>
            </div>
        </form>
    </div>
@endsection
