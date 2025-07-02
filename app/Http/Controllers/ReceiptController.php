<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Receipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReceiptController extends Controller
{
    public function generateReceipt(Order $order)
    {
        // Generate receipt number
        $receiptNumber = $order->order_number . '-RCPT-' . date('Ymd') . '-' . Str::random(6);

        // Load data
        $order->load(['user', 'items.product']);

        // Generate PDF
        $pdf = Pdf::loadView('adminMenu.pesanan.receipt', compact('order', 'receiptNumber'));

        $filename = 'receipts/' . $receiptNumber . '.pdf';


        // Create receipt record
        $receipt = new Receipt();
        $receipt->order_id = $order->id;
        $receipt->receipt_number = $receiptNumber;
        $receipt->file_path = $filename;


        if ($order->receipt == null) {
            $receipt->save();
            // Storage::put('public/' . $filename, $pdf->output());
        }


        // Untuk non-AJAX, tetap kembalikan PDF
        return $pdf->stream($receiptNumber . '.pdf');
    }

    public function downloadReceipt(Receipt $receipt)
    {
        if (!Storage::exists('public/' . $receipt->file_path)) {
            abort(404);
        }

        return Storage::download('public/' . $receipt->file_path, $receipt->receipt_number . '.pdf');
    }
}
