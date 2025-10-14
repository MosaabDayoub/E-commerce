<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
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
            $user = $request->user();
            return ResponseHelper::success(new UserResource($user), 'Admin profile retrieved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to retrieve admin profile');
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
            
            $this->authService->updateProfile($user, $validatedData);
            
            if ($request->hasFile('avatar')) {
                if ($user->getFirstMedia('avatar')) {
                    $user->clearMediaCollection('avatar');
                }
                
                $user->addMediaFromRequest('avatar')
                    ->toMediaCollection('avatar');
            }

            return ResponseHelper::success(new UserResource($user->fresh()), 'Admin profile updated successfully');

        } catch (\Exception) {
            return ResponseHelper::error('Failed to update admin profile');
        }
    }

    /**
     * change password
     */
    public function changePassword(ProfileRequest $request): JsonResponse
    {
        try {
            $user = $request->user();

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
}