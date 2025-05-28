@extends('layouts.user')
@section('main-content')
bayar jing
@foreach($payments as $payment)
    <div class="payment-item">
        <p>Order: {{ $payment->order->order_number }}</p>
        <p>Status: {{ ucfirst($payment->status) }}</p>
    </div>
@endforeach
@endsection
