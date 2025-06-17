<div class="py-5" style="background-color: #F9F7F7;">
    <div class="container px-3">
        <div class="text-center mb-4">
            <h2 class="fw-bold" style="color: #112D4E;">Produk Terbaru</h2>
            <p class="mt-2" style="color: #000000;">Temukan produk kami yang sesuai dengan kebutuhan anda!</p>
        </div>

        <div class="row justify-content-center g-4" id="product-list">
            @foreach ([
                ['image' => 'komponen-hidrolik-pneumatik.png', 'title' => 'Komponen Hidrolik dan Pneumatik', 'price' => 'Rp1.200.000'],
                ['image' => 'komponen-karet-industri.png', 'title' => 'Komponen Karet Industri', 'price' => 'Rp1.900.000'],
                ['image' => 'ring-dan-sejenisnya.png', 'title' => 'Ring dan Sejenisnya', 'price' => 'Rp2.200.000'],
                ['image' => 'komponen-mekanik-bengkel.png', 'title' => 'Komponen Mekanik dan Bengkel', 'price' => 'Rp700.000'],
                ['image' => 'seal-industri-umum.png', 'title' => 'Seal Industri Umum', 'price' => 'Rp400.000'],
                ['image' => 'komponen-hidrolik-pneumatik.png', 'title' => 'Komponen Hidrolik dan Pneumatik', 'price' => 'Rp1.200.000'],
                ['image' => 'komponen-hidrolik-pneumatik.png', 'title' => 'Komponen Hidrolik dan Pneumatik', 'price' => 'Rp1.200.000'],
                ['image' => 'komponen-hidrolik-pneumatik.png', 'title' => 'Komponen Hidrolik dan Pneumatik', 'price' => 'Rp1.200.000'],
                ['image' => 'komponen-hidrolik-pneumatik.png', 'title' => 'Komponen Hidrolik dan Pneumatik', 'price' => 'Rp1.200.000'],
                ['image' => 'komponen-hidrolik-pneumatik.png', 'title' => 'Komponen Hidrolik dan Pneumatik', 'price' => 'Rp1.200.000'],
                ['image' => 'komponen-hidrolik-pneumatik.png', 'title' => 'Komponen Hidrolik dan Pneumatik', 'price' => 'Rp1.200.000'],
                ['image' => 'komponen-hidrolik-pneumatik.png', 'title' => 'Komponen Hidrolik dan Pneumatik', 'price' => 'Rp1.200.000'],
            ] as $product)
            <div class="col-md-3 col-sm-6 mb-4 {{ $loop->iteration > 8 ? 'd-none' : '' }}" data-product="{{ $loop->iteration }}">
                <div class="card border-0 shadow rounded-4 overflow-hidden product-card position-relative">
                    <div class="product-img" style="background-image: url('{{ asset('img/' . $product['image']) }}');"></div>

                    <!-- Hover button -->
                    <div class="hover-overlay d-flex justify-content-center align-items-center">
                        <button class="btn btn-dark rounded-pill px-4 py-2 fw-semibold">Tambah Ke Keranjang</button>
                    </div>

                    <a href="{{ route('catalog') }}" class="stretched-link"></a>
                    <div class="product-body text-center p-3 bg-white">
                        <h6 class="fw-bold" style="color: #112D4E;">{{ $product['title'] }}</h6>
                        <p class="text-muted mb-0">{{ $product['price'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Button tampilkan lebih banyak -->
        <div class="text-center mt-4">
            <button class="btn btn-outline-secondary rounded-pill px-4 py-2" id="load-more-btn">Tampilkan Lebih Banyak</button>
        </div>
    </div>

    <style>
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
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
            top: 0;
            left: 0;
            width: 100%;
            height: 220px;
            background: rgba(0, 0, 0, 0.4);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 2;
        }

        .product-card:hover .hover-overlay {
            opacity: 1;
        }
    </style>

    <script>
        document.getElementById('load-more-btn').addEventListener('click', function () {
            const hiddenProducts = document.querySelectorAll('.product-card.d-none');
            hiddenProducts.forEach(item => item.classList.remove('d-none'));
            this.style.display = 'none';
        });
    </script>
</div>
