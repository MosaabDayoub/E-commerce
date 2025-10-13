<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Events\PasswordResetRequested;
use Illuminate\Support\Facades\Request;

class ProfileController extends Controller
{
    /**
     * show profile
     */
    public function show(ProfileRequest $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            return ResponseHelper::success(new UserResource($user), 'Profile retrieved successfully');
        } catch (\Exception) {
            return ResponseHelper::error('Failed to retrieve profile');
        }
    }

    /**
     * update profile
     */
    public function update(ProfileRequest $request): JsonResponse
    {
        try {
            $user = $request->user();
            $validatedData = $request->validated();
            
            if (isset($validatedData['current_password'])) {
                if (!Hash::check($validatedData['current_password'], $user->password)) {
                    return ResponseHelper::error('Current password is incorrect');
                }
                
                unset($validatedData['current_password']);
            }
            
            if ($request->hasFile('avatar')) {
                if ($user->getFirstMedia('avatar')) {
                    $user->getFirstMedia('avatar')->delete();
                }
                
                $user->addMediaFromRequest('avatar')
                    ->toMediaCollection('avatar');
                
                unset($validatedData['avatar']);
            }
            
            $user->update($validatedData);

            return ResponseHelper::success(new UserResource($user), 'Profile updated successfully');

        } catch (\Exception) {
            return ResponseHelper::error('Failed to update profile');
        }
    }

    /**
     * change Password
     */
    public function changePassword(ProfileRequest $request): JsonResponse
    {
        try {

            $user = $request->user();

            if (!Hash::check($request->current_password, $user->password)) {
                return ResponseHelper::error('Current password is incorrect');
            }

            if (Hash::check($request->new_password, $user->password)) {
                return ResponseHelper::error('New password must be different from current password');
            }

            $user->update([
                'password' => $request->new_password
            ]);
            
            return ResponseHelper::successMessage('Password changed successfully');

        } catch (\Exception) {
            return ResponseHelper::error('Failed to change password');
        }
    }

   /**
 * Reset password with verification code
 */
public function resetPassword(ProfileRequest $request): JsonResponse
{
    try {
        
        $user = $request->user();

        if (!$user) {
            return ResponseHelper::error('User not found');
        }

        $cachedCode = Cache::get('password_reset_code_' . $user->id);

        if (!$cachedCode || $cachedCode !== $request->code) {
            return ResponseHelper::error('Invalid or expired verification code');
        }
        $user->update([
            'password' => $request->password
        ]);

        Cache::forget('password_reset_code_' . $user->id);

        return ResponseHelper::successMessage('Password reset successfully');

    } catch (\Exception) {
        return ResponseHelper::error('Failed to reset password');    
    }
}
    /**
     * delete profile image
     */
    public function deleteAvatar(ProfileRequest $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            if ($user->getFirstMedia('avatar')) {
                $user->getFirstMedia('avatar')->delete();
            }

            return ResponseHelper::success(new UserResource($user), 'Avatar deleted successfully');     
        } catch (\Exception) {
            return ResponseHelper::error('Failed to delete avatar'); 
        }
    }

    /**
 * Request password reset code
 */
public function requestResetCode( Request $request): JsonResponse
{
    try {
        
        $user = $request->user();

        if (!$user) {
            return ResponseHelper::error('User not found');
        }

        $verificationCode = Str::random(6);

        event(new PasswordResetRequested($user, $verificationCode));

        return ResponseHelper::successMessage('Verification code sent to your email');

    } catch (\Exception) {
        return ResponseHelper::error('Failed to send verification code');    
    }
}
}