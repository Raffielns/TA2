@extends('layouts.user')

@section('main-content')
    <div class="container py-4">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><i class="fas fa-cash-register mr-2"></i> Proses Checkout</h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <!-- Ringkasan Pesanan -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Daftar Pesanan :</h5>
                            </div>
                            <div class="card-body">
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
                                        @foreach ($cartItems as $item)
                                            <tr>
                                                <td>
                                                    <img src="{{ asset('storage/files/' . $item->product->encrypted_filename) }}"
                                                        alt="" class="img-fluid rounded" style="max-height: 80px;">
                                                </td>
                                                <td>{{ $item->product_name }}</td>
                                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3">Total Pembayaran</th>
                                            <th>Rp {{ number_format($total, 0, ',', '.') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Form Pembayaran -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Metode Pembayaran</h5>
                            </div>

                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('order.submit') }}" method="POST" enctype="multipart/form-data"
                                    id="paymentForm">
                                    @csrf
                                    @foreach ($cartItems as $item)
                                        @php
                                            $subtotal = $item->price * $item->quantity;
                                            $total += $subtotal;
                                        @endphp
                                        <input type="hidden" name="products[{{ $item->product_id }}][product_id]"
                                            value="{{ $item->product_id }}">
                                        <input type="hidden" name="products[{{ $item->product_id }}][quantity]"
                                            value="{{ $item->quantity }}">
                                        <input type="hidden" name="products[{{ $item->product_id }}][price]"
                                            value="{{ $item->price }}">
                                        <input type="hidden" name="products[{{ $item->product_id }}][color]"
                                            value="{{ $item->color ?? '' }}">
                                        <input type="hidden" name="products[{{ $item->product_id }}][size]"
                                            value="{{ $item->size ?? '' }}">
                                    @endforeach
                                    <div class="form-group">
                                        <label><strong>Pilih Metode Pembayaran</strong></label>
                                        <div class="payment-methods">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="radio" name="payment_method"
                                                    id="cod" value="cod" checked>
                                                <label class="form-check-label" for="cod">
                                                    <i class="fas fa-money-bill-wave mr-2"></i> Cash on Delivery (COD)
                                                </label>
                                            </div>

                                            {{-- <div class="form-check mb-3">
                                                <input class="form-check-input" type="radio" name="payment_method"
                                                    id="transfer" value="transfer">
                                                <label class="form-check-label" for="transfer">
                                                    <i class="fas fa-university mr-2"></i> Transfer Bank
                                                </label>
                                            </div> --}}

                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="radio" name="payment_method"
                                                    id="midtrans" value="midtrans">
                                                <label class="form-check-label" for="midtrans">
                                                    <i class="fa-solid fa-credit-card mr-2"></i> Midtrans
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Transfer Bank Details (Hidden by Default) -->
                                    {{-- <div id="bankDetails" style="display:none;">
                                        <div class="form-group">
                                            <label>Pilih Bank :</label>
                                            <select class="form-control" name="bank_name">
                                                <option value="bca">BCA</option>
                                                <option value="bni">BNI</option>
                                                <option value="bri">BRI</option>
                                                <option value="mandiri">Mandiri</option>
                                            </select>
                                        </div>
                                        <div class="alert alert-info">
                                            <p>Silakan transfer ke:</p>
                                            <p><strong>Bank: BCA</strong><br>
                                                No. Rekening: 1234567890<br>
                                                A/N:CV. Anugerah Sukses Sejahtera<br>
                                                Jumlah: Rp {{ number_format($total, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="form-group">
                                            <label>Upload Bukti Transfer</label>
                                            <input type="file" class="form-control-file" name="payment_proof">
                                        </div>
                                    </div> --}}

                                    <!-- Alamat Pengiriman -->
                                    <div class="form-group mt-4">
                                        <label><strong>Alamat Pengiriman</strong></label>
                                        <textarea class="form-control" name="shipping_address" rows="3" required></textarea>
                                    </div>

                                    <div class="form-group mt-4">
                                        <label><strong>Catatan (Opsional)</strong></label>
                                        <textarea class="form-control" name="notes" rows="2"></textarea>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <a href="{{ route('customer.order') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                                        </a>
                                        <button type="submit" class="btn btn-primary" id="confirmorderBtn">
                                            <i class="fas fa-check-circle mr-2"></i> Konfirmasi Pesanan
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Menampilkan detail bank ketika memilih transfer
        document.querySelectorAll('input[name="payment_method"]').forEach(el => {
            el.addEventListener('change', function() {
                document.getElementById('bankDetails').style.display =
                    this.value === 'transfer' ? 'block' : 'none';
            });
        });

        // Menghilangkan submit handler JavaScript dan biarkan form submit normal
        document.getElementById('paymentForm').addEventListener('submit', function() {
            const btn = document.getElementById('confirmorderBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        });
    </script>

    <style>
        .payment-methods .form-check-label {
            padding: 10px;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
        }

        .payment-methods .form-check-input:checked+label {
            background-color: #f8f9fa;
            font-weight: bold;
        }
    </style>
@endsection
