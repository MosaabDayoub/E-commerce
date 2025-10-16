<?php

namespace App\Http\Controllers\User;

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
        $user = $this->authService->login($request->email, $request->password,'user');

        if (!$user) {
            return ResponseHelper::error('The provided credentials are incorrect.');
        }

        $token = $this->authService->createAuthToken($user,'user');

        return ResponseHelper::success([
            'user' => new UserResource($user),
            'token' => $token,
        ], 'Login successfully');
    }
}