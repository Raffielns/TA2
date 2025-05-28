<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;    // Untuk relakasi dengan model Order
use Illuminate\Http\Request;
use App\Models\ActivityLog; // Untuk mencatat aktivitas admin

class AdminPaymentController extends Controller
{
    // AdminPaymentController.php
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $payments = Payment::with('order')->latest()->get();
        return view('adminMenu.transaction.index', compact('payments'));
    }


    public function verify(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected'
        ]);

        $payment->update(['status' => $request->status]);

        // Update order status if payment is verified
        if ($request->status == 'verified') {
            $payment->order->update(['status' => 'processing']);
        }
        // Log the payment verification
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Memverifikasi pembayaran',
            'target' => 'Order #' . $payment->order->order_number
        ]);

        //log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Menyetujui pembayaran customer',
            'target' => 'Order #' . $payment->order->order_number
        ]);

        return back()->with('success', 'Status pembayaran berhasil diperbarui');
    }
}
