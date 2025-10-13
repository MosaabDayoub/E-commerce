<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Helpers\ResponseHelper;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $methodName = $this->route()->getActionMethod();
        $userId = $this->getUserId();

        return match($methodName) {
            'store' => $this->getStoreRules(),
            'update' => $this->getUpdateRules($userId),
            'search' => $this->getSearchRules()
        };
    }

    /**
     * Rules for creating a new user
     */
    private function getStoreRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ];
    }

    /**
     * Rules for updating user
     */
    private function getUpdateRules($userId): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'password' => [
                'sometimes',
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'password_confirmation' => 'required_with:password|string|same:password',
        ];
    }

    /**
     * Rules for searching users
     */
    private function getSearchRules(): array
    {
        return [
            'search' => 'required|string|min:1|max:255'
        ];
    }

    /**
     * Get userId from route
     */
    private function getUserId()
    {
        return $this->route('user')?->id;
    }

    /**
     * Custom error messages
     */
    public function messages(): array
    {
        return [
            // Name messages
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'name.max' => 'Name must not exceed 255 characters',
            
            // Email messages
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'email.max' => 'Email must not exceed 255 characters',
            'email.unique' => 'Email already exists',
            
            // Password messages
            'password.required' => 'Password is required',
            'password.string' => 'Password must be a string',
            'password.confirmed' => 'Password confirmation does not match',
            
            // Password confirmation messages
            'password_confirmation.required' => 'Password confirmation is required',
            'password_confirmation.required_with' => 'Password confirmation is required when changing password',
            'password_confirmation.same' => 'Password confirmation must match the password',
            
            // Search messages
            'search.required' => 'Search keyword is required',
            'search.string' => 'Search keyword must be a string',
            'search.min' => 'Search keyword must be at least 1 character',
            'search.max' => 'Search keyword must not exceed 255 characters',
        ];
    }

    /**
     * Custom attribute names
     */
    public function attributes(): array
    {
        return [
            'name' => 'name',
            'email' => 'email address',
            'password' => 'password',
            'password_confirmation' => 'password confirmation',
            'search' => 'search keyword',
        ];
    }
}