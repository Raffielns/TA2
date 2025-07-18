<div class="py-5" style="background-color: var(--light-color);">
    <div class="container px-3">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark mb-3">Produk Terbaru</h2>
            <p class="text-muted">Temukan produk kami yang sesuai dengan kebutuhan Anda!</p>
        </div>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4" id="product-list">
            @forelse ($products->take(12) as $index => $product)
                <div class="col {{ $index >= 8 ? 'd-none extra-product' : '' }}">
                    <div class="card border-0 shadow-sm rounded-lg overflow-hidden h-100 product-card">
                        <!-- Product Image with Quick Actions -->
                        <div class="position-relative product-image-container" style="height: 240px; width:100%;">
                            <img src="{{ asset('storage/files/' . $product->encrypted_filename) }}"
                                class="img-fluid w-100 h-100 object-fit-contain" alt="{{ $product->nama_barang }}">
                            {{-- style="height: 200px; width: 100%;"> --}}

                            <!-- Quick Actions (shown on hover) -->
                            <div class="product-actions d-flex flex-column justify-content-center align-items-center">
                                <a href="{{ route('catalog.detail', $product->id) }}"
                                    class="btn btn-sm btn-light rounded-circle mb-2 quick-action-btn"
                                    data-bs-toggle="tooltip" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <form action="{{ route('customer.addToCart') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $product->id }}">
                                    <input type="hidden" name="name" value="{{ $product->nama_barang }}">
                                    <input type="hidden" name="price" value="{{ $product->harga_barang }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-sm btn-light rounded-circle quick-action-btn"
                                        data-bs-toggle="tooltip" title="Tambah ke Keranjang">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </form>
                            </div>

                            <!-- Category Badge -->
                            <div class="product-badge">
                                <span class="badge">
                                    {{ $product->category->name ?? 'Tanpa Kategori' }}
                                </span>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="card-body d-flex flex-column">
                            <h6 class="fw-bold mb-1 text-truncate">{{ $product->nama_barang }}</h6>
                            <p class="text-danger fw-bold mb-3">Rp
                                {{ number_format($product->harga_barang, 0, ',', '.') }}</p>

                            <div class="mt-auto d-grid">
                                <a href="{{ route('catalog.detail', $product->id) }}" class="btn custom-btn-detail">
                                    <i class="fas fa-info-circle me-1"></i> Detail Produk
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center py-4">
                        <i class="fas fa-box-open fa-2x mb-3"></i>
                        <p class="mb-0">Belum ada produk yang tersedia.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if ($products->count() > 8)
            <div class="text-center mt-5">
                <button class="btn btn-outline-primary rounded-pill px-4 py-2" id="load-more-btn">
                    <i class="fas fa-chevron-down me-2"></i> Tampilkan Lebih Banyak
                </button>
            </div>
        @endif
    </div>
</div>

<style>
    :root {
        --primary-color: #141E46;
        --secondary-color: #2E8B57;
        --accent-color: #FF6969;
    }

    /* Product Card */
    .product-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 20px;
        /* Jarak antar box */
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    /* Product Image Container */
    .product-image-container {
        position: relative;
        overflow: hidden;
        background-color: #f8f9fa;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .product-image-container img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        padding: 10px;
        object-position: center;
        transition: transform 0.5s ease;
    }

    .product-card:hover .product-image-container img {
        transform: scale(1.05);
    }

    /* Product Badge */
    .product-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 2;
    }

    .product-badge .badge {
        background-color: var(--primary-color);
        color: white;
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 50px;
    }

    /* Product Actions (on hover) */
    .product-actions {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(20, 30, 70, 0.7);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .product-card:hover .product-actions {
        opacity: 1;
    }

    .quick-action-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        background-color: rgba(255, 255, 255, 0.9);
    }

    .quick-action-btn:hover {
        background-color: var(--primary-color) !important;
        color: white !important;
        transform: scale(1.1);
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
    }

    .custom-btn-detail:hover {
        background-color: var(--primary-color);
        color: white;
        transform: translateY(-2px);
    }

    /* Spacing antar produk */
    #product-list .col {
        margin-bottom: 24px;
    }

    /* Responsive Adjustments */
    @media (max-width: 767.98px) {
        .product-actions {
            opacity: 1;
            background-color: transparent;
            top: auto;
            bottom: 10px;
            height: auto;
        }

        .quick-action-btn {
            width: 30px;
            height: 30px;
            font-size: 12px;
        }

        .product-image-container img {
            height: 180px;
        }
    }
</style>

<script>
    document.getElementById('load-more-btn')?.addEventListener('click', function() {
        document.querySelectorAll('.extra-product').forEach(item => {
            item.classList.remove('d-none');
            item.classList.add('animate__animated', 'animate__fadeIn');
        });
        this.style.display = 'none';
    });
</script>
