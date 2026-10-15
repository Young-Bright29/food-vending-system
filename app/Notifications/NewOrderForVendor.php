<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Notification;

class NewOrderForVendor extends Notification
{
    public function __construct(
        public Order $order,
        public array $items,
        public float $vendorTotal
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,

            'order_number' => $this->order->order_number,

            'customer_name' => $this->order->user?->name
                ?? 'Customer',

            'delivery_address' => $this->order->delivery_address,

            'phone' => $this->order->phone,

            'items' => $this->items,

            'total_amount' => round($this->vendorTotal, 2),

            'status' => $this->order->status,

            'message' => 'New order '
                . $this->order->order_number
                . ' received.',
        ];
    }
}
