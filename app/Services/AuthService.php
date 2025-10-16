<?php

namespace App\Services;

use App\Models\User;
use App\Models\Admin;
use App\Events\PasswordResetRequested;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthService
{
    const PASSWORD_RESET_KEY = 'password_reset_code_';
    const TOKEN_NAME = 'auth-token';

    public function __construct() {}

    /**
     * Login - 
     */
    public function login(string $email, string $password, string $guard = 'user'): ?object
    {
        $user = $this->findUserByEmail($email, $guard);

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        return $user;
    }

    /**
     * Create authentication token 
     */
    public function createAuthToken(object $user, string $guard = 'user'): string
    {
        $tokenName = $guard . '-' . self::TOKEN_NAME;
        return $user->createToken($tokenName)->plainTextToken;
    }

    /**
     * Register 
     */
    public function register(string $name,string $email, string $password, string $guard = 'user'): object
    {
        $model = $this->getModel($guard);
        
        return $model::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);
    }

    /**
     * Logout 
     */
    public function logout(object $user): void
    {
        $user->tokens()->delete();
    }

    /**
     * Update profile
     */
    public function updateProfile(object $user, array $data): bool
    {
        return $user->update($data);
    }

    /**
     * Change password
     */
    public function changePassword(object $user, string $currentPassword, string $newPassword): array
    {
        if (!Hash::check($currentPassword, $user->password)) {
            return ['success' => false, 'message' => 'Current password is incorrect'];
        }

        if (Hash::check($newPassword, $user->password)) {
            return ['success' => false, 'message' => 'New password must be different from current password'];
        }

        $user->update(['password' => $newPassword]);

        return ['success' => true, 'message' => 'Password changed successfully'];
    }

    /**
     * Request password reset code 
     */
    public function requestPasswordResetCode(object $user, string $guard = 'user'): array
    {
        $verificationCode = Str::random(6);
        
        Cache::put(self::PASSWORD_RESET_KEY . $guard . '_' . $user->id, $verificationCode, now()->addMinutes(10));

        event(new PasswordResetRequested($user, $verificationCode));

        return ['success' => true, 'message' => 'Verification code sent to your email'];
    }

    /**
     * Reset password with code 
     */
    public function resetPasswordWithCode(object $user, string $code, string $newPassword, string $guard = 'user'): array
    {
        $cachedCode = Cache::get(self::PASSWORD_RESET_KEY . $guard . '_' . $user->id);

        if (!$cachedCode || $cachedCode !== $code) {
            return ['success' => false, 'message' => 'Invalid or expired verification code'];
        }

        $user->update(['password' => $newPassword]);
        Cache::forget(self::PASSWORD_RESET_KEY . $guard . '_' . $user->id);

        return ['success' => true, 'message' => 'Password reset successfully'];
    }

    /**
     * Delete avatar
     */
    public function deleteAvatar(object $user): bool
    {
        if ($user->getFirstMedia('avatar')) {
            $user->clearMediaCollection('avatar');
            return true;
        }

        return false;
    }

    /**
     * Delete account 
     */
    public function deleteAccount(object $user): bool
    {
        DB::beginTransaction();

        try {
            $user->tokens()->delete();
            $user->delete();
            
            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete account: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Find user by email 
     */
    public function findUserByEmail(string $email, string $guard = 'user'): ?object
    {
        $model = $this->getModel($guard);
        return $model::where('email', $email)->first();
    }

    /**
     * Get model based on guard
     */
    private function getModel(string $guard): string
    {
        return match($guard) {
            'admin' => Admin::class,
            'user' => User::class,
            default => User::class
        };
    }
}