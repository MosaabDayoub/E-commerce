<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RoleManagementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $methodName = $this->route()->getActionMethod();

        return match($methodName) {
            'assignRole', 'removeRole' => $this->getSingleRoleRules(),
            'syncRoles' => $this->getMultipleRolesRules(),
            default => []
        };
    }

    private function getSingleRoleRules(): array
    {
        return [
            'role' => 'required|string|exists:roles,name'
        ];
    }

    private function getMultipleRolesRules(): array
    {
        return [
            'roles' => 'required|array|min:1',
            'roles.*' => 'string|exists:roles,name'
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('roles') && is_string($this->roles)) {
            $this->merge([
                'roles' => explode(',', $this->roles)
            ]);
        }
    }

    public function messages(): array
    {
        return [
            'role.required' => 'Role is required',
            'role.string' => 'Role must be a string',
            'role.exists' => 'The selected role does not exist',
            'roles.required' => 'Roles are required',
            'roles.array' => 'Roles must be an array',
            'roles.min' => 'At least one role must be selected',
            'roles.*.string' => 'Each role must be a string',
            'roles.*.exists' => 'One or more selected roles do not exist'
        ];
    }
}