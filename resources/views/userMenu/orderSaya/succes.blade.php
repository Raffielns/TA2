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
                            {{ $order->payment_method }}
                        </p>
                        @if ($order->payment_method == 'transfer')
                            <p><strong>Status Pembayaran:</strong>
                                <span class="badge badge-warning">Menunggu Verifikasi</span>
                            </p>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('order.status') }}" class="btn btn-primary px-4">
                        <i class="fas fa-history mr-2"></i> Lihat Riwayat Pesanan
                    </a>
                    <a href="{{ route('catalog') }}" class="btn btn-outline-secondary px-4 me-3">
                        <i class="fas fa-shopping-bag mr-2"></i> Lanjut Belanja
                    </a>
                    @if ($order->payment_method === 'midtrans')
                        <button type="button" class="btn btn-warning btn-sm" id="pay-button">
                            Bayar Sekarang
                        </button>
                    @endif

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

@section('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function() {
            // SnapToken acquired from previous step
            snap.pay('{{ $order->snap_token }}', {
                // Optional
                onSuccess: function(result) {
                    /* You may add your own js here, this is just example */
                    document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                },
                // Optional
                onPending: function(result) {
                    /* You may add your own js here, this is just example */
                    document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                },
                // Optional
                onError: function(result) {
                    /* You may add your own js here, this is just example */
                    document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                }
            });
        };
    </script>
@endsection
