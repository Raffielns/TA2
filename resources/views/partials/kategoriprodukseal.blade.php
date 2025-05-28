<!-- Kategori Produk Seal -->
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

<div class="py-5" style="background-color: #F9F7F7; position: relative;">
    <div class="container">
        <h2 class="fw-bold text-center mb-4" style="color: #112D4E;">Kategori Produk Seal</h2>

        <!-- Swiper Wrapper -->
        <div class="position-relative px-5">
            <div class="swiper kategoriSwiper">
                <div class="swiper-wrapper">
                    @php use Illuminate\Support\Str; @endphp
                    @foreach ($allCategories as $cat)
                        <div class="swiper-slide">
                            <a href="{{ route('catalog.byCategory', ['slug' => $cat->slug]) }}"
                                class="card border-0 text-white shadow rounded-4 overflow-hidden category-card"
                                style="background-image: url('{{ asset('img/' . ($cat->image ?? 'default.png')) }}');">
                                <span class="category-badge">{{ $cat->products_count }} Produk</span>
                                <div class="category-overlay"></div>
                                <div class="category-name">{{ $cat->name }}</div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Navigasi Panah -->
            <div class="swiper-button-prev custom-swiper-btn"></div>
            <div class="swiper-button-next custom-swiper-btn"></div>
        </div>
    </div>
</div>


<!-- Swiper JS -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    new Swiper('.kategoriSwiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            576: {
                slidesPerView: 2
            },
            768: {
                slidesPerView: 3
            },
            992: {
                slidesPerView: 4
            }
        }
    });
</script>

<!-- Style -->
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
        background-color: #bfbcd6;
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
        background: linear-gradient(to top, rgba(0, 0, 0, 0.6), transparent);
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

    .custom-swiper-btn {
        color: white;
        background-color: #bfbcd6;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        top: 35%;
        transform: translateY(-50%);
        position: absolute;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .swiper-button-next.custom-swiper-btn {
        right: -30px;
    }

    .swiper-button-prev.custom-swiper-btn {
        left: -30px;
    }

    .custom-swiper-btn::after {
        font-size: 18px;
        font-weight: bold;
    }
</style>
