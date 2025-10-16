<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Admin\DeleteAccountRequest;
use App\Services\AuthService;
use App\Http\Controllers\Controller;

class DeleteAccountController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(DeleteAccountRequest $request)
    {
        $user = $request->user('api_admin');

        $deleted = $this->authService->deleteAccount($user);

        if (!$deleted) {
            return ResponseHelper::error('Account deletion failed');
        }

        return ResponseHelper::successMessage('Account deleted successfully');
    }
}