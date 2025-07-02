<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\DB;
use Midtrans;
use App\Models\ActivityLog;
use App\Models\Cart;
use App\Models\Review;
use Illuminate\Support\Facades\Http;

Midtrans\Config::$serverKey = config('midtrans.server_key');
Midtrans\Config::$isProduction = false;
Midtrans\Config::$isSanitized = true;
Midtrans\Config::$is3ds = true;

class OrderController extends Controller
{
    /**
     * Menampilkan halaman order utama
     */
    public function index()
    {
        return view('userMenu.orderSaya.index');
    }

    /**
     * Menyimpan item ke keranjang belanja
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'id' => 'required',
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric|min:1',
            'color' => 'nullable',
            'size' => 'nullable'
        ]);

        $product = Product::findOrFail($request->product_id);

        $cartItem = [
            'id' => $product->code ?? $product->id,
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $request->quantity,
            'color' => $request->color,
            'size' => $request->size,
            'image_path' => $product->image_path,
        ];

        $cart = session()->get('cart', []);
        $cart[] = $cartItem;
        session()->put('cart', $cart);

        // Log aktivitas penambahan ke keranjang
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Melakukan checkout pesanan',
            'target' => 'Order #' . Str::random(8) // Simulasi order number
        ]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');

        // return redirect()->route('customer.order')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Mengupdate jumlah item dalam keranjang
     */
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Keranjang berhasil diupdate');
    }

    /**
     * Menghapus item dari keranjang
     */
    public function destroy($id)
    {
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang');
    }

    /**
     * Menampilkan halaman checkout
     */
    public function checkout()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get();

        $total_amount = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        // dd($total_amount);

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.order')->with('error', 'Keranjang belanja kosong');
        }

        return view('userMenu.orderSaya.checkout', [
            'cartItems' => $cartItems,
            'total' => $total_amount
        ]);
    }

    /**
     * Memproses submit order dari checkout
     */
    public function submit(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cod,transfer,midtrans',
            'shipping_address' => 'required|string|max:500',
            'telephone' => 'required',
            'notes' => 'nullable|string|max:255',
            'bank_name' => 'required_if:payment_method,transfer',
            'payment_proof' => 'required_if:payment_method,transfer|image|mimes:jpeg,png,jpg|max:2048',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        // dd($request->all());

        DB::beginTransaction();

        try {
            // Calculate total amount
            $total = 0;
            foreach ($request->products as $product) {
                $total += $product['price'] * $product['quantity'];
            }

            // Prepare order data
            $orderData = [
                'user_id' => auth()->id(),
                'order_number' => 'INV-' . strtoupper(Str::random(8)),
                'total_amount' => $total,
                'shipping_address' => $request->shipping_address,
                'notes' => $request->notes,
                'telephone' => $request->telephone,
                'status' => 'pending',
                'payment_method' => $request->payment_method
            ];

            // Add bank transfer details if payment method is transfer
            if ($request->payment_method === 'transfer') {
                $path = $request->file('payment_proof')->store('payment_proofs', 'public');

                $orderData['bank_name'] = $request->bank_name;
                $orderData['payment_proof'] = $path; // Stores the full path with hashed name
            }

            // Create order
            $order = Order::create($orderData);

            // Create order items and check stock
            foreach ($request->products as $product) {
                $dbProduct = Product::findOrFail($product['product_id']);

                if ($dbProduct->stock < $product['quantity']) {
                    throw new \Exception("Stok produk {$dbProduct->name} tidak mencukupi");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'color' => $product['color'] ?? null,
                    'size' => $product['size'] ?? null,
                ]);

                // Decrement stock
                $dbProduct->decrement('stock', $product['quantity']);
            }

            // Handle other payment methods
            switch ($request->payment_method) {
                case 'midtrans':

                    $params = [
                        'transaction_details' => [
                            'order_id' => $order->order_number,
                            'gross_amount' => $order->total_amount,
                        ],
                        'customer_details' => [
                            'first_name' => auth()->user()->name,
                            'email' => auth()->user()->email,
                        ],
                        'callbacks' => [
                            'finish' => url("/user/statusOrder/" . $order->order_number),
                            'error' => url("/user/statusOrder/" . $order->order_number),
                            'expire' => url("/user/statusOrder/" . $order->order_number),
                        ]

                    ];

                    $snapToken = \Midtrans\Snap::getSnapToken($params);

                    $order->snap_token = $snapToken;
                    $order->save();
                    break;

                case 'cod':
                    break;
            }

            // Clear cart
            Cart::where('user_id', auth()->id())->delete();

            DB::commit();
            return redirect()->route('order.status', ['order' => $order->order_number])
                ->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    /**
     * Menampilkan halaman sukses setelah order
     */
    public function success($order)
    {
        $order = Order::where('order_number', $order)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('userMenu.orderSaya.succes', compact('order'));
    }

    /**
     * Menampilkan status pesanan
     */
    public function status()
    {
        $orders = Order::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('userMenu.statusOrder.index', compact('orders'));
    }

    /**
     * Menampilkan detail status pesanan
     */
    public function showDetail($orderId)
    {
        $order = Order::where('id', $orderId) // Ubah ke id
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $order->load(['items.product']);


        // dd($order);
        return view('userMenu.statusOrder.show', compact('order'));
    }

    public function handleCallback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed != $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::where('id', $request->order_id)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($request->transaction_status === 'capture' || $request->transaction_status === 'settlement') {
            $order->status = 'diproses';
            $order->save();
        }

        return response()->json(['message' => 'OK']);
    }

    public function handlePaymentSuccess(Request $request)
    {
        // Validasi input
        $request->validate([
            'snap_token' => 'required|string',
            'order_id' => 'required|exists:orders,id'
        ]);

        try {
            DB::beginTransaction();

            // Cari order berdasarkan snap_token DAN order_id (double check)
            $order = Order::where('snap_token', $request->snap_token)
                ->where('id', $request->order_id)
                ->firstOrFail();

            // Update status order
            $order->update([
                'status' => 'diproses',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status order berhasil diupdate',
                'redirect_url' => '/user/statusOrder/' . $order->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal update status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyPayment($orderId)
    {
        $verify = Order::where('id', $orderId)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        $verify->status = 'diproses';
        $verify->save();

        $orders = Order::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('userMenu.statusOrder.index', compact('orders'));
    }

    /**
     * Menampilkan halaman history order
     */
    public function history()
    {
        $orderItems = OrderItem::with(['order', 'product'])
            ->whereHas('order', function ($query) {
                $query->where('user_id', auth()->id())
                    ->where('status', 'selesai');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('userMenu.historyOrder.index', compact('orderItems'));
    }

    /**
     * Menampilkan halaman review order
     */
    // public function review()
    // {
    //     $reviews = Review::with(['order', 'product'])
    //         ->where('user_id', auth()->id())
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     return view('userMenu.reviewSaya.index', compact('reviews'));
    // }

    public function showReviewForm(Order $order, Product $product)
    {
        // Pastikan order milik user yang login dan status selesai
        if ($order->user_id != auth()->id() || $order->status != 'selesai') {
            abort(403, 'Unauthorized action.');
        }

        // Pastikan product ada di order tersebut
        $orderItem = $order->items()->where('product_id', $product->id)->first();
        if (!$orderItem) {
            abort(404, 'Product not found in this order.');
        }

        return view('userMenu.reviewSaya.create', [
            'order' => $order,
            'product' => $product
        ]);
    }

    public function submitReview(Request $request, Order $order, Product $product)
    {
        // Validasi
        if ($order->user_id != auth()->id() || $order->status != 'selesai') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:500'
        ]);

        // Buat review baru
        $review = Review::create([
            'user_id' => auth()->id(),
            'order_id' => $order->id,
            'product_id' => $product->id,
            'rating' => $request->rating,
            'review' => $request->review
        ]);

        return redirect()->route('order.history')
            ->with('success', 'Terima kasih atas review Anda!');
    }

    public function showReview(Order $order, Product $product)
    {
        // Pastikan order milik user yang login
        if ($order->user_id != auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Ambil review yang sudah dibuat
        $review = Review::where('order_id', $order->id)
            ->where('product_id', $product->id)
            ->firstOrFail();

        return view('userMenu.reviewSaya.show', [
            'order' => $order,
            'product' => $product,
            'review' => $review
        ]);
    }
}
