@extends('layouts.admin')

@section('main-content')
<div class="container py-4">
    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Detail Produk: {{ $product->nama_barang }}</h4>
        </div>

        <div class="card-body row">
            <div class="col-md-5">
                <img src="{{ asset('storage/files/' . $product->encrypted_filename) }}" class="img-fluid rounded shadow" alt="{{ $product->nama_barang }}">
            </div>

            <div class="col-md-7">
                <h5 class="fw-bold mb-3">Informasi Produk</h5>
                <table class="table">
                    <tr>
                        <th style="width: 30%">Kode</th>
                        <td>{{ $product->code }}</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $product->nama_barang }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>{{ $product->category->name }}</td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td>Rp {{ number_format($product->harga_barang, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Stok</th>
                        <td>{{ $product->stock }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $product->deskripsi }}</td>
                    </tr>
                </table>

                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary mt-3">
                    <i class="bi bi-arrow-left-circle"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-primary {
        background-color: #112D4E !important;
    }

    .table th {
        color: #112D4E;
    }
</style>
@endsection
