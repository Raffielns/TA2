<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewPaymentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'message' => 'Pembayaran baru untuk pesanan #' . $this->order->order_number,
            'type' => 'payment'
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'message' => 'Pembayaran baru untuk pesanan #' . $this->order->order_number,
            'type' => 'payment'
        ]);
    }
}
