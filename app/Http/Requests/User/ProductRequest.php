<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ResponseHelper;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $methodName = $this->route()->getActionMethod();
    
        return match($methodName) {
            'index' => $this->getFilterRules(),
            'search' => $this->getSearchRules()
        };
    }

    // Filtering and search rules
    private function getFilterRules(): array
    {
        return [
            'colors' => 'sometimes|array',
            'colors.*' => 'integer|exists:colors,id',
            'sizes' => 'sometimes|array', 
            'sizes.*' => 'integer|exists:sizes,id',
            'min_price' => 'sometimes|numeric|min:0',
            'max_price' => 'sometimes|numeric|min:0',
            'category_id' => 'sometimes|integer|exists:categories,id',
        ];
    }

  
     // Search rules
    private function getSearchRules(): array
    {
        return [
            'search' => 'required|string|min:1|max:255'
        ];
    }

    // Custom error messages
    public function messages(): array
    {
        return [
            // Color messages
            'colors.required' => 'At least one color must be selected',
            'colors.array' => 'Colors must be an array',
            'colors.min' => 'At least one color must be selected',
            'colors.*.integer' => 'Color ID must be an integer',
            'colors.*.exists' => 'The selected color does not exist',

            // Size messages
            'sizes.required' => 'At least one size must be selected',
            'sizes.array' => 'Sizes must be an array',
            'sizes.min' => 'At least one size must be selected',
            'sizes.*.integer' => 'Size ID must be an integer',
            'sizes.*.exists' => 'The selected size does not exist',

            // Search messages
            'search.required' => 'Search keyword is required',
            'search.string' => 'Search keyword must be a string',
            'search.min' => 'Search keyword must be at least 1 character',
            'search.max' => 'Search keyword must not exceed 255 characters',

            // Filter messages
            'min_price.numeric' => 'Minimum price must be a number',
            'min_price.min' => 'Minimum price must be at least 0',
            'max_price.numeric' => 'Maximum price must be a number',
            'max_price.min' => 'Maximum price must be at least 0',
            'category_id.integer' => 'Category ID must be an integer',
            'category_id.exists' => 'The selected category does not exist',
        ];
    }

    // Custom attribute names
    public function attributes(): array
    {
        return [
            'category_id' => 'category',
            'colors' => 'colors',
            'sizes' => 'sizes',
            'search' => 'search keyword',
            'min_price' => 'minimum price',
            'max_price' => 'maximum price',
        ];
    }
}