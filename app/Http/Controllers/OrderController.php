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
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\ActivityLog;

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
            'image_path' => $product->image_path
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
        if (empty(session('cart'))) {
            return redirect()->route('customer.order')->with('error', 'Keranjang belanja kosong');
        }

        return view('userMenu.orderSaya.checkout', [
            'cart' => session('cart'),
            'total' => $this->calculateTotal()
        ]);
    }

    /**
     * Menampilkan riwayat pesanan
     */
    public function history()
    {
        $orders = auth()->user->orders()
            ->with(['items.product', 'payment'])
            ->latest()
            ->paginate(10);

        return view('userMenu.historyOrder.index', ['orders' => $orders]);
    }

    /**
     * Memproses submit order dari checkout
     */
    public function submit(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cod,transfer,midtrans',
            'shipping_address' => 'required|string',
            'bank_name' => 'required_if:payment_method,transfer',
            'payment_proof' => 'nullable|image|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $cart = session('cart');
            if (!$cart || count($cart) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang kosong. Tambahkan produk terlebih dahulu.'
                ], 422);
            }

            $total = 0;
            $validatedItems = [];

            foreach ($cart as $productId => $details) {
                $product = Product::find($productId);
                if (!$product) {
                    throw new \Exception("Produk dengan ID {$productId} tidak ditemukan.");
                }

                if ($product->stock < $details['quantity']) {
                    throw new \Exception("Stok produk {$product->nama_barang} tidak mencukupi.");
                }

                if ($product->harga_barang != $details['price']) {
                    throw new \Exception("Harga produk {$product->nama_barang} telah berubah.");
                }

                $total += $details['price'] * $details['quantity'];
                $validatedItems[] = [
                    'product_id' => $productId,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    'color' => $details['color'] ?? null,
                    'size' => $details['size'] ?? null,
                ];
            }

            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'INV-' . strtoupper(Str::random(8)),
                'total_amount' => $total,
                'shipping_address' => $request->shipping_address,
                'notes' => $request->notes,
                'status' => 'pending',
                'payment_method' => $request->payment_method
            ]);

            foreach ($validatedItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'color' => $item['color'],
                    'size' => $item['size'],
                ]);

                Product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);
            }

            // Handle Transfer Bank Manual
            if ($request->payment_method === 'transfer') {
                $path = $request->file('payment_proof')
                    ? $request->file('payment_proof')->store('payment_proofs', 'public')
                    : null;

                Payment::create([
                    'order_id' => $order->id,
                    'bank_name' => $request->bank_name,
                    'amount' => $order->total_amount,
                    'proof_image' => $path,
                    'status' => 'pending',
                ]);
            }

            // Handle Midtrans jika aktif
            if ($request->payment_method === 'midtrans') {
                return $this->handleMidtransPayment($order);
            }

            // Notifikasi ke semua admin
            $admins = User::where('role', 1)->get();
            Notification::send($admins, new NewOrderNotification($order));

            // Kosongkan cart
            session()->forget('cart');

            DB::commit();

            return response()->json([
                'success' => true,
                'order_number' => $order->order_number,
                'redirect_url' => route('order.success', ['order' => $order->order_number])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
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

        return view('userMenu.orderSaya.success', compact('order'));
    }

    /**
     * Menghitung total belanja dari session cart
     */
    private function calculateTotal()
    {
        $total = 0;
        foreach (session('cart') as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }
        return $total;
    }

    private function handleMidtransPayment($order)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ]
        ];

        $snapToken = Snap::getSnapToken($params);
        return response()->json([
            'success' => true,
            'redirect_url' => "https://app.sandbox.midtrans.com/snap/v2/vtweb/{$snapToken}"
        ]);
    }
}
