<div class="py-5" style="background-color: #F9F7F7;">
    <div class="container">
        <div class="text-center mb-3">
            <h2 class="fw-bold" style="color: #112D4E;">Kategori Pengerjaan Lainnya</h2>
            <p class="mt-2" style="color: #000000;">Kami juga menerima pengerjaan pembuatan besi dan lain-lain.</p>
        </div>

        <div class="row justify-content-center g-4">
            <!-- Kategori 1 -->
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('catalog') }}" class="card border-0 text-white shadow rounded-4 overflow-hidden category-card"
                   style="background-image: url('{{ asset('img/komponen-hidrolik-pneumatik.png') }}');">
                    <span class="category-badge">5 Produk</span>
                    <div class="category-overlay"></div>
                    <div class="category-name">Komponen Hidrolik & Pneumatic</div>
                </a>
            </div>
            <!-- Kategori 2 -->
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('catalog') }}" class="card border-0 text-white shadow rounded-4 overflow-hidden category-card"
                   style="background-image: url('{{ asset('img/komponen-mekanik-bengkel.png') }}');">
                    <span class="category-badge">4 Produk</span>
                    <div class="category-overlay"></div>
                    <div class="category-name">Komponen Per Mekanik</div>
                </a>
            </div>
        </div>
    </div>

    <style>
        .category-card {
            background-size: cover;
            background-position: center;
            height: 220px;
            position: relative;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        }

        .category-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background-color: #6A5ACD;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.8rem;
            z-index: 2;
        }

        .category-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60%;
            background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);
            z-index: 1;
        }

        .category-name {
            position: absolute;
            bottom: 16px;
            left: 16px;
            font-weight: bold;
            font-size: 1.2rem;
            z-index: 2;
        }
    </style>
</div>
