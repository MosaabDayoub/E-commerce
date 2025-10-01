<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'sometimes|string|in:pending,approved,completed,cancelled'
        ];
    }

    public function message(){
        return [
            // General messages
            'status.required' => 'Order status is required',
            'status.string' => 'Order status must be a string',
            'status.in' => 'Order status must be: pending, approved, completed, or cancelled',
        ];
    }

    public function attributes(): array
    {
        return [
            'status' => 'order status'
        ];
    }
}
