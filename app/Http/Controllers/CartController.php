<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $product = [
            'id' => $request->id,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => 1,
        ];

        $cart = session()->get('cart', []);
        $cart[$product['id']] = $product;
        session(['cart' => $cart]);

        return redirect()->route('customer.order')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function viewCart()
    {
        $cart = session('cart', []);
        return view('userMenu.orderSaya.index', compact('cart'));
    }

    // 🛠 Update quantity di cart
    public function updateCart(Request $request,$id)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$request->id])) {
            $cart[$request->id]['quantity'] = $request->quantity;
            session(['cart' => $cart]);
        }
        return redirect()->route('customer.order')->with('success', 'Jumlah produk diperbarui!');
    }

    // 🗑 Remove item dari cart
    public function removeFromCart(Request $request)
    {
        $cart = session()->get('cart', []);
        if(isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session(['cart' => $cart]);
        }
        return redirect()->route('customer.order')->with('success', 'Produk dihapus dari keranjang.');
    }
}
