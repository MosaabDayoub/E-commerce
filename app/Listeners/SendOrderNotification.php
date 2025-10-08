<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Jobs\SendOrderEmailsJob;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderNotification implements ShouldQueue
{
    public function handle(OrderCreated $event): void
    {
        SendOrderEmailsJob::dispatch($event->order);
    }
}