<div class="py-5" style="background-color: #F9F7F7;">
    <div class="container px-3">
        <div class="text-center mb-4">
            <h2 class="fw-bold" style="color: #112D4E;">Produk Terbaru</h2>
            <p class="mt-2" style="color: #000000;">Temukan produk kami yang sesuai dengan kebutuhan anda!</p>
        </div>

        <div class="row justify-content-center g-4" id="product-list">
            @forelse ($products->take(12) as $index => $product)
                <div class="col-md-3 col-sm-6 mb-4 {{ $index >= 8 ? 'd-none extra-product' : '' }}">
                    <a href="{{ route('catalog.detail', $product->id) }}"
                       class="card border-0 shadow-sm rounded-4 overflow-hidden product-card text-decoration-none text-dark position-relative d-block">
                        <div class="product-img" style="background-image: url('{{ asset('storage/files/' . $product->encrypted_filename) }}');"></div>

                        <!-- Hover Button -->
                        <div class="hover-overlay d-flex justify-content-center align-items-center">
                            <form action="{{ route('customer.addToCart') }}" method="POST" class="w-100">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <input type="hidden" name="name" value="{{ $product->nama_barang }}">
                                <input type="hidden" name="price" value="{{ $product->harga_barang }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-dark rounded-0 fw-semibold w-100 py-2">TAMBAH KE KERANJANG</button>
                            </form>
                        </div>

                        <div class="product-body text-center p-3 bg-white">
                            <h6 class="fw-bold mb-1">{{ $product->nama_barang }}</h6>
                            <p class="text-muted mb-0">Rp{{ number_format($product->harga_barang, 0, ',', '.') }}</p>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">Belum ada produk yang tersedia.</div>
                </div>
            @endforelse
        </div>

        @if ($products->count() > 8)
        <div class="text-center mt-4">
            <button class="btn btn-outline-secondary rounded-pill px-4 py-2" id="load-more-btn">Tampilkan Lebih Banyak</button>
        </div>
        @endif
    </div>

    <style>
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .product-img {
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            height: 220px;
            background-color: #ffffff;
        }

        .hover-overlay {
            position: absolute;
            top: 220px;
            left: 0;
            width: 100%;
            height: 45px;
            background-color: #000;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 5;
        }

        .product-card:hover .hover-overlay {
            opacity: 1;
        }

        .hover-overlay button {
            color: #fff;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .hover-overlay {
                height: 40px;
                top: 200px;
            }
        }
    </style>

    <script>
        document.getElementById('load-more-btn')?.addEventListener('click', function () {
            document.querySelectorAll('.extra-product').forEach(item => item.classList.remove('d-none'));
            this.style.display = 'none';
        });
    </script>
</div>
