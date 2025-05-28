@extends('layouts.user')

@section('main-content')
<div class="container">
    <h2>Review Produk</h2>
    @foreach($reviews as $order)
        @foreach($order->items as $item)
            <div class="card mb-3">
                <div class="card-body">
                    <h5>{{ $item->product->name }}</h5>
                    <!-- Form review -->
                    <form action="{{ route('review.submit') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                        <textarea name="review" class="form-control"></textarea>
                        <button type="submit" class="btn btn-primary mt-2">Submit Review</button>
                    </form>
                </div>
            </div>
        @endforeach
    @endforeach
</div>
@endsection
