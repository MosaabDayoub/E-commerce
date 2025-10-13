<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {

            return ResponseHelper::error('The provided credentials are incorrect.');
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return ResponseHelper::success([
            'user' =>new UserResource($user),
            'token' => $token,
        ], 'Login successfully');
    }
}
