<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Requests\Admin\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::get();

        return response()->json($users, 200);
    }

  
    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        User::create([

            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        return response()->json(['message' => 'user created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        
        return response()->json($user, 200); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $validated = $request->validated();

        $updateData = [
            'name' => $validated['name'] ?? $user->name,
            'email' => $validated['email'] ?? $user->email,
        ];

        if (isset($data['password'])) {
        $data['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'updated_at' => $user->updated_at
            ]
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'user deleted successfully'], 200);
    }

    // search about specified resource
   public function search(UserRequest $request)
{
    $validated = $request->validated();
    
    $user = User::where('name', 'like' , $validated['search'] . '%')->get();
    return response()->json($user, 200);
}
}
