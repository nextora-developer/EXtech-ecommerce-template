<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminOrderNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order->load('items'); // 顺便预载 items
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New order received: ' . $this->order->order_no)
                    ->markdown('emails.orders.admin_notification');
    }
}
