@extends('layouts.user')

@section('main-content')
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Review Saya</h2>

                @if ($reviews->isEmpty())
                    <div class="alert alert-info">
                        Anda belum memberikan review untuk produk apapun.
                    </div>
                @else

                @endif
            </div>
        </div>
    </div>
@endsection
