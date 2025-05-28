@extends('layouts.admin')

@section('main-content')
<div class="container py-4">
    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Edit Produk: {{ $products->nama_barang }}</h4>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('products.update', $products->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="code" class="form-label">Kode Produk</label>
                        <input type="text" name="code" id="code" class="form-control" value="{{ $products->code }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="productName" class="form-label">Nama Produk</label>
                        <input type="text" name="productName" id="productName" class="form-control" value="{{ $products->nama_barang }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi Produk</label>
                    <textarea name="description" id="description" class="form-control" rows="3" required>{{ $products->deskripsi }}</textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="price" class="form-label">Harga Produk</label>
                        <input type="number" name="price" id="price" class="form-control" value="{{ $products->harga_barang }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="stock" class="form-label">Stok Produk</label>
                        <input type="number" name="stock" id="stock" class="form-control" value="{{ $products->stock }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="category" class="form-label">Kategori Produk</label>
                    <select name="category" id="category" class="form-select" required>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $products->category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Gambar Produk</label>
                    <input type="file" name="image" id="image" class="form-control" required>

                    {{-- Tempat preview gambar --}}
                    <img id="image-preview" class="img-fluid rounded mt-3 shadow" style="max-height: 150px; display: none;">
                </div>


                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-label {
        font-weight: 600;
        color: #112D4E;
    }

    .btn-primary {
        background-color: #3F72AF;
        border-color: #3F72AF;
    }

    .btn-primary:hover {
        background-color: #2f5f9c;
        border-color: #2f5f9c;
    }

    .bg-primary {
        background-color: #112D4E !important;
    }
</style>

@push('scripts')
<script>
    // Preview gambar saat upload
    document.getElementById('image').addEventListener('change', function (e) {
        const previewContainer = document.getElementById('image-preview');
        const file = e.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                if (!previewContainer) {
                    const newImg = document.createElement('img');
                    newImg.id = 'image-preview';
                    newImg.src = e.target.result;
                    newImg.className = 'img-fluid rounded mt-3 shadow';
                    newImg.style.maxHeight = '150px';
                    e.target.closest('.mb-3').appendChild(newImg);
                } else {
                    previewContainer.src = e.target.result;
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // Validasi form real-time
    const requiredInputs = document.querySelectorAll('input[required], textarea[required], select[required]');
    requiredInputs.forEach(input => {
        input.addEventListener('input', () => {
            if (input.value.trim() === '') {
                input.classList.add('is-invalid');
                input.classList.remove('is-valid');
            } else {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
            }
        });
    });
</script>
@endpush

@endsection
