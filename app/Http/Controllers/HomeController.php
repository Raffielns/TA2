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
        // $produkBaru = Product::whereMonth('created_at', now()->month)->count();
        // $produkLowStock = Product::where('stock', '<=', 10)->count();
        $totalOrder = Order::count();
        // $totalPembayaran = Payment::where('status', 'verified')->sum('amount');

        // Ambil 5 log aktivitas terbaru
        $logs = ActivityLog::with('user')->latest('created_at')->limit(5)->get();

        // Pesanan terbaru (5 terakhir)
        $latestOrders = Order::latest()->take(5)->with('user')->get();

        // Data untuk grafik pendapatan 6 bulan terakhir
        $monthlyRevenue = [];
        $months = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            $monthlyRevenue[] = Order::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_amount');
        }

        // Data untuk grafik produk terlaris bulan ini
        $topProducts = Product::select('products.id', 'products.nama_barang as name')
            ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as total_sold')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', function ($join) {
                $join->on('order_items.order_id', '=', 'orders.id')
                    ->whereMonth('orders.created_at', now()->month);
            })
            ->groupBy('products.id', 'products.nama_barang')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return view('adminMenu.dashboard', compact(
            'pendapatan',
            'totalProduk',
            // 'produkBaru',
            // 'produkLowStock',
            'totalOrder',
            // 'totalPembayaran',
            'latestOrders',
            'logs',
            'monthlyRevenue',
            'months',
            'topProducts'
        ));
    }
}
