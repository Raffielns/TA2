@extends('layouts.user')

@section('main-content')
    <div class="container py-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><i class="fas fa-clipboard-list mr-2"></i> Status Pesanan</h3>
            </div>

            <div class="card-body">
                @if ($orders->isEmpty())
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i> Anda belum memiliki pesanan.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No. Pesanan</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>#{{ $order->order_number }}</td>
                                        {{-- <td>
                                            <img src="{{ asset('storage/files/' . $item->product->encrypted_filename) }}"
                                                alt="" class="img-fluid rounded" style="max-height: 80px;">
                                        </td>
                                        <td class="text-start">{{ $product->nama_barang }}</td> --}}
                                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($order->payment_method == 'cod')
                                                <span class="badge badge-secondary">COD</span>
                                            @elseif($order->payment_method == 'transfer')
                                                <span class="badge badge-info">Transfer Bank</span>
                                            @else
                                                <span
                                                    class="badge badge-primary">{{ ucfirst($order->payment_method) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusClass =
                                                    [
                                                        'pending' => 'warning',
                                                        'diproses' => 'info',
                                                        'dikirim' => 'primary',
                                                        'selesai' => 'success',
                                                        'dibatalkan' => 'danger',
                                                        'gagal' => 'dark',
                                                    ][$order->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge badge-{{ $statusClass }}">
                                                {{ ucfirst($order->status) }}
                                                @if ($order->payment_status)
                                                    ({{ ucfirst($order->payment_status) }})
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                @if ($order->payment_method == 'midtrans' && $order->status == 'pending')
                                                    <button class="pay-button btn btn-primary btn-sm"
                                                        data-snap-token="{{ $order->snap_token }}"
                                                        data-order-id="{{ $order->id }}">
                                                        <i class="fas fa-credit-card"></i> Pay Now
                                                    </button>
                                                @endif
                                                <a href="{{ route('order.detail', $order->id) }}"
                                                    class="btn btn-sm btn-outline-primary ms-2">Detail</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.pay-button').forEach(button => {
                button.addEventListener('click', function() {
                    const snapToken = this.getAttribute('data-snap-token');
                    const orderId = this.getAttribute('data-order-id');
                    const button = this;
                    console.log('order:', orderId);

                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    button.disabled = true;

                    snap.pay(snapToken, {
                        onSuccess: function(result) {
                            fetch('/user/handlePaymentSuccess', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content,
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        snap_token: snapToken,
                                        order_id: orderId
                                    })
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error(
                                            'Network response was not ok');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        window.location.href = data.redirect_url;
                                    } else {
                                        alert('Pembayaran berhasil tetapi update status gagal: ' +
                                            data.message);
                                        window.location.href =
                                            `/user/statusOrder/${orderId}`;
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    window.location.href =
                                        `/user/statusOrder/${orderId}`;
                                });
                        },
                        onPending: function(result) {
                            button.innerHTML = '<i class="fas fa-credit-card"></i>';
                            button.disabled = false;
                        },
                        onError: function(result) {
                            window.location.href = '{{ route("order.status") }}';
                            button.innerHTML = '<i class="fas fa-credit-card"></i>';
                            button.disabled = false;
                        },
                        onClose: function() {
                            button.innerHTML = '<i class="fas fa-credit-card"></i>';
                            button.disabled = false;
                        }
                    });
                });
            });
        });
    </script>
@endsection
