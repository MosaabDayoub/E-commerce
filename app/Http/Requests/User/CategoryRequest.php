<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ResponseHelper;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => 'required|string|min:1|max:255'
        ];
    }

    // Custom error messages
    public function messages(): array
    {
        return [  
            'search.required' => 'Search keyword is required',
            'search.string' => 'Search keyword must be a string',
            'search.min' => 'Search keyword must be at least 1 character',
            'search.max' => 'Search keyword must not exceed 255 characters',
        ];
    }

    // Custom attribute names
    public function attributes(): array
    {
        return [
            'search' => 'search keyword',
        ];
    }
}