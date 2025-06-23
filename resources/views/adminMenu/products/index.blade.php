@extends('layouts.admin')

@section('main-content')
    <div class="align-items-stretch">
        <div class="card w-100 shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    @if (auth()->user()->role == 1)
                    <h5 class="card-title fw-semibold">Kelola Produk</h5>
                        <a class="btn btn-sm btn-warning" href="{{ route('products.create') }}">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Produk
                        </a>
                    @endif
                </div>

                <div class="table-responsive overflow-y-scroll" style="max-height: 60vh;" id="scroll">
                    <table class="table table-bordered table-hover align-middle" id="Tables">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 12%">Gambar</th>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                @if (auth()->user()->role == 1)
                                    <th colspan="2" class="text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>
                                        <img src="{{ asset('storage/files/' . $product->encrypted_filename) }}"
                                            alt="" class="img-fluid rounded" style="max-height: 80px;">
                                    </td>
                                    <td class="text-start">{{ $product->nama_barang }}</td>
                                    <td class="text-muted small">{{ Str::limit($product->deskripsi, 60) }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>Rp {{ number_format($product->harga_barang, 0, ',', '.') }}</td>
                                    <td>{{ $product->stock }}</td>
                                    @if (auth()->user()->role == 1)
                                        <td class="text-center">
                                            <a href="{{ route('products.edit', $product->id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        </td>
                                    @endif
                                        <td class="text-center">
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                                class="delete-form">
                                                @csrf
                                                @method('delete')
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete"
                                                    onclick="return confirm('Hapus produk ini?')">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $("#Tables").DataTable({
                responsive: true
            });
        });
    </script>
@endpush
