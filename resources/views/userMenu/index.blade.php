@extends('layouts.user')

@section('main-content')
@php
    use Illuminate\Support\Carbon;

    $user = auth()->user();
    $orders = $user->orders()->whereMonth('created_at', now()->month)->get();
    $totalBelanja = $orders->sum('total_amount');
    $totalPesanan = $orders->count();
    $pesananSelesai = $orders->where('status', 'completed')->count();
    $pesananProses = $orders->whereIn('status', ['processing', 'pending'])->count();
    $recentOrders = $user->orders()->with('items.product')->latest()->take(3)->get();
    $cart = session('cart') ?? [];
    $cartItems = array_slice($cart, -3, 3, true);
@endphp

<h1 class="h3 mb-4 text-black-800">Dashboard Customer</h1>

@if (session('success'))
    <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="row">
    <!-- Total Pesanan -->
    <x-user.statbox color="primary" icon="shopping-cart" title="Total Pesanan" :value="$totalPesanan" />

    <!-- Pesanan Dalam Proses -->
    <x-user.statbox color="warning" icon="spinner" title="Pesanan Dalam Proses" :value="$pesananProses" />

    <!-- Pesanan Selesai -->
    <x-user.statbox color="success" icon="check-circle" title="Pesanan Selesai" :value="$pesananSelesai" />

    <!-- Total Belanja -->
    <x-user.statbox color="info" icon="wallet" title="Total Belanja" :value="'Rp ' . number_format($totalBelanja, 0, ',', '.')" />
</div>

<!-- Produk Terbaru di Keranjang -->
@if(count($cartItems))
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Produk Terbaru di Keranjang</h6>
        <a href="{{ route('customer.order') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $cartTotal = 0; @endphp
                    @foreach ($cartItems as $id => $item)
                        @php $sub = $item['price'] * $item['quantity']; $cartTotal += $sub; @endphp
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>Rp {{ number_format($sub, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('cart.remove', $id) }}" method="POST" class="d-inline">@csrf
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                                </form>
                                <a href="{{ route('catalog.detail', $id) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Total:</td>
                        <td colspan="2" class="fw-bold">Rp {{ number_format($cartTotal, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Pesanan Terakhir -->
<div class="card shadow mb-4">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Pesanan Terakhir</h6>
    </div>
    <div class="card-body">
        @if ($recentOrders->isEmpty())
            <p class="text-muted">Belum ada pesanan.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentOrders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge
                                    @if($order->status == 'completed') badge-success
                                    @elseif($order->status == 'processing') badge-warning
                                    @else badge-info @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-primary">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
