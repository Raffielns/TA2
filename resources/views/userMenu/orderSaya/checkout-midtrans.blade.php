@extends('layouts.user')

@section('main-content')
<div class="container py-5 text-center">
    <h3>Menyiapkan Halaman Pembayaran...</h3>
    <p>Harap tunggu, Anda akan diarahkan ke halaman pembayaran Midtrans.</p>

    <div class="spinner-border text-primary mt-4" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>

<!-- Midtrans Snap JS -->
<script type="text/javascript"
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}"></script>

<script type="text/javascript">
    // Token Snap dikirim dari controller
    let snapToken = "{{ $snapToken }}";

    snap.pay(snapToken, {
        onSuccess: function(result){
            alert("Pembayaran berhasil!");
            window.location.href = "/user/order/success/" + result.order_id;
        },
        onPending: function(result){
            alert("Transaksi sedang diproses.");
            window.location.href = "/user/order/success/" + result.order_id;
        },
        onError: function(result){
            alert("Pembayaran gagal. Silakan coba lagi.");
            console.error(result);
            window.location.href = "/catalog";
        },
        onClose: function(){
            alert("Anda menutup halaman pembayaran.");
            window.location.href = "/catalog";
        }
    });
</script>
@endsection
