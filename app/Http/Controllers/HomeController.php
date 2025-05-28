<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Statistik Dasar
        $pendapatan = Order::whereMonth('created_at', now()->month)->sum('total_amount');
        $totalProduk = Product::count();
        $produkBaru = Product::whereMonth('created_at', now()->month)->count();
        $produkLowStock = Product::where('stock', '<=', 10)->count();
        $totalOrder = Order::count();
        $totalPembayaran = Payment::where('status', 'verified')->sum('amount');

        // Ambil 5 log aktivitas terbaru
        $logs = ActivityLog::with('user')->latest('created_at')->limit(5)->get();

        // Grafik Pendapatan per Bulan (6 bulan terakhir)
        $grafikPendapatan = [];
        $grafikBulan = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $label = $bulan->format('M Y');
            $total = Order::whereMonth('created_at', $bulan->month)
                ->whereYear('created_at', $bulan->year)
                ->sum('total_amount');

            $grafikBulan[] = $label;
            $grafikPendapatan[] = $total;
        }

        // Produk Terlaris (top 5 dari tabel order_items)
        $produkLaris = DB::table('order_items')
            ->select('products.nama_barang', DB::raw('SUM(order_items.quantity) as total_terjual'))
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereMonth('order_items.created_at', now()->month)
            ->groupBy('products.nama_barang')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        $produkLarisNama = $produkLaris->pluck('nama_barang');
        $produkLarisJumlah = $produkLaris->pluck('total_terjual');

        // Pesanan terbaru (5 terakhir)
        $latestOrders = Order::latest()->take(5)->with('user')->get();

        return view('adminMenu.dashboard', compact(
            'pendapatan',
            'totalProduk',
            'produkBaru',
            'produkLowStock',
            'totalOrder',
            'totalPembayaran',
            'grafikBulan',
            'grafikPendapatan',
            'produkLarisNama',
            'produkLarisJumlah',
            'latestOrders',
            'logs'
        ));
    }
}
