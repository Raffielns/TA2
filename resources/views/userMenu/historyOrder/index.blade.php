@extends('layouts.user')

@section('main-content')
<div class="container">
    @if(isset($orders) && count($orders) > 0)
        @foreach($orders as $order)
            <div class="card mb-3">
                <div class="card-body">
                    <h5>Order #{{ $order->order_number }}</h5>
                    <p>Status: {{ ucfirst($order->status) }}</p>
                    <!-- Tambahkan detail lainnya -->
                </div>
            </div>
        @endforeach
    @else
        <div class="alert alert-info">
            Belum ada riwayat pesanan
        </div>
    @endif
</div>
@endsection
