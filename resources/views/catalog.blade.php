@php use Illuminate\Support\Str; @endphp

@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-white p-3 rounded shadow-sm">
                <li class="breadcrumb-item"><a href="{{ route('landing') }}"><i class="fas fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active">Produk</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Urutkan</h5>
                        @foreach (['Pengurutan standar', 'Urutkan berdasar tren', 'Urutkan berdasar rating', 'Urutkan terbaru', 'Urutkan termurah', 'Urutkan termahal'] as $index => $label)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sort" id="sort{{ $index }}"
                                    {{ $index === 3 ? 'checked' : '' }}>
                                <label class="form-check-label" for="sort{{ $index }}">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Harga</h5>
                        <input type="range" class="form-range" min="10000" max="10000000">
                        <p>Harga: Rp275.000 – Rp4.800.000</p>
                        <button class="btn btn-dark w-100">Saring</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Kategori</h5>
                        <ul class="list-unstyled">
                            @foreach ($allCategories as $cat)
                                <li>
                                    <a href="{{ route('catalog.byCategory', ['slug' => $cat->slug]) }}"
                                        class="text-decoration-none">
                                        {{ $cat->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Produk -->
            <div class="col-md-9">
                <h3 class="mb-4">Menampilkan {{ $products->count() }} produk</h3>
                <div class="row g-4">
                    @forelse ($products as $product)
                        {{-- penambahan notifikasi sukses ditambahkan --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <img src="{{ asset('storage/files/' . $product->encrypted_filename) }}"
                                    alt="{{ $product->nama_barang }}" class="card-img-top"
                                    style="height: 200px; object-fit: cover;">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="fw-bold">{{ $product->nama_barang }}</h6>
                                    <span
                                        class="badge custom-badge mb-2">{{ $product->category->name ?? 'Tanpa Kategori' }}</span>
                                    <p class="text-muted small mb-1">{{ Str::limit($product->deskripsi, 60) }}</p>
                                    <p class="text-danger fw-bold mb-3">Rp
                                        {{ number_format($product->harga_barang, 0, ',', '.') }}</p>

                                    <div class="mt-auto d-grid gap-3">
                                        <form action="{{ route('customer.addToCart') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $product->id }}">
                                            <input type="hidden" name="name" value="{{ $product->nama_barang }}">
                                            <input type="hidden" name="price" value="{{ $product->harga_barang }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <input type="hidden" name="color" value="Hitam">
                                            <input type="hidden" name="size" value="M">
                                            <button type="submit" class="btn custom-btn-cart w-100">
                                                <i class="fas fa-cart-plus me-1"></i> Tambah ke Keranjang
                                            </button>
                                        </form>
                                        <a href="{{ route('catalog.detail', $product->id) }}"
                                            class="btn custom-btn-detail w-100">
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
                                            <img src="{{ asset('storage/files/' . $product->encrypted_filename) }}"
                                                alt="" class="img-fluid rounded">
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Kategori:</strong> {{ $product->category->name ?? '-' }}</p>
                                            <p><strong>Harga:</strong> Rp
                                                {{ number_format($product->harga_barang, 0, ',', '.') }}</p>
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
        </div>
    </div>

    <style>
        .custom-btn-cart {
            background-color: #1f2731;
            color: #fff;
            border: none;
            padding: 10px 12px;
            font-size: 14px;
            border-radius: 6px;
        }

        .custom-btn-cart:hover {
            background-color: #3a4048;
            color: #fff;
        }

        .custom-btn-detail {
            background-color: transparent;
            color: #1f2731;
            border: 1.5px solid #1f2731;
            padding: 10px 12px;
            font-size: 14px;
            border-radius: 6px;
        }

        .custom-btn-detail:hover {
            background-color: #1f2731;
            color: white;
        }

        .custom-badge {
            background-color: #1f2731;
            color: white;
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 8px;
        }

        .card-body .d-grid {
            gap: rem;
        }
    </style>
@endsection
