<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return ResponseHelper::success([
            'user' => new UserResource($user),
            'token' => $token,
        ], 'Registration successful');
    }
}