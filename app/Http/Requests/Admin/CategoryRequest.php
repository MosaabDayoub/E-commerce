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
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_ar' => 'nullable|string|max:1000',
            'description_en' => 'nullable|string|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048', 
        ];
    }

    // Rules for updating category
    private function getUpdateRules($categoryId): array
    {
        return [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_ar' => 'nullable|string|max:1000',
            'description_en' => 'nullable|string|max:1000',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:2048', 
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
            // Arabic name messages
            'name_ar.required' => 'Arabic category name is required',
            'name_ar.string' => 'Arabic category name must be a string',
            'name_ar.max' => 'Arabic category name must not exceed 255 characters',
            
            // English name messages
            'name_en.required' => 'English category name is required',
            'name_en.string' => 'English category name must be a string',
            'name_en.max' => 'English category name must not exceed 255 characters',
            
            // Arabic description messages
            'description_ar.string' => 'Arabic description must be a string',
            'description_ar.max' => 'Arabic description must not exceed 1000 characters',
            
            // English description messages
            'description_en.string' => 'English description must be a string',
            'description_en.max' => 'English description must not exceed 1000 characters',
            
            // Image messages
            'image.required' => 'Category image is required',
            'image.image' => 'The file must be an image',
            'image.mimes' => 'Image type not supported. Allowed: JPEG, PNG, JPG, GIF, WebP',
            'image.max' => 'Image size must not exceed 2MB',
            
            // Search messages
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
            'name_ar' => 'Arabic name',
            'name_en' => 'English name',
            'description_ar' => 'Arabic description',
            'description_en' => 'English description',
            'image' => 'category image',
            'search' => 'search keyword',
        ];
    }

}