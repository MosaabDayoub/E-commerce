<?php

namespace App\Services;

use App\Models\User;
use App\Events\PasswordResetRequested;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthService
{
    /**
     * /Login
     */
    public function login(string $email, string $password): ?User
    {
        $user = $this->findUserByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        return $user;
    }

    /**
     * create token for user 
     */
    public function createAuthToken(User $user, string $tokenName = 'auth-token'): string
    {
        return $user->createToken($tokenName)->plainTextToken;
    }

    /**
     * Register
     */
    public function register(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], 
        ]);
    }

    /**
     * Logout
     */
    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }

    /**
     * update profile
     */
    public function updateProfile(User $user, array $data): bool
    {
        
        return $user->update($data);
    }

    /**
     * change password
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): array
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
     * request reset password code
     */
    public function requestPasswordResetCode(User $user): array
    {
        $verificationCode = Str::random(6);

        event(new PasswordResetRequested($user, $verificationCode));

        return ['success' => true, 'message' => 'Verification code sent to your email'];
    }

    /**
     * reset passowrd with code 
     */
    public function resetPasswordWithCode(User $user, string $code, string $newPassword): array
    {
        $cachedCode = Cache::get('password_reset_code_' . $user->id);

        if (!$cachedCode || $cachedCode !== $code) {
            return ['success' => false, 'message' => 'Invalid or expired verification code'];
        }

        $user->update(['password' => $newPassword]);

        Cache::forget('password_reset_code_' . $user->id);

        return ['success' => true, 'message' => 'Password reset successfully'];
    }

    /**
     * delete avatar
     */
    public function deleteAvatar(User $user): bool
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
    public function deleteAccount(User $user): bool
    {
        DB::beginTransaction();

        try {
            $user->tokens()->delete();
            
            $user->delete();

            DB::commit();

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }


    /**
     * find user by email 
     */
    public function findUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}