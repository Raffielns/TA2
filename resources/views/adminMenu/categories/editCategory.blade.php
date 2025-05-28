@extends('layouts.admin')

@section('main-content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        {{ isset($category) ? 'Edit Kategori Produk' : 'Tambah Kategori Produk' }}
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ isset($category) ? route('category.update', $category->id) : route('category.store') }}">
                        @csrf
                        @if(isset($category))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label for="categoryName" class="form-label">Nama Kategori</label>
                            <input type="text" name="categoryName" id="categoryName" class="form-control"
                                placeholder="Masukkan nama kategori"
                                value="{{ old('categoryName', $category->name ?? '') }}" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
