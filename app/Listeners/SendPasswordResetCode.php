<?php

namespace App\Listeners;

use App\Events\PasswordResetRequested;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetCode;
use Illuminate\Support\Facades\Cache;

class SendPasswordResetCode implements ShouldQueue
{
    public function handle(PasswordResetRequested $event)
    {
        Cache::put(
            'password_reset_code_' . $event->user->id,
            $event->verificationCode,
            now()->addMinutes(5)
        );

        Mail::to($event->user->email)
            ->queue(new PasswordResetCode($event->verificationCode));
    }
}