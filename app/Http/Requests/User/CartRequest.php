<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ResponseHelper;

class CartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $methodName = $this->route()->getActionMethod();
    
        return match($methodName) {
            'store' => $this->getStoreRules(),
            'updateItem' => $this->getUpdateRules(),
            'getCartCost' => $this->getCartCostRules()
        };
    }

    // Rules for adding item to cart
    private function getStoreRules(): array
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'color_id' => 'nullable|integer|exists:colors,id',
            'size_id' => 'nullable|integer|exists:sizes,id',
            'quantity' => 'required|integer|min:1|max:100',
            'user_id' => 'sometimes|integer|exists:users,id'
        ];
    }

    // Rules for updating cart item
    private function getUpdateRules(): array
    {
        return [
            'product_id' => 'sometimes|integer|exists:products,id',
            'color_id' => 'nullable|integer|exists:colors,id',
            'size_id' => 'nullable|integer|exists:sizes,id',
            'quantity' => 'sometimes|integer|min:1|max:100'
        ];
    }

    // Rules for getting cart cost
    private function getCartCostRules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id'
        ];
    }

    // Custom error messages
    public function messages(): array
    {
        return [
            // Product messages
            'product_id.required' => 'Product ID is required',
            'product_id.integer' => 'Product ID must be an integer',
            'product_id.exists' => 'The selected product does not exist',

            // Color messages
            'color_id.integer' => 'Color ID must be an integer',
            'color_id.exists' => 'The selected color does not exist',

            // Size messages
            'size_id.integer' => 'Size ID must be an integer',
            'size_id.exists' => 'The selected size does not exist',

            // Quantity messages
            'quantity.required' => 'Quantity is required',
            'quantity.integer' => 'Quantity must be an integer',
            'quantity.min' => 'Quantity must be at least 1',
            'quantity.max' => 'Quantity cannot exceed 100',

            // User messages
            'user_id.required' => 'User ID is required',
            'user_id.integer' => 'User ID must be an integer',
            'user_id.exists' => 'The selected user does not exist',
        ];
    }

    // Custom attribute names
    public function attributes(): array
    {
        return [
            'product_id' => 'product',
            'color_id' => 'color',
            'size_id' => 'size',
            'user_id' => 'user',
        ];
    }
}