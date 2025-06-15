@extends('layouts.user')

@section('main-content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-eye mr-2"></i> Review Anda
                        </h3>
                    </div>

                    <div class="card-body">
                        <div class="mb-4">
                            <h5>Produk: {{ $product->nama_barang }}</h5>
                            <p>No. Pesanan: #{{ $order->order_number }}</p>
                            <p>Tanggal Pesanan: {{ $order->created_at->format('d M Y H:i') }}</p>
                            <p>Tanggal Review: {{ $review->created_at->format('d M Y H:i') }}</p>
                        </div>

                        <div class="form-group">
                            <label>Rating</label>
                            <div class="d-flex">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="mr-2 text-warning" style="font-size: 1.5rem;">
                                        @if ($i <= $review->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    </span>
                                @endfor
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Ulasan</label>
                            <div class="border p-3 bg-light rounded">
                                {{ $review->review }}
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <a href="{{ route('order.history') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Riwayat
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
