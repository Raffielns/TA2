@extends('layouts.user')

@section('main-content')
<div class="container py-4">
    <div class="card shadow-lg" style="border: none; border-radius: 15px; overflow: hidden;">
        <div class="card-header py-3 bg-primary">
            <h2 class="mb-0 text-white">
                <i class="fas fa-shopping-cart mr-2"></i> Keranjang Belanja Anda
            </h2>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(empty(session('cart')))
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x mb-4 text-muted"></i>
                    <h4>Keranjang belanja Anda kosong</h4>
                    <p class="text-muted">Mulai berbelanja dan tambahkan produk ke keranjang Anda</p>
                    <a href="{{ route('catalog') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali Berbelanja
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th style="border-top-left-radius: 10px;">Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Warna</th>
                                <th>Ukuran</th>
                                <th>Subtotal</th>
                                <th style="border-top-right-radius: 10px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0 @endphp
                            @foreach(session('cart') as $id => $details)
                                @php $total += $details['price'] * $details['quantity'] @endphp
                                <tr>
                                    <td style="font-weight: 600;">{{ $details['name'] }}</td>
                                    <td>Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                                    <td>
                                        <form action="{{ route('cart.update', $id) }}" method="POST">
                                            @csrf
                                            <div class="input-group" style="width: 120px;">
                                                <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1"
                                                       class="form-control text-center">
                                                <div class="input-group-append">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">
                                            {{ $details['color'] ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary badge-pill">
                                            {{ $details['size'] ?? '-' }}
                                        </span>
                                    </td>
                                    <td style="font-weight: 600;">
                                        Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <form action="{{ route('cart.remove', $id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-right font-weight-bold">Total Belanja:</td>
                                <td colspan="2" class="font-weight-bold">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('catalog') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left mr-2"></i> Lanjutkan Belanja
                    </a>
                    <a href="{{ route('checkout') }}" class="btn btn-primary" onclick="event.preventDefault(); document.getElementById('checkout-form').submit();">
                        <i class="fas fa-credit-card mr-2"></i> Proses Checkout
                    </a>
                    <form id="checkout-form" action="{{ route('checkout') }}" method="GET" style="display: none;">
                        @csrf
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .table thead th {
        border-bottom: none;
        font-weight: 600;
    }

    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .btn {
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection
