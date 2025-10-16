<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;


class RegisterController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request)
    {   

        $user = $this->authService->register($request->all(), 'user');
        
        $token = $this->authService->createAuthToken($user, 'user');

        return ResponseHelper::success([
            'user' => new UserResource($user),
            'token' => $token,
        ], 'Registration successful');
    }
}