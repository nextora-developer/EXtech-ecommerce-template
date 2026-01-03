<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public string $oldStatus;
    public string $newStatus;

    public function __construct(Order $order, string $oldStatus, string $newStatus)
    {
        $this->order     = $order->load('items');
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function build()
    {
        return $this->subject('Your order status was updated: ' . $this->order->order_no)
            ->markdown('emails.orders.status_updated');
    }
}
