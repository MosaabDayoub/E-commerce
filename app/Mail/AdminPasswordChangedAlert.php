<?php
// app/Mail/AdminPasswordChangedAlert.php

namespace App\Mail;

use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminPasswordChangedAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $admin;
    public $changeTime;

    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
        $this->changeTime = now();
    }

    public function build()
    {
        return $this->subject('Alert: Administrator Password Changed')
                    ->view('emails.admin_password_changed_alert')
                    ->with([
                        'adminName' => $this->admin->name,
                        'adminEmail' => $this->admin->email,
                        'changeTime' => $this->changeTime->format('Y-m-d H:i:s')
                    ]);
    }
}