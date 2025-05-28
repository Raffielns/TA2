@extends('layouts.admin')

@section('main-content')
    <div class="container-fluid">
        <div class="row mb-4">
            <!-- Kartu Ringkasan -->
            <div class="col-md-3">
                <div class="card shadow border-left-primary">
                    <div class="card-body">
                        <div class="text-uppercase text-muted small">Pendapatan Bulan Ini</div>
                        <h4 class="fw-bold">Rp {{ number_format($pendapatan, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow border-left-success">
                    <div class="card-body">
                        <div class="text-uppercase text-muted small">Total Produk</div>
                        <h4 class="fw-bold">{{ $totalProduk }}</h4>
                        <span class="badge bg-success">{{ $produkBaru }} Baru</span>
                        <span class="badge bg-warning text-dark">{{ $produkLowStock }} Low</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow border-left-info">
                    <div class="card-body">
                        <div class="text-uppercase text-muted small">Total Pesanan</div>
                        <h4 class="fw-bold">{{ $totalOrder }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow border-left-warning">
                    <div class="card-body">
                        <div class="text-uppercase text-muted small">Pembayaran Masuk</div>
                        <h4 class="fw-bold">{{ $totalPembayaran }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header">Pendapatan 6 Bulan Terakhir</div>
                    <div class="card-body">
                        <canvas id="chartPendapatan"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header">Produk Terlaris Bulan Ini</div>
                    <div class="card-body">
                        <canvas id="chartProdukLaris"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Pesanan Terbaru -->
        <div class="card shadow mb-4">
            <div class="card-header">Pesanan Terbaru</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Order</th>
                                <th>Pelanggan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestOrders as $o)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $o->order_number }}</td>
                                    <td>{{ $o->user->name }}</td>
                                    <td>{{ $o->created_at->format('d M Y') }}</td>
                                    <td>Rp {{ number_format($o->total_amount, 0, ',', '.') }}</td>
                                    <td><span class="badge bg-info">{{ ucfirst($o->status) }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada pesanan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const chartPendapatan = document.getElementById('chartPendapatan').getContext('2d');
            new Chart(chartPendapatan, {
                type: 'line',
                data: {
                    labels: {!! json_encode($grafikBulan) !!},
                    datasets: [{
                        label: 'Pendapatan',
                        data: {!! json_encode($grafikPendapatan) !!},
                        borderColor: 'rgba(78, 115, 223, 1)',
                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                }
            });

            const chartProdukLaris = document.getElementById('chartProdukLaris').getContext('2d');
            new Chart(chartProdukLaris, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($produkLarisNama) !!},
                    datasets: [{
                        label: 'Terjual',
                        data: {!! json_encode($produkLarisJumlah) !!},
                        backgroundColor: 'rgba(54, 185, 204, 0.5)',
                        borderColor: 'rgba(54, 185, 204, 1)',
                        borderWidth: 1
                    }]
                }
            });
        </script>
    @endpush

@include('partials.log_activity')
@endsection
