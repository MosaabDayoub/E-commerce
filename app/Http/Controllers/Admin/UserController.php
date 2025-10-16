<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Requests\Admin\UserRequest;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;


class UserController extends Controller
{
    // get users.
    public function index()
    {
        $users = User::withCount('orders')->paginate(10);
        return ResponseHelper::success(UserResource::collection($users)); 
    }

    /**
     * create user.
     */
    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);
        
        return ResponseHelper::success(new UserResource($user),'User created successfully');
    }

    // get the specified user.
    public function show(User $user)
    {
        $user->loadCount('orders');
        
        return ResponseHelper::success(new UserResource($user));  
    }

    // Update the specified user.
    public function update(UserRequest $request, User $user)
    {
        $validated = $request->validated();

        $updateData = [
            'name' => $validated['name'] ?? $user->name,
            'email' => $validated['email'] ?? $user->email,
        ];

        if (isset($validated['password'])) {
            $updateData['password'] = $validated['password'];
        }

        $user->update($updateData);

        $user->loadCount('orders');

        return ResponseHelper::success(new UserResource($user),'User updated successfully');
    }
    // Remove user.
    public function destroy(User $user)
    {
        $user->delete();
        return ResponseHelper::successMessage('user deleted successfully'); 
    }

    // search about specified resource
    public function search(UserRequest $request){
            
        $users = User::where('name','like',$request->search . '%')
        ->limit(50)
        ->paginate(10);
        return ResponseHelper::success(UserResource::collection($users)); 
    }
}