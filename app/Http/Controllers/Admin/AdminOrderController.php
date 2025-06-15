<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user'])->latest()->get();
        return view('adminMenu.pesanan.index', compact('orders'));
    }


    public function show(Order $order)
    {
        $order->load([
            'user',
            'items.product'
        ]);

        return view('adminMenu.pesanan.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,diproses,selesai,dibatalkan',
            'notes' => 'nullable|string|max:500'
        ]);

        // Update the order
        $order->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $order->notes
        ]);

        // Add notification logic if needed
        // $order->user->notify(new OrderStatusUpdated($order));

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Status pesanan berhasil diperbarui');
    }
}
