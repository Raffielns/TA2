@extends('layouts.user')

@section('main-content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Detail Pesanan #{{ $order->order_number }}</h1>
            <button class="btn btn-primary btn-sm">
                <a href="{{ route('order.status') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </button>
        </div>

        <div class="row">
            <!-- Order Summary -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Ringkasan Pesanan</h6>
                        <span
                            class="badge
                        @if ($order->status == 'pending') badge-warning
                        @elseif($order->status == 'diproses') badge-info
                        @elseif($order->status == 'dikirim') badge-info
                        @elseif($order->status == 'selesai') badge-success
                        @elseif($order->status == 'dibatalkan') badge-danger
                        @else badge-secondary @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Nomor Pesanan:</strong><br>
                                {{ $order->order_number }}
                            </div>
                            <div class="col-md-6">
                                <strong>Tanggal Pesanan:</strong><br>
                                {{ $order->created_at->format('d F Y, H:i') }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Metode Pembayaran:</strong><br>
                                @if ($order->payment_method == 'transfer')
                                    Transfer Bank
                                @else
                                    {{ strtoupper($order->payment_method) }}
                                @endif
                            </div>
                            <div class="col-md-6">
                                <strong>Total Pembayaran:</strong><br>
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </div>
                        </div>


                        @if ($order->telephone)
                            <div class="row">
                                <div class="col-12">
                                    <strong>No. Telp/WhatsApp:</strong><br>
                                    {{ $order->telephone }}
                                </div>
                            </div>
                        @endif

                        @if ($order->notes)
                            <div class="row">
                                <div class="col-12">
                                    <strong>Catatan Pesanan:</strong><br>
                                    {{ $order->notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Pelanggan</h6>
                    </div>
                    <div class="card-body">
                        <address>
                            <strong>{{ $order->user->name }}</strong><br>
                            {{ $order->user->email }}<br>
                            {{-- {{ $order->telephone }}<br><br> --}}
                            {!! nl2br(e($order->shipping_address)) !!}
                        </address>
                    </div>
                </div>
            </div>

            <!-- Order Items & Payment -->
            <div class="col-lg-6">
                <!-- Order Items -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Item Pesanan</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Gambar</th>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>
                                                <img src="{{ asset('storage/files/' . $item->product->encrypted_filename) }}"
                                                    alt="" class="img-fluid rounded" style="max-height: 80px;">
                                            </td>
                                            <td class="text-start">{{ $item->product->nama_barang }}</td>
                                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right">Subtotal:</th>
                                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                @if ($order->receipt)
                    <div class="card">
                        <a href="{{ route('admin.receipt.generate', $order->id) }}" class="btn btn-info">
                            <i class="fa-solid fa-receipt"></i> Download Struk
                        </a>
                    </div>
                @endif

                <!-- Payment Proof -->
                @if ($order->payment_proof)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Bukti Pembayaran</h6>
                            <a href="#" class="btn btn-sm btn-primary" data-toggle="modal"
                                data-target="#paymentProofModal">
                                <i class="fas fa-expand"></i> Lihat Full
                            </a>
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ asset('storage/' . $order->payment_proof) }}" alt=""
                                class="img-fluid rounded" style="max-height: 80px;">
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Proof Modal -->
    @if ($order->payment_proof)
        <div class="modal fade" id="paymentProofModal" tabindex="-1" role="dialog"
            aria-labelledby="paymentProofModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentProofModalLabel">Bukti Pembayaran</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Transfer" class="img-fluid">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
                        <a href="{{ asset('storage/' . $order->payment_proof) }}" download class="btn btn-primary">
                            <i class="fas fa-download"></i> Unduh
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
