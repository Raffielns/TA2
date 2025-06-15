<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // public function addToCart(Request $request)
    // {
    //     $product = [
    //         'id' => $request->id,
    //         'name' => $request->name,
    //         'price' => $request->price,
    //         'quantity' => 1,
    //     ];

    //     $cart = session()->get('cart', []);
    //     $cart[$product['id']] = $product;
    //     session(['cart' => $cart]);

    //     return redirect()->route('customer.order')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    // }
    public function addToCart(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id',
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'color' => 'nullable|string',
            'size' => 'nullable|string'
        ]);

        $product = Product::findOrFail($request->id);

        // Cek apakah produk sudah ada di cart user
        $existingCart = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->id)
            ->first();

        if ($existingCart) {
            // Update quantity jika produk sudah ada
            $existingCart->increment('quantity');
        } else {
            // Buat cart baru jika produk belum ada
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->id,
                'product_name' => $request->name,
                'price' => $request->price,
                'quantity' => 1,
                'color' => $request->color,
                'size' => $request->size
            ]);
        }

        return redirect()->route('customer.order')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    // public function viewCart()
    // {
    //     $cart = session('cart', []);
    //     return view('userMenu.orderSaya.index', compact('cart'));
    // }
    public function viewCart()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return view('userMenu.orderSaya.index', compact('cartItems'));
    }

    // 🛠 Update quantity di cart
    // public function updateCart(Request $request, $id)
    // {
    //     $cart = session()->get('cart', []);

    //     if (isset($cart[$request->id])) {
    //         $cart[$request->id]['quantity'] = $request->quantity;
    //         session(['cart' => $cart]);
    //     }
    //     return redirect()->route('customer.order')->with('success', 'Jumlah produk diperbarui!');
    // }
    public function updateCart(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        return redirect()->route('customer.order')->with('success', 'Jumlah produk diperbarui!');
    }

    // 🗑 Remove item dari cart
    // public function removeFromCart(Request $request)
    // {
    //     $cart = session()->get('cart', []);
    //     if (isset($cart[$request->id])) {
    //         unset($cart[$request->id]);
    //         session(['cart' => $cart]);
    //     }
    //     return redirect()->route('customer.order')->with('success', 'Produk dihapus dari keranjang.');
    // }

    public function removeFromCart($id)
    {
        $cartItem = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cartItem->delete();

        return redirect()->route('customer.order')->with('success', 'Produk dihapus dari keranjang.');
    }

    public function getCartTotal()
    {
        return Cart::where('user_id', Auth::id())
            ->sum(DB::raw('price * quantity'));
    }
}
