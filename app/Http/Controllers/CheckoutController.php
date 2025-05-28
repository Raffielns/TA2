<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
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

        return view('userMenu.orderSaya.checkout'); // Sesuaikan dengan path view checkout Anda
    }
}
