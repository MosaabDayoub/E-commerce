<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $methodName = $this->route()->getActionMethod();
        $productId = $this->getProductId();

        return match($methodName) {
            'index' => $this->getFilterRules(),
            'store' => $this->getStoreRules(),
            'update' => $this->getUpdateRules($productId),
            'addColorsToProduct', 'removeColorsFromProduct' => $this->getColorRules(),
            'addSizesToProduct', 'removeSizesFromProduct' => $this->getSizeRules(),
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

    // Product creation rules
    private function getStoreRules(): array
    {
        return [
            'name' => 'required|unique:products,name|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'colors' => 'sometimes|array',
            'colors.*' => 'integer|exists:colors,id',
            'sizes' => 'sometimes|array',
            'sizes.*' => 'integer|exists:sizes,id',
        ];
    }

    // Product update rules
    private function getUpdateRules($productId): array
    {
        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('products','name')->ignore($productId)
            ],
            'description' => 'nullable|string|max:1000',
            'price' => 'sometimes|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'colors' => 'sometimes|array',
            'colors.*' => 'integer|exists:colors,id',
            'sizes' => 'sometimes|array',
            'sizes.*' => 'integer|exists:sizes,id',
        ];
    }

    // Color rules
    private function getColorRules(): array
    {
        return [
            'colors' => 'required|array|min:1',
            'colors.*' => 'integer|exists:colors,id'
        ];
    }

    // Size rules
    private function getSizeRules(): array
    {
        return [
            'sizes' => 'required|array|min:1',
            'sizes.*' => 'integer|exists:sizes,id'
        ];
    }

     // Search rules
    private function getSearchRules(): array
    {
        return [
            'search' => 'required|string|min:1|max:255'
        ];
    }

    //Get productId from route
    private function getProductId()
    {
        return $this->route('product')?->id ?? $this->route('productId');
    }

    // Custom error messages
    public function messages(): array
    {
        return [
            // General messages
            'name.required' => 'Product name is required',
            'name.unique' => 'Product name already exists',
            'name.max' => 'Product name must not exceed 255 characters',
            
            'description.required' => 'Product description is required',
            'description.max' => 'Product description must not exceed 1000 characters',
            
            'price.required' => 'Product price is required',
            'price.numeric' => 'Product price must be a number',
            'price.min' => 'Product price must be at least 0',
            
            'category_id.required' => 'Category is required',
            'category_id.exists' => 'The selected category does not exist',

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
        ];
    }

    // Custom attribute names
    public function attributes(): array
    {
        return [
            'name' => 'product name',
            'description' => 'product description',
            'price' => 'product price',
            'category_id' => 'category',
            'colors' => 'colors',
            'sizes' => 'sizes',
            'search' => 'search keyword',
            'min_price' => 'minimum price',
            'max_price' => 'maximum price',
        ];
    }
}