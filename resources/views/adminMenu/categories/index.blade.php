@extends('layouts.admin')

@section('main-content')
<div class="align-items-stretch">
    <div class="card w-100 shadow-sm border-0">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <!-- Kiri -->
                <div>
                    <h5 class="card-title fw-semibold mb-2">Kategori Produk</h5>
                    <div class="d-flex align-items-center" style="gap: 8px;">
                        <a href="{{ url('/') }}" class="text-decoration-none text-dark">
                            <i class="fas fa-home"></i>
                        </a>
                        <span>-</span>
                        <a href="{{ route('dashboard') }}" class="text-decoration-none text-dark">
                            Dashboard
                        </a>
                    </div>
                </div>
                <!-- Kanan -->
                <div>
                    <a class="btn btn-sm btn-warning" href="{{ route('category.create') }}">
                        <i class="fas fa-plus me-1"></i> Tambah Kategori
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div style="max-height: 60vh;" class="table-responsive overflow-y-scroll" id="scroll">
                <table class="table table-bordered table-hover align-middle" id="Tables">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%">No.</th>
                            <th style="text-align: left;">Nama Kategori</th>
                            <th style="width: 20%">Jumlah Produk</th>
                            <th colspan="2" style="width: 20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $kategori)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-start">{{ $kategori->name }}</td>
                            <td>
                                <span class="badge bg-light">{{ $kategori->products_count }} produk</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('category.edit', $kategori->id) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('category.destroy', $kategori->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus kategori ini?')">
                                        <i class="fas fa-trash-alt"></i>
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
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>
<script type="module">
    $(document).ready(function() {
        $("#Tables").DataTable({
            responsive: true,
            fixedHeader: true
        });
    });
</script>
@endpush
