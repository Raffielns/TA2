@extends('layouts.user')

@section('main-content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-star mr-2"></i> Beri Review Produk
                        </h3>
                    </div>

                    <div class="card-body">
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-3">
                                @if ($product->images->first())
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                        alt="{{ $product->nama_barang }}" class="img-thumbnail mr-3"
                                        style="width: 80px; height: 80px; object-fit: cover;">
                                @endif
                                <div>
                                    <h5 class="mb-1">{{ $product->nama_barang }}</h5>
                                    <p class="mb-1 text-muted small">No. Pesanan: #{{ $order->order_number }}</p>
                                    <p class="mb-0 text-muted small">Tanggal Pesanan:
                                        {{ $order->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <form method="POST"
                            action="{{ route('user.reviews.submit', ['order' => $order->id, 'product' => $product->id]) }}">
                            @csrf

                            <div class="form-group">
                                <label class="font-weight-bold">Rating Produk</label>
                                <div class="rating-container mb-2">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}" name="rating"
                                            value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}
                                            class="d-none">
                                        <label for="star{{ $i }}" class="rating-star">
                                            <i class="{{ old('rating') >= $i ? 'fas' : 'far' }} fa-star"></i>
                                        </label>
                                    @endfor
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">Tidak puas</small>
                                    <small class="text-muted">Sangat puas</small>
                                </div>
                                @error('rating')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="review" class="font-weight-bold">Ulasan Anda</label>
                                <textarea class="form-control @error('review') is-invalid @enderror" id="review" name="review" rows="5"
                                    placeholder="Bagaimana pengalaman Anda dengan produk ini?">{{ old('review') }}</textarea>
                                @error('review')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-0 d-flex justify-content-between align-items-center">
                                <a href="{{ route('order.history') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-paper-plane mr-2"></i> Kirim Review
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .rating-container {
            display: flex;
            flex-direction: row-reverse;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .rating-star {
            color: #ddd;
            font-size: 2.2rem;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-right: 0.3rem;
            position: relative;
        }

        .rating-star:hover {
            transform: scale(1.1);
        }

        .rating-star i.fas {
            color: #FFC107;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .rating-star:hover,
        .rating-star:hover~.rating-star {
            color: #FFC107;
        }

        input[name="rating"]:checked~.rating-star i {
            color: #FFC107;
        }

        .rating-labels {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-top: 0.5rem;
        }
    </style>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            function updateStars(ratingValue) {
                $('.rating-star').each(function(index) {
                    const starNum = parseInt($(this).attr('for').replace('star', ''));
                    if (starNum <= ratingValue) {
                        $(this).find('i').removeClass('far').addClass('fas');
                    } else {
                        $(this).find('i').removeClass('fas').addClass('far');
                    }
                });
            }

            $('.rating-star').hover(
                function() {
                    const starNum = parseInt($(this).attr('for').replace('star', ''));
                    updateStars(starNum);
                },
                function() {
                    const selected = $('input[name="rating"]:checked').val();
                    updateStars(parseInt(selected || 0));
                }
            );

            $('.rating-star').click(function() {
                const starId = $(this).attr('for');
                const ratingValue = parseInt(starId.replace('star', ''));
                $(`#${starId}`).prop('checked', true);
                updateStars(ratingValue);
            });

            const selected = $('input[name="rating"]:checked').val();
            updateStars(parseInt(selected || 0));
        });
    </script>
@endpush
