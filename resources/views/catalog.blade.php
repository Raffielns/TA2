@php use Illuminate\Support\Str; @endphp

@extends('layouts.app')

@section('content')
    <div class="container py-5">

        <!-- navigasi -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-white p-3 rounded shadow-sm">
                <li class="breadcrumb-item"><a href="{{ route('landing') }}"><i class="fas fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active">Produk</li>
            </ol>
        </nav>

        {{-- notifikasi sukses --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Kategori Produk di atas grid produk -->
        <div class="mb-4 text-center">
            <h4 class="fw-bold mb-3">Kategori Produk</h4>
            <div class="d-flex flex-wrap justify-content-center gap-3"> <!-- gap lebih besar -->
                @foreach ($allCategories as $cat)
                    <a href="{{ route('catalog.byCategory', ['slug' => $cat->slug]) }}"
                        class="category-pill text-decoration-none">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Produk -->
        <h3 class="mb-4">Menampilkan {{ $products->count() }} produk</h3>
        <div class="row g-4">
            @forelse ($products as $product)
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm product-card position-relative">
                        <!-- Kategori di atas gambar -->
                        <span class="badge custom-badge position-absolute top-0 start-0 m-2">
                            {{ $product->category->name ?? 'Tanpa Kategori' }}
                        </span>

                        <img src="{{ asset('storage/files/' . $product->encrypted_filename) }}" class="card-img-top"
                            alt="{{ $product->nama_barang }}" data-bs-toggle="modal"
                            data-bs-target="#previewModal{{ $product->id }}">

                        <div class="card-body d-flex flex-column">
                            <h6 class="fw-bold">{{ $product->nama_barang }}</h6>
                            <p class="text-muted small mb-1">{{ Str::limit($product->deskripsi, 60) }}</p>
                            <p class="text-danger fw-bold mb-3">
                                Rp {{ number_format($product->harga_barang, 0, ',', '.') }}
                            </p>

                            <div class="mt-auto d-grid gap-3">
                                <form action="{{ route('customer.addToCart') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $product->id }}">
                                    <input type="hidden" name="name" value="{{ $product->nama_barang }}">
                                    <input type="hidden" name="price" value="{{ $product->harga_barang }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" name="color" value="Hitam">
                                    <input type="hidden" name="size" value="M">
                                    <button type="submit" class="btn custom-btn-cart w-100 mb-2">
                                        <i class="fas fa-cart-plus me-1"></i> Tambah ke Keranjang
                                    </button>
                                </form>
                                <a href="{{ route('catalog.detail', $product->id) }}" class="btn custom-btn-detail w-100">
                                    <i class="fas fa-info-circle me-1"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Preview -->
                <div class="modal fade" id="previewModal{{ $product->id }}" tabindex="-1"
                    aria-labelledby="previewModalLabel{{ $product->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="previewModalLabel{{ $product->id }}">
                                    {{ $product->nama_barang }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body row">
                                <div class="col-md-6">
                                    <img src="{{ asset('storage/files/' . $product->encrypted_filename) }}" alt=""
                                        class="img-fluid rounded">
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Kategori:</strong> {{ $product->category->name ?? '-' }}</p>
                                    <p><strong>Harga:</strong> Rp {{ number_format($product->harga_barang, 0, ',', '.') }}
                                    </p>
                                    <p class="text-muted">{{ $product->deskripsi }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">Belum ada produk yang tersedia.</div>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        :root {
            --primary-color: #141E46;
            --secondary-color: #2E8B57;
            --accent-color: #FF6969;
            --light-color: #FFF5E0;
        }

        /* Kategori Pills */
        .category-pill {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 20px;
            /* lebih tebal agar nyaman */
            border-radius: 25px;
            /* lebih bulat */
            transition: all 0.3s ease;
            font-size: 14px;
            margin: 4px;
            /* tambahan jarak antar pill */
            display: inline-block;
        }

        .category-pill:hover {
            background-color: #0d1633;
            color: #fff;
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

        .custom-badge {
            background-color: var(--primary-color);
            color: white;
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 12px;
        }

        .card-body h6.fw-bold {
            font-weight: 600;
            color: #333;
        }

        /* Tombol */
        .custom-btn-cart {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 12px;
            font-size: 14px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .custom-btn-cart:hover {
            background-color: #0d1633;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(20, 30, 70, 0.2);
        }

        .custom-btn-detail {
            background-color: white;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            padding: 8px 12px;
            font-size: 14px;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .custom-btn-detail:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(20, 30, 70, 0.2);
        }

        /* Breadcrumb Navigasi */
        .breadcrumb {
            background-color: #f8f9fa !important;
            border-left: 4px solid var(--primary-color);
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            font-weight: 500;
        }

        .breadcrumb-item.active {
            color: #6c757d;
            font-weight: 500;
        }
    </style>
@endsection
