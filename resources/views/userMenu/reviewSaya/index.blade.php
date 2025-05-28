@extends('layouts.user')

@section('main-content')
<div class="container">
    @foreach($reviews as $order)
        <div class="card mb-3">
            <div class="card-body">
                <h5>Order #{{ $order->order_number }}</h5>
                @foreach($order->items as $item)
                    <div class="item">
                        {{ $item->product->name }} - {{ $item->quantity }} pcs
                    </div>
                @endforeach
            </div>
        </div>
        @forelse($orders as $order)
    <!-- Tampilkan order -->
@empty
    <div class="alert alert-info">Belum ada riwayat pesanan</div>
@endforelse
        <div class="card mb-3">
            <div class="card-body">
                <h5>Review Produk</h5>
                @foreach($order->items as $item)
                    <div class="item">
                        {{ $item->product->name }} - {{ $item->quantity }} pcs
                        <!-- Form review -->
                        <form action="{{ route('review.submit') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                            <textarea name="review" class="form-control"></textarea>
                            <button type="submit" class="btn btn-primary mt-2">Submit Review</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection
