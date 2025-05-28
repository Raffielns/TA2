<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        // Optional: log payload
        Log::info('Midtrans Webhook:', $payload);

        $orderId = $payload['order_id'];
        $transactionStatus = $payload['transaction_status'];
        $fraudStatus = $payload['fraud_status'] ?? null;

        $order = Order::where('order_number', $orderId)->first();
        if (!$order) {
            Log::warning("Order not found for ID: {$orderId}");
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Update status sesuai status transaksi Midtrans
        switch ($transactionStatus) {
            case 'capture':
            case 'settlement':
                $order->status = 'processing';

                Payment::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'amount' => $order->total_amount,
                        'status' => 'verified',
                        'bank_name' => $payload['va_numbers'][0]['bank'] ?? 'midtrans'
                    ]
                );
                break;

            case 'pending':
                $order->status = 'pending';
                Payment::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'amount' => $order->total_amount,
                        'status' => 'pending',
                        'bank_name' => $payload['va_numbers'][0]['bank'] ?? 'midtrans'
                    ]
                );
                break;

            case 'deny':
            case 'cancel':
            case 'expire':
                $order->status = 'cancelled';
                Payment::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'amount' => $order->total_amount,
                        'status' => 'rejected',
                        'bank_name' => $payload['va_numbers'][0]['bank'] ?? 'midtrans'
                    ]
                );
                break;
        }

        $order->save();

        return response()->json(['message' => 'Webhook handled'], 200);
    }
}
