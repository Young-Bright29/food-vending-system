<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Support\Collection;
use Illuminate\Notifications\Notification;

class NewOrderForVendor extends Notification
{
    public function __construct(
        public Order $order,
        public Collection $items,
        public string $customerName
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $items = $this->items->map(function ($item) {
            return [
                'food_name' => $item->food?->name ?? 'Food item',
                'quantity' => (int) $item->quantity,
                'unit_price' => (float) $item->price,
            ];
        })->values();

        return [
            'title' => 'New Order Received',

            'message' => $this->customerName
                . ' placed order '
                . $this->order->order_number,

            'order_id' => $this->order->id,

            'order_number' => $this->order->order_number,

            'customer_name' => $this->customerName,

            'items' => $items->all(),

            'quantity' => (int) $items->sum('quantity'),

            'vendor_subtotal' => round(
                $items->sum(
                    fn ($item) =>
                        $item['unit_price'] * $item['quantity']
                ),
                2
            ),

            'url' => route('vendor.orders'),
        ];
    }
}
