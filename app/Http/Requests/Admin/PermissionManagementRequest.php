<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PermissionManagementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $methodName = $this->route()->getActionMethod();

        return match($methodName) {
            'givePermissionTo', 'revokePermissionFrom' => $this->getSinglePermissionRules(),
            'syncPermissions' => $this->getMultiplePermissionsRules(),
            'getDirectPermissions' => [],
            default => []
        };
    }

    private function getSinglePermissionRules(): array
    {
        return [
            'permission' => 'required|string|exists:permissions,name'
        ];
    }

    private function getMultiplePermissionsRules(): array
    {
        return [
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'string|exists:permissions,name'
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('permissions') && is_string($this->permissions)) {
            $this->merge([
                'permissions' => explode(',', $this->permissions)
            ]);
        }
    }

    public function messages(): array
    {
        return [
            'permission.required' => 'Permission is required',
            'permission.string' => 'Permission must be a string',
            'permission.exists' => 'The selected permission does not exist',
            'permissions.required' => 'Permissions are required',
            'permissions.array' => 'Permissions must be an array',
            'permissions.min' => 'At least one permission must be selected',
            'permissions.*.string' => 'Each permission must be a string',
            'permissions.*.exists' => 'One or more selected permissions do not exist'
        ];
    }
}