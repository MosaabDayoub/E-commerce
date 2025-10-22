<?php
// app/Observers/AdminObserver.php

namespace App\Observers;

use App\Models\Admin;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\AdminPasswordChangedAlert;

class AdminObserver
{
  
    public function updated(Admin $admin)
    {
        if ($admin->wasChanged('password')) {
            $this->sendPasswordChangeAlert($admin);
        }
    }

    private function sendPasswordChangeAlert(Admin $admin)
    {
        try {
            $superAdmin = Admin::getSuperAdmin();
            
            if (!$superAdmin) {
                Log::warning('No super admin found to send password change alert');
                return;
            }

            if ($admin->id === $superAdmin->id) {
                Log::info('Super admin changed their own password - no alert sent', [
                    'super_admin_id' => $superAdmin->id
                ]);
                return;
            }

            Mail::to($superAdmin->email)->send(new AdminPasswordChangedAlert($admin));
            
            Log::info('Admin password change alert sent to super admin', [
                'admin_id' => $admin->id,
                'admin_email' => $admin->email,
                'super_admin_id' => $superAdmin->id,
                'changed_at' => now()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send admin password change alert', [
                'admin_id' => $admin->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function deleting(Admin $admin)
    {
        if ($admin->isSuperAdmin()) {
            Log::warning('Attempt to delete super admin blocked', [
                'admin_id' => $admin->id,
                'email' => $admin->email
            ]);
            return false;
        }

        Log::warning('Admin deletion initiated', [
            'admin_id' => $admin->id,
            'email' => $admin->email
        ]);
    }

    public function deleted(Admin $admin)
    {
        Log::info('Admin account deleted', [
            'admin_id' => $admin->id,
            'email' => $admin->email,
            'deleted_at' => now()
        ]);
    }
}