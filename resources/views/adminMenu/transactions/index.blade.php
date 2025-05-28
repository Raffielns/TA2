@extends('layouts.admin')

@section('main-content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Pembayaran</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No. Pesanan</th>
                                <th>Bank</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Bukti Transfer</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($payments)
                                @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->order->order_number ?? 'N/A' }}</td>
                                        <td>{{ strtoupper($payment->bank_name) }}</td>
                                        <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $payment->status == 'verified' ? 'success' : ($payment->status == 'rejected' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($payment->proof_image)
                                                <a href="{{ asset('storage/' . $payment->proof_image) }}" target="_blank">Lihat
                                                    Bukti</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if ($payment->status == 'pending')
                                                <button class="btn btn-sm btn-success"
                                                    onclick="verifyPayment({{ $payment->id }}, 'verified')">
                                                    <i class="fas fa-check"></i> Verifikasi
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="verifyPayment({{ $payment->id }}, 'rejected')">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data pembayaran</td>
                                    </tr>
                                @endforelse
                            @else
                                <tr>
                                    <td colspan="7" class="text-center text-danger">Error: Data pembayaran tidak tersedia
                                    </td>
                                </tr>
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <form id="verifyForm" method="POST" style="display:none;">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" id="paymentStatus">
    </form>

    <script>
        function verifyPayment(paymentId, status) {
            if (confirm('Anda yakin ingin memverifikasi pembayaran ini?')) {
                let form = document.getElementById('verifyForm');
                form.action = `/admin/payments/${paymentId}/verify`;
                document.getElementById('paymentStatus').value = status;
                form.submit();
            }
        }
    </script>

@endsection
