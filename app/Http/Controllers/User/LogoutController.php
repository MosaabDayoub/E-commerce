<?php

namespace App\Http\Controllers\User;

use App\Helpers\ResponseHelper;
use App\Services\AuthService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(Request $request)
    {
        $this->authService->logout($request->user('api_user'));

        return ResponseHelper::successMessage('Logged out successfully');
    }
}