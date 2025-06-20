@extends('layouts.admin')

@section('main-content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Ulasan Customer</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Ulasan</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Produk</th>
                                <th>Customer</th>
                                <th>Order ID</th>
                                <th>Rating</th>
                                <th>Ulasan</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ulasan as $index => $review)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/files/' . $review->product->encrypted_filename) }}"
                                                alt="" class="img-fluid rounded" style="max-height: 50px;">
                                            <span>{{ $review->product->nama_barang }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $review->user->name }}</td>
                                    <td>#{{ $review->order_id }}</td>
                                    <td>
                                        <div class="rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                            <span class="ms-1">({{ $review->rating }})</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="review-text">
                                            @php
                                                // Memecah teks menjadi array per 50 karakter
                                                $chunks = str_split($review->review, 30);
                                            @endphp

                                            @foreach ($chunks as $chunk)
                                                {{ $chunk }}<br>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>{{ $review->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
