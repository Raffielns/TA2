@extends('layouts.admin')

@section('main-content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Bahan Baku</h1>
        <a href="{{ route('materials.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>Tambah Bahan Baku
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Bahan Baku <span>History Pesanan</span></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Bahan</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Harga/Unit</th>
                            <th>Supplier</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($materials ?? [] as $index => $material)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($material->image)
                                        <img src="{{ asset('storage/' . $material->image) }}" alt="{{ $material->name }}" class="img-thumbnail mr-3" width="50">
                                    @else
                                        <div class="img-thumbnail mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #f8f9fa;">
                                            <i class="fas fa-box-open text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $material->name }}</strong>
                                        <div class="text-muted small">{{ Str::limit($material->description, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ ucfirst($material->category) }}</span>
                            </td>
                            <td>
                                {{ $material->stock }} {{ $material->unit }}
                                @if($material->stock <= $material->minimum_stock)
                                    <div class="text-danger small">Stok rendah!</div>
                                @endif
                            </td>
                            <td>Rp {{ number_format($material->price, 0, ',', '.') }}</td>
                            <td>{{ $material->supplier }}</td>
                            <td>
                                @if($material->stock == 0)
                                    <span class="badge badge-danger">Habis</span>
                                @elseif($material->stock <= $material->minimum_stock)
                                    <span class="badge badge-warning">Hampir Habis</span>
                                @else
                                    <span class="badge badge-success">Tersedia</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('materials.edit', $material->id) }}" class="btn btn-sm btn-primary mr-2" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('materials.destroy', $material->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus bahan baku ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
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

@section('scripts')
<!-- Page level plugins -->
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
    });
</script>
@endsection
