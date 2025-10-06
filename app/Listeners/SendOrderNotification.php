<?php
// app/Listeners/SendOrderNotification.php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Mail\OrderCreatedAdminNotification;
use Illuminate\Support\Facades\Mail;

class SendOrderNotification
{
    public function handle(OrderCreated $event): void
    {

        Mail::to('admin@example.com')->send(
            new OrderCreatedAdminNotification($event->order)
        );
    }
}