<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = auth()->id();
        $routeName = $this->route()->getName();

        $rules = [
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId)
            ],
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ];

        // change password rules
        if ($routeName === 'profile.change-password') {
            return [
                'current_password' => 'required|string',
                'new_password' => [
                    'required',
                    'confirmed',
                    'different:current_password',
                    Password::min(8)->letters()->numbers()
                ]
            ];
        }

        // reset password rules
        if ($routeName === 'password.reset') {
            return [
                'code' => 'required|string|size:6',
                'password' => [
                    'required',
                    'confirmed',
                    Password::min(8)->letters()->numbers()
                ]
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            // email
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already taken.',
            
            // image
            'avatar.image' => 'The file must be an image.',
            'avatar.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, webp.',
            'avatar.max' => 'The image may not be greater than 5MB.',
            
            // current password
            'current_password.required' => 'The current password is required.',
            'current_password.string' => 'The current password must be a string.',
            
            // new password
            'new_password.required' => 'The new password is required.',
            'new_password.confirmed' => 'The password confirmation does not match.',
            'new_password.different' => 'The new password must be different from the current password.',
            
            // reset password
            'code.required' => 'The verification code is required.',
            'code.size' => 'The verification code must be 6 characters.',
            'password.required' => 'The password field is required.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}