@extends('layouts.admin')

@section('main-content')
    <div class="container px-5 my-5">
        <h3 class="mb-5">Add Product</h3>
        <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="categoryName">Nama Kategori</label>
                <input type="text" name="categoryName" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="image">Gambar Kategori</label>
                <input type="file" name="image" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
