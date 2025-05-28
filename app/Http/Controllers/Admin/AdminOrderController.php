<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class AdminOrderController extends Controller
{
    // AdminOrderController.php
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $orders = Order::with(['user', 'payment'])->latest()->get();
        return view('adminMenu.pesanan.index', compact('orders'));
    }


    public function show(Order $order)
    {
        $order->load(['user', 'items.product', compact('orders')]);
        return view('adminMenu.pesanan.show', compact('order'));
        // // Mark notification as read
        // auth()->user()->unreadNotifications()
        //     ->where('data->order_id', $order->id)
        //     ->update(['read_at' => now()]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        // Log the order status update
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Mengubah status pesanan ke "' . $order->status . 'Berhasil cuy',
            'target' => 'Order #' . $order->order_number
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbarui');
    }
}
