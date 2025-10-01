<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $methodName = $this->route()->getActionMethod();
        $categoryId = $this->getCategoryId();

        return match($methodName) {
            'store' => $this->getStoreRules(),
            'update' => $this->getUpdateRules($categoryId),
            'search' => $this->getSearchRules(),
        };
    }

    // Rules for creating a new category
    private function getStoreRules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
        ];
    }

    // Rules for updating category
    private function getUpdateRules($categoryId): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($categoryId)
            ],
            'description' => 'nullable|string|max:1000',
        ];
    }

    // Rules for searching categories
    private function getSearchRules(): array
    {
        return [
            'search' => 'required|string|min:1|max:255'
        ];
    }

    // Get categoryId from route
    private function getCategoryId()
    {
        return $this->route('category')?->id;
    }

    // Custom error messages
    public function messages(): array
    {
        return [
            'name.required' => 'Category name is required',
            'name.string' => 'Category name must be a string',
            'name.max' => 'Category name must not exceed 255 characters',
            'name.unique' => 'Category name already exists',
            
            'description.string' => 'Category description must be a string',
            'description.max' => 'Category description must not exceed 1000 characters',
            
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
            'name' => 'category name',
            'description' => 'category description',
            'search' => 'search keyword',
        ];
    }
}