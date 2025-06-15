@extends('layouts.app')

@section('content')
    <!-- Background Header -->
    <div style="background-image: url('{{ asset('img/bg-dashboard.jpg') }}'); background-size: cover; background-position: center; padding: 60px 0 30px 0;">
        <div class="container">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-white p-3 rounded shadow-sm">
                    <li class="breadcrumb-item"><a href="{{ route('landing') }}"><i class="fas fa-home"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('catalog') }}">Katalog Produk</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->nama_barang }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container py-5" style="background-color: #F9F7F7;">
        <div class="row">
            <!-- Gambar Utama -->
            <div class="col-md-6 mb-4">
                <img id="mainImage" src="{{ asset('storage/files/' . $product->encrypted_filename) }}"
                    class="img-fluid rounded shadow-sm w-100" style="object-fit: cover;">
                <!-- Thumbnail -->
                <div class="d-flex gap-2 flex-wrap mt-3">
                    @foreach ($product->images as $image)
                        <img src="{{ asset('storage/' . $image->image_path) }}" class="img-thumbnail"
                            style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                            onclick="document.getElementById('mainImage').src=this.src;">
                    @endforeach
                </div>
            </div>

            <!-- Info Produk -->
            <div class="col-md-6">
                <h2 class="fw-bold" style="color: #112D4E;">{{ $product->nama_barang }}</h2>
                <p class="text-muted">
                    Kategori:
                    <span class="badge" style="background-color: #3F72AF; color: white;">
                        {{ $product->category->name ?? 'Tanpa Kategori' }}
                    </span>
                </p>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <small><i class="fas fa-eye"></i> {{ $product->views }} dilihat | {{ $product->sold }} terjual</small>
                    <a href="{{ url()->current() }}" class="text-decoration-none text-muted"><i
                            class="fas fa-share-alt"></i> Bagikan</a>
                </div>

                <div class="product-rating mb-3 d-flex align-items-center text-warning">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= 4)
                            <i class="fas fa-star"></i>
                        @elseif ($i == 5)
                            <i class="fas fa-star-half-alt"></i>
                        @endif
                    @endfor
                    <span class="ms-2 text-muted">4.5 dari 128 ulasan</span>
                </div>

                <h4 class="text-primary fw-bold">Rp {{ number_format($product->harga_barang, 0, ',', '.') }}</h4>
                <p class="mt-3 text-dark">{{ $product->deskripsi }}</p>

                <form action="{{ route('customer.addToCart') }}" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <input type="hidden" name="name" value="{{ $product->nama_barang }}">
                    <input type="hidden" name="price" value="{{ $product->harga_barang }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-outline-primary btn-lg w-100 mb-2">
                        <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                    </button>
                </form>

                <a href="https://wa.me/6285806405660?text=Halo, saya ingin membeli produk {{ $product->nama_barang }}. Apakah produk ini tersedia?"
                    target="_blank" class="btn btn-success btn-lg w-100">
                    <i class="fab fa-whatsapp"></i> Beli Lewat WhatsApp
                </a>
            </div>
        </div>

        <!-- Produk Terkait -->
        <div class="mt-5">
            <h3 class="fw-bold mb-4" style="color: #112D4E;">Produk Terkait</h3>
            <div class="row g-4">
                @forelse ($relatedProducts as $related)
                    <div class="col-md-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <img src="{{ asset('storage/files/' . $related->encrypted_filename) }}" class="card-img-top"
                                alt="{{ $related->nama_barang }}" style="height: 180px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h6 class="fw-bold" style="color: #112D4E;">{{ $related->nama_barang }}</h6>
                                <p class="text-primary fw-bold mb-2">Rp
                                    {{ number_format($related->harga_barang, 0, ',', '.') }}</p>
                                <a href="{{ route('catalog.detail', $related->id) }}"
                                    class="btn btn-outline-secondary btn-sm w-100 mt-auto">
                                    <i class="fas fa-info-circle me-1"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-muted">Tidak ada produk terkait.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        .btn-outline-primary {
            border-color: #3F72AF;
            color: #3F72AF;
        }

        .btn-outline-primary:hover {
            background-color: #3F72AF;
            color: #fff;
        }

        .btn-outline-secondary {
            border-color: #112D4E;
            color: #112D4E;
        }

        .btn-outline-secondary:hover {
            background-color: #112D4E;
            color: #fff;
        }
    </style>
@endsection
