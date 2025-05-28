@extends('layouts.user')

@section('main-content')
    <div class="container py-5">
        <div class="card shadow-lg border-0">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-check-circle text-success fa-5x"></i>
                </div>
                <h2 class="font-weight-bold text-success mb-3">Pembayaran Berhasil!</h2>
                <p class="lead mb-4">Terima kasih atas pesanan Anda. Pesanan Anda sedang diproses.</p>

                <div class="card mb-4 mx-auto" style="max-width: 500px;">
                    <div class="card-body text-left">
                        <h5 class="card-title">Detail Pesanan</h5>
                        <p><strong>No. Pesanan:</strong> {{ $order->order_number }}</p>
                        <p><strong>Total Pembayaran:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        <p><strong>Metode Pembayaran:</strong>
                            {{ $order->payment_method == 'cod' ? 'COD (Bayar di Tempat)' : 'Transfer Bank' }}
                        </p>
                        @if ($order->payment_method == 'transfer')
                            <p><strong>Status Pembayaran:</strong>
                                <span class="badge badge-warning">Menunggu Verifikasi</span>
                            </p>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('order.history') }}" class="btn btn-primary px-4">
                        <i class="fas fa-history mr-2"></i> Lihat Riwayat Pesanan
                    </a>
                    <a href="{{ route('catalog') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-shopping-bag mr-2"></i> Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .success-animation {
            animation: bounce 1s ease infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        .order-card {
            border-left: 4px solid #28a745;
        }
    </style>
@endsection
