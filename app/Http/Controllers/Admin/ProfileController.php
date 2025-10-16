<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\adminResource;
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
            $admin = $request->user('api_admin');
            return ResponseHelper::success(new adminResource($admin), 'Profile retrieved successfully');
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
            $admin = $request->user('api_admin');
            $validatedData = $request->validated();
              
            $this->authService->updateProfile($admin, $validatedData);
            
            if ($request->hasFile('avatar')) {
                if ($admin->getFirstMedia('avatar')) {
                    $admin->getFirstMedia('avatar')->delete();
                }
                
                $admin->addMediaFromRequest('avatar')
                    ->toMediaCollection('avatar');
            }

            return ResponseHelper::success(new adminResource($admin->fresh()), 'Profile updated successfully');

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
            $admin = $request->user('api_admin');

            $result = $this->authService->changePassword(
                $admin,
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
            $admin = $this->authService->finduserByEmail($request->email,'admin');

            $result = $this->authService->requestPasswordResetCode($admin,'admin');

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
            $admin = $this->authService->finduserByEmail($request->email,'admin');

            $result = $this->authService->resetPasswordWithCode(
                $admin,
                $request->code,
                $request->password,
                'admin'
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
            $admin = $request->user('api_admin');
            
            $deleted = $this->authService->deleteAvatar($admin);

            if (!$deleted) {
                return ResponseHelper::error('No avatar found to delete');
            }

            return ResponseHelper::success(new adminResource($admin), 'Avatar deleted successfully');     
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to delete avatar'); 
        }
    }
}