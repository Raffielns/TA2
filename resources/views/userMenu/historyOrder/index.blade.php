@extends('layouts.user')

@section('main-content')
    <div class="container py-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><i class="fas fa-clipboard-list mr-2"></i> Riwayat Pesanan</h3>
            </div>

            <div class="card-body">
                @if ($orderItems->isEmpty())
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
                                    <th>Nama Barang</th>
                                    <th>Harga</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderItems as $orderItem)
                                    <tr>
                                        <td>#{{ $orderItem->order->order_number }}</td>
                                        <td>{{ $orderItem->order->created_at->format('d M Y H:i') }}</td>
                                        <td>{{ $orderItem->product->nama_barang }}</td>
                                        <td>Rp {{ number_format($orderItem->product->harga_barang, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($orderItem->order->payment_method == 'cod')
                                                <span class="badge badge-secondary">COD</span>
                                            @elseif($orderItem->order->payment_method == 'transfer')
                                                <span class="badge badge-info">Transfer Bank</span>
                                            @else
                                                <span
                                                    class="badge badge-primary">{{ ucfirst($orderItem->order->payment_method) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusClass =
                                                    [
                                                        'pending' => 'warning',
                                                        'diproses' => 'info',
                                                        'selesai' => 'success',
                                                        'dibatalkan' => 'danger',
                                                        'gagal' => 'dark',
                                                    ][$orderItem->order->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge badge-{{ $statusClass }}">
                                                {{ ucfirst($orderItem->order->status) }}
                                                @if ($orderItem->order->payment_status)
                                                    ({{ ucfirst($orderItem->order->payment_status) }})
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            @if ($orderItem->order->status == 'selesai')
                                                @php
                                                    $hasReview = App\Models\Review::where(
                                                        'order_id',
                                                        $orderItem->order->id,
                                                    )
                                                        ->where('product_id', $orderItem->product_id)
                                                        ->exists();
                                                @endphp

                                                @if ($hasReview)
                                                    <a href="{{ route('user.reviews.show', ['order' => $orderItem->order->id, 'product' => $orderItem->product_id]) }}"
                                                        class="btn btn-sm btn-info" title="Lihat Review">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('user.reviews.create', ['order' => $orderItem->order->id, 'product' => $orderItem->product_id]) }}"
                                                        class="btn btn-sm btn-success" title="Beri Review">
                                                        <i class="fas fa-star"></i>
                                                    </a>
                                                @endif
                                            @endif
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
