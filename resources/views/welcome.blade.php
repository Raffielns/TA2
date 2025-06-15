@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="hero-section position-relative text-white"
    style="
        background-image: linear-gradient(to bottom right, rgba(0,0,0,0.7), rgba(0,0,0,0.6)), url('{{ asset('img/bg-dashboard.jpg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        height: 500px;
        display: flex;
        align-items: center;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="display-4 fw-bold mb-4">Premium Seals for Industrial Excellence</h1>
                <p class="lead mb-4">Produk seal dengan komposisi material pilihan, dirancang untuk ketahanan optimal dan disesuaikan dengan kebutuhan industri Anda.</p>
                <div class="d-flex" style="gap :1rem">
                    <a href="#products" class="btn btn-danger btn-lg px-4 shadow rounded-pill">Produk Kami</a>
                    <a href="#contact" class="btn btn-outline-light btn-lg px-4 rounded-pill">Hubungi Kami</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section Animations & Hover Effects -->
<style>
  .product-card, .card:hover {
    transition: all 0.4s ease-in-out;
  }
  .product-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 12px 20px rgba(0,0,0,0.2);
  }
  .card-body svg {
    transition: transform 0.3s ease;
  }
  .card:hover svg {
    transform: scale(1.2);
  }
  .hero-section h1, .hero-section p {
    animation: fadeInUp 1s ease-out;
  }
  @keyframes fadeInUp {
    0% { opacity: 0; transform: translateY(20px); }
    100% { opacity: 1; transform: translateY(0); }
  }
</style>

<!-- Kategori Produk Seal Section -->
@include('partials.kategoriprodukseal')

<!-- Kategori Pengerjaan Lainnya -->
@include('partials.kategorilainnya')

<!-- Industri yang Dilayani -->
@include('partials.industries')

<!-- Testimoni -->
@include('partials.testimonials')

<!-- Call to Action -->
@include('partials.cta')

<!-- Kontak -->
@include('partials.contact')

@endsection
