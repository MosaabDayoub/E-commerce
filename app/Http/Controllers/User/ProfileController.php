<?php

namespace App\Http\Controllers\User;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProfileRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ProfileController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * show profile
     */
    public function show(ProfileRequest $request): JsonResponse
    {
        try {
            $user = $request->user('api_user');
            return ResponseHelper::success(new UserResource($user), 'Profile retrieved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to retrieve profile');
        }
    }

    /**
     * update profiles
     */
    public function update(ProfileRequest $request): JsonResponse
    {
        try {
            $user = $request->user('api_user');
            $validatedData = $request->validated();
              
            $this->authService->updateProfile($user, $validatedData);
            
            if ($request->hasFile('avatar')) {
                if ($user->getFirstMedia('avatar')) {
                    $user->getFirstMedia('avatar')->delete();
                }
                
                $user->addMediaFromRequest('avatar')
                    ->toMediaCollection('avatar');
            }

            return ResponseHelper::success(new UserResource($user->fresh()), 'Profile updated successfully');

        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to update profile');
        }
    }

    /**
     * change password
     */
    public function changePassword(ProfileRequest $request): JsonResponse
    {
        try {
            $user = $request->user('api_user');

            $result = $this->authService->changePassword(
                $user,
                $request->current_password,
                $request->new_password
            );

            if (!$result['success']) {
                return ResponseHelper::error($result['message']);
            }

            return ResponseHelper::successMessage($result['message']);

        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to change password');
        }
    }

    /**
     * request reset code
     */
    public function requestResetCode(Request $request): JsonResponse
    {
        try {
            $user = $this->authService->findUserByEmail($request->email,'user');

            $result = $this->authService->requestPasswordResetCode($user,'user');

            if (!$result['success']) {
                return ResponseHelper::error($result['message']);
            }

            return ResponseHelper::successMessage($result['message']);

        } catch (\Exception) {
            return ResponseHelper::error('Failed to send verification code');    
        }
    }

    /**
     * reset password
     */
    public function resetPassword(ProfileRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->findUserByEmail($request->email,'user');

            $result = $this->authService->resetPasswordWithCode(
                $user,
                $request->code,
                $request->password,
                'user'
            );

            if (!$result['success']) {
                return ResponseHelper::error($result['message']);
            }

            return ResponseHelper::successMessage($result['message']);

        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to reset password');    
        }
    }

    /**
     * delete avatar from profile
     */
    public function deleteAvatar(ProfileRequest $request): JsonResponse
    {
        try {
            $user = $request->user('api_user');
            
            $deleted = $this->authService->deleteAvatar($user);

            if (!$deleted) {
                return ResponseHelper::error('No avatar found to delete');
            }

            return ResponseHelper::success(new UserResource($user), 'Avatar deleted successfully');     
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to delete avatar'); 
        }
    }
}