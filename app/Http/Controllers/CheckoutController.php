<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
        Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function index()
    {
        // Cek apakah user sudah login
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk melanjutkan ke checkout');
        }

        // Pastikan cart tidak kosong
        if(empty(session('cart'))) {
            return redirect()->route('order.index')->with('error', 'Keranjang belanja kosong');
        }

        // Dapatkan data keranjang belanja dari session
        $cart = session('cart');

        // Hitung total belanja
        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Siapkan data transaksi untuk Midtrans
        $transaction_details = [
            'order_id' => uniqid(), // Generate unique order ID
            'gross_amount' => $total, // Total pembayaran
        ];

        // Data customer
        $customer_details = [
            'first_name' => auth()->user()->name,
            'email' => auth()->user()->email,
            // Tambahkan data customer lainnya jika diperlukan
        ];

        // Data item (opsional, tapi disarankan)
        $items = [];
        foreach($cart as $id => $details) {
            $items[] = [
                'id' => $id,
                'price' => $details['price'],
                'quantity' => $details['quantity'],
                'name' => $details['name']
            ];
        }

        // Parameter transaksi
        $params = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $items,
        ];

        try {
            // Dapatkan Snap Token dari Midtrans
            $snapToken = Midtrans\Snap::getSnapToken($params);

            // Kirim snap token ke view
            return view('userMenu.orderSaya.checkout', [
                'snapToken' => $snapToken,
                'total' => $total,
                'items' => $items
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: '.$e->getMessage());
        }
    }
}
