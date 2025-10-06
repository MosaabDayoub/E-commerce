<?php
// app/Mail/OrderCreatedAdminNotification.php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCreatedAdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('new order' . $this->order->id)
                    ->view('emails.order_created')
                    ->with(['order' => $this->order]);
    }
}