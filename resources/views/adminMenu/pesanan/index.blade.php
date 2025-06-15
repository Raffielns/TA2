@extends('layouts.admin')

@section('main-content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Pesanan</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No. Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Metode Pembayaran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($orders)
                                @forelse($orders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($order->payment_method == 'transfer')
                                                Transfer Bank
                                            @elseif ($order->payment_method == 'cod')
                                                COD
                                            @else
                                                Midtrans
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
                                            <a href="{{ route('admin.orders.show', $order->id) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data pesanan</td>
                                    </tr>
                                @endforelse
                            @else
                                <tr>
                                    <td colspan="7" class="text-center">Error: Data pesanan tidak tersedia</td>
                                </tr>
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <form id="statusForm" method="POST" style="display:none;">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" id="statusInput">
    </form>
@endsection
