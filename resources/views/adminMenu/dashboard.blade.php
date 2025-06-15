@extends('layouts.admin')

@section('main-content')
    <div class="container-fluid">
        <div class="row mb-4">
            <!-- Kartu Ringkasan -->
            <div class="col-md-4">
                <div class="card shadow border-left-primary">
                    <div class="card-body">
                        <div class="text-uppercase text-muted small">Pendapatan Bulan Ini</div>
                        <h4 class="fw-bold">Rp {{ number_format($pendapatan, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow border-left-success">
                    <div class="card-body">
                        <div class="text-uppercase text-muted small">Total Produk</div>
                        <h4 class="fw-bold">{{ $totalProduk }}</h4>
                        {{-- <span class="badge bg-success">{{ $produkBaru }} Baru</span>
                        <span class="badge bg-warning text-dark">{{ $produkLowStock }} Low</span> --}}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow border-left-info">
                    <div class="card-body">
                        <div class="text-uppercase text-muted small">Total Pesanan</div>
                        <h4 class="fw-bold">{{ $totalOrder }}</h4>
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-3">
                <div class="card shadow border-left-warning">
                    <div class="card-body">
                        <div class="text-uppercase text-muted small">Pembayaran Masuk</div>
                        <h4 class="fw-bold">{{ $totalPembayaran }}</h4>
                    </div>
                </div>
            </div> --}}
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
                                    <td>
                                        <span
                                            class="badge
                                    @if ($o->status == 'selesai') badge-success
                                    @elseif($o->status == 'pending') badge-warning
                                    @else badge-info @endif">
                                            {{ ucfirst($o->status) }}
                                        </span>
                                    </td>
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
        @include('partials.log_activity')
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Grafik Pendapatan 6 Bulan Terakhir
        const revenueCtx = document.getElementById('chartPendapatan').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'Pendapatan',
                    data: @json($monthlyRevenue),
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointBorderColor: '#fff',
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointHoverBorderColor: '#fff',
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Grafik Produk Terlaris Bulan Ini
        const productsCtx = document.getElementById('chartProdukLaris').getContext('2d');
        const productsChart = new Chart(productsCtx, {
            type: 'bar',
            data: {
                labels: @json($topProducts->pluck('name')),
                datasets: [{
                    label: 'Jumlah Terjual',
                    data: @json($topProducts->pluck('total_sold')),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw + ' unit terjual';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            callback: function(value) {
                                if (Number.isInteger(value)) {
                                    return value;
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Terjual'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Nama Produk'
                        }
                    }
                }
            }
        });
    </script>
@endsection
