<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RolePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $methodName = $this->route()->getActionMethod();

        return match($methodName) {
            'givePermissionToRole', 'revokePermissionFromRole' => $this->getSingleRolePermissionRules(),
            'syncRolePermissions' => $this->getMultipleRolePermissionsRules(),
            'getRolePermissions', 'getAllPermissions' => $this->getRolePermissionsRules(),
            default => []
        };
    }

    private function getSingleRolePermissionRules(): array
    {
        return [
            'role' => 'required|string|exists:roles,name',
            'permission' => 'required|string|exists:permissions,name'
        ];
    }

    private function getMultipleRolePermissionsRules(): array
    {
        return [
            'role' => 'required|string|exists:roles,name',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'string|exists:permissions,name'
        ];
    }

    private function getRolePermissionsRules(): array
    {
        return [
            'role' => 'required|string|exists:roles,name'
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
            'role.required' => 'Role is required',
            'role.string' => 'Role must be a string',
            'role.exists' => 'The selected role does not exist',
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