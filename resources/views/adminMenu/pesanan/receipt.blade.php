<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Struk Pembayaran #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 13px;
            line-height: 1.4;
            color: #333;
            max-width: 580px;
            margin: 0 auto;
            padding: 15px;
            background-color: #f9f9f9;
        }

        .invoice-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 25px;
            border-top: 4px solid #4a6cf7;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #e0e0e0;
        }

        .header h3 {
            font-size: 22px;
            margin: 0 0 5px 0;
            color: #2c3e50;
        }

        .invoice-number {
            font-size: 14px;
            color: #7f8c8d;
            font-weight: 500;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-block {
            margin-bottom: 5px;
        }

        .info-title {
            font-weight: 600;
            color: #555;
            margin-bottom: 3px;
            font-size: 12px;
        }

        .info-value {
            color: #333;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        table th {
            background-color: #4a6cf7;
            color: white;
            padding: 8px 10px;
            text-align: left;
            font-weight: 500;
            font-size: 12px;
        }

        table td {
            border: 1px solid #e0e0e0;
            padding: 8px 10px;
            font-size: 12px;
        }

        table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            font-weight: 600;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #7f8c8d;
            padding-top: 15px;
            border-top: 1px dashed #e0e0e0;
        }

        .thank-you {
            font-size: 14px;
            color: #4a6cf7;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .company-info {
            margin-top: 15px;
            font-size: 10px;
        }

        .payment-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 12px;
        }

        .paid {
            background-color: #d4edda;
            color: #155724;
        }

        .pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .logo {
            max-width: 120px;
            margin-bottom: 10px;
        }

        /* .barcode {
            margin: 15px 0;
            text-align: center;
            padding: 10px 0;
            border-top: 1px dashed #e0e0e0;
            border-bottom: 1px dashed #e0e0e0;
        } */

        .notes {
            margin-top: 20px;
            font-size: 12px;
            color: #666;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }

        .product-img {
            max-height: 80px;
            max-width: 60px;
            object-fit: contain;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-section .info-block {
            flex: 1;
            min-width: 200px;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="header">
            <img src="{{ public_path('img/Logo CV. ASS.png') }}" class="logo">
            <h3>INVOICE PEMBAYARAN</h3>
            <div class="invoice-number">No: {{ $receiptNumber }}</div>
        </div>

        <div class="info-section">
            <div class="info-block">
                <div class="info-title">Tanggal Pesanan</div>
                <div class="info-value">{{ $order->created_at->format('d M Y H:i') }}</div>
            </div>
            <div class="info-block">
                <div class="info-title">Pelanggan</div>
                <div class="info-value">{{ $order->user->name }}</div>
            </div>
            <div class="info-block">
                <div class="info-title">No. Telephone</div>
                <div class="info-value">{{ $order->telephone }}</div>
            </div>
            <div class="info-block">
                <div class="info-title">Status Pembayaran</div>
                <div class="info-value">
                    @if ($order->payment_method == 'cod')
                        <span class="payment-status pending">COD</span>
                    @else
                        <span class="payment-status paid">Lunas</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="info-section">
            <div class="info-block">
                <div class="info-title">Informasi Pengiriman</div>
                <div class="info-value">{{ $order->shipping_address }}</div>
            </div>
            <div class="info-block">
                <div class="info-title">Catatan Pesanan</div>
                <div class="info-value">
                    @if ($order->notes)
                        {{ $order->notes }}
                    @else
                        Tidak ada catatan.
                    @endif
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <img src="{{ public_path('storage/files/' . $item->product->encrypted_filename) }}"
                                        class="product-img">
                                    <span>{{ $item->product->nama_barang }}</span>
                                </div>
                            </td>
                            <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-right">{{ $item->quantity }}</td>
                            <td class="text-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="3" class="text-right">Subtotal:</td>
                        <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="3" class="text-right">Total:</td>
                        <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="notes">
                @if ($order->payment_method == 'cod')
                    <strong>Catatan:</strong> Pembayaran dilakukan saat barang diterima. Pastikan jumlah yang dibayar
                    sesuai
                    dengan total yang tertera.
                @else
                    <strong>Catatan:</strong> Pembayaran sudah diterima secara penuh. Terima kasih telah berbelanja
                    dengan
                    kami.
                @endif
            </div>

            <div class="footer">
                <div class="thank-you">Terima kasih atas kepercayaan Anda!</div>
                <div class="company-info">
                    <strong>CV. Anugerah Sukses Sejahtera</strong><br>
                    Ketintang Barat III No.182, Surabaya, 60231<br>
                    Telp: (031) 8273618 | Email: anugerahsukses.s@gmail.com<br>
                    ©Raffielendiaz {{ date('Y') }}. All Rights Reserved.
                </div>
            </div>
        </div>
</body>

</html>
