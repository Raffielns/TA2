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
                                    @if($order->payment_method == 'cod')
                                        COD
                                    @else
                                        Transfer ({{ strtoupper($order->payment->bank_name) }})
                                    @endif
                                </td>
                                <td>
                                    <span class="badge
                                        @if($order->status == 'pending') badge-warning
                                        @elseif($order->status == 'processing') badge-info
                                        @elseif($order->status == 'completed') badge-success
                                        @elseif($order->status == 'cancelled') badge-danger @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Ubah Status
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#" onclick="updateStatus({{ $order->id }}, 'processing')">Proses</a>
                                            <a class="dropdown-item" href="#" onclick="updateStatus({{ $order->id }}, 'completed')">Selesai</a>
                                            <a class="dropdown-item" href="#" onclick="updateStatus({{ $order->id }}, 'cancelled')">Batal</a>
                                        </div>
                                    </div>
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

<script>
function updateStatus(orderId, status) {
    if(confirm('Anda yakin ingin mengubah status pesanan?')) {
        let form = document.getElementById('statusForm');
        form.action = `/admin/orders/${orderId}/update-status`;
        document.getElementById('statusInput').value = status;
        form.submit();
    }
}
</script>
@endsection
