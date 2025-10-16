<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;

class LoginController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(LoginRequest $request)
    {
        $user = $this->authService->login($request->email, $request->password);

        if (!$user) {
            return ResponseHelper::error('The provided credentials are incorrect.');
        }

        $token = $this->authService->createAuthToken($user, 'admin-token', 'admin');

        return ResponseHelper::success([
            'user' => new UserResource($user),
            'token' => $token,
        ], 'Admin login successfully');
    }
}