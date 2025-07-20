@extends('layouts.app')

@section('content')
    <!-- Header dengan Breadcrumb -->
    <div class="bg-dark py-4">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-white p-3 rounded shadow-sm">
                    <li class="breadcrumb-item"><a href="{{ route('landing') }}" class="text-decoration-none"><i
                                class="fas fa-home"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('catalog') }}" class="text-decoration-none">Katalog
                            Produk</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->nama_barang }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            <!-- Gallery Produk -->
            <div class="col-lg-6 mb-4">
                <div class="card border-2 shadow-sm rounded-lg overflow-hidden">
                    <img id="mainImage" src="{{ asset('storage/files/' . $product->encrypted_filename) }}"
                        class="img-fluid w-100"
                        style="height: 400px; object-fit: contain;">
                </div>

                <!-- Thumbnail Gallery -->
                <div class="d-flex gap-2 mt-3 flex-wrap">
                    @foreach ($product->images as $image)
                        <div class="thumbnail-container"
                            onclick="document.getElementById('mainImage').src='{{ asset('storage/' . $image->image_path) }}'">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="img-thumbnail rounded"
                                style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Info Produk -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-lg h-100">
                    <div class="card-body">
                        <!-- Header Produk -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h2 class="fw-bold mb-2">{{ $product->nama_barang }}</h2>
                                <span class="badge bg-primary mb-3">
                                    {{ $product->category->name ?? 'Tanpa Kategori' }}
                                </span>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block"><i class="fas fa-eye"></i> {{ $product->views }}
                                    dilihat</small>
                                <small class="text-muted d-block"><i class="fas fa-shopping-bag"></i> {{ $product->sold }}
                                    terjual</small>
                            </div>
                        </div>

                        <!-- Harga -->
                        <div class="mb-4">
                            <h3 class="text-danger fw-bold">Rp {{ number_format($product->harga_barang, 0, ',', '.') }}
                            </h3>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">Deskripsi Produk</h5>
                            <p class="text-dark">{{ $product->deskripsi }}</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-3 mt-auto">
                            <form action="{{ route('customer.addToCart') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <input type="hidden" name="name" value="{{ $product->nama_barang }}">
                                <input type="hidden" name="price" value="{{ $product->harga_barang }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn custom-btn-cart w-100 py-2 mb-2">
                                    <i class="fas fa-cart-plus me-2"></i> Tambah ke Keranjang
                                </button>
                            </form>

                            <a href="https://wa.me/6285806405660?text=Halo, saya ingin membeli produk {{ $product->nama_barang }}. Apakah produk ini tersedia?"
                                target="_blank" class="btn btn-success w-100 py-2">
                                <i class="fab fa-whatsapp me-2"></i> Beli Lewat WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ulasan Pelanggan -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-lg">
                    <div class="card-body">
                        <h3 class="fw-bold mb-4"><i class="fas fa-star text-warning me-2"></i>Ulasan Pelanggan</h3>

                        @if ($product->reviews->count() > 0)
                            <div class="row">
                                <!-- Rating Summary -->
                                <div class="col-md-4 mb-4">
                                    <div class="text-center p-4 bg-light rounded">
                                        <h2 class="fw-bold text-primary">
                                            {{ number_format($product->reviews->avg('rating'), 1) }}/5</h2>
                                        <div class="mb-3">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($product->reviews->avg('rating')))
                                                    <i class="fas fa-star text-warning"></i>
                                                @elseif ($i - $product->reviews->avg('rating') < 1)
                                                    <i class="fas fa-star-half-alt text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <p class="mb-0">Berdasarkan {{ $product->reviews->count() }} ulasan</p>
                                    </div>
                                </div>

                                <!-- Daftar Ulasan -->
                                <div class="col-md-8">
                                    @foreach ($product->reviews->sortByDesc('created_at')->take(5) as $review)
                                        <div class="review-item mb-4 pb-3 border-bottom">
                                            <div class="d-flex justify-content-between mb-2">
                                                <div>
                                                    <strong>{{ $review->user->name }}</strong>
                                                    <div class="text-warning small">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= $review->rating)
                                                                <i class="fas fa-star"></i>
                                                            @else
                                                                <i class="far fa-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                </div>
                                                <small
                                                    class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
                                            </div>
                                            <p class="mb-0">{{ $review->review }}</p>
                                        </div>
                                    @endforeach

                                    @if ($product->reviews->count() > 5)
                                        <div class="text-center mt-3">
                                            <a href="#" class="text-decoration-none">Lihat semua ulasan</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-comment-slash fa-3x text-muted mb-3"></i>
                                <h5 class="fw-bold">Belum ada ulasan</h5>
                                <p class="text-muted">Jadilah yang pertama memberikan ulasan untuk produk ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Produk Terkait -->
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="fw-bold mb-4">Produk Terkait</h3>

                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
                    @forelse ($relatedProducts as $related)
                        <div class="col">
                            <div class="card h-100 border-2 shadow-sm product-card">
                                <div class="position-relative overflow-hidden" style="height: 180px;">
                                    <img src="{{ asset('storage/files/' . $related->encrypted_filename) }}"
                                        class="card-img-top h-100 object-fit-contain" alt="{{ $related->nama_barang }}">
                                    <div class="product-badge">
                                        <span
                                            class="badge bg-dark">{{ $related->category->name ?? 'Uncategorized' }}</span>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h6 class="fw-bold mb-1">{{ $related->nama_barang }}</h6>
                                    <p class="text-danger fw-bold mb-2">Rp
                                        {{ number_format($related->harga_barang, 0, ',', '.') }}</p>
                                    <a href="{{ route('catalog.detail', $related->id) }}"
                                        class="btn custom-btn-detail mt-auto">
                                        <i class="fas fa-eye me-1"></i> Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center">Tidak ada produk terkait.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --primary-color: #141E46;
            --secondary-color: #2E8B57;
            --accent-color: #FF6969;
            --light-color: #FFF5E0;
        }

        /* Gallery Thumbnail */
        .thumbnail-container {
            transition: transform 0.3s ease;
            border-radius: 8px;
            overflow: hidden;
        }

        .thumbnail-container:hover {
            transform: scale(1.05);
        }

        .img-thumbnail {
            border: 2px solid #f8f9fa;
            transition: border-color 0.3s ease;
        }

        .img-thumbnail:hover {
            border-color: var(--primary-color);
        }

        /* Review Section */
        .review-item {
            transition: background-color 0.3s ease;
            padding: 15px;
            border-radius: 8px;
        }

        .review-item:hover {
            background-color: #f9f9f9;
        }

        /* Tombol Tambah ke Keranjang */
        .custom-btn-cart {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px;
            font-size: 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .custom-btn-cart:hover {
            background-color: #0d1633;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(20, 30, 70, 0.2);
        }

        /* Tombol Lihat Detail */
        .custom-btn-detail {
            background-color: white;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            padding: 8px 12px;
            font-size: 14px;
            border-radius: 8px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .custom-btn-detail:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(20, 30, 70, 0.2);
        }

        /* Product Card */
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .product-badge {
            position: relative;
            width: 100%;
            padding-top: 100%;
            left: 10px;
            z-index: 2;
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
            color: white !important;
        }
    </style>

@endsection
