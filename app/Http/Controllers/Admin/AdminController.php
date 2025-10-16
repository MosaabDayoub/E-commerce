<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Http\Requests\Admin\AdminRequest;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionManagementRequest;
use App\Http\Requests\Admin\RoleManagementRequest;
use App\Http\Requests\Admin\RolePermissionRequest;
use App\Http\Resources\AdminResource;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    // get admins.
    public function index()
    {
        $admins = Admin::with('roles.permissions')->get();
        return ResponseHelper::success(AdminResource::collection($admins)); 
    }

    /**
     * create admin.
     */
    public function store(AdminRequest $request)
    {
        $validated = $request->validated();

        $admin = Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);
        
        return ResponseHelper::success(new AdminResource($admin),'Admin created successfully');
    }

    // get the specified admin.
    public function show(Admin $admin)
    {
        $admin->load('roles.permissions');
        return ResponseHelper::success(new AdminResource($admin));  
    }

    // Update the specified admin.
    public function update(AdminRequest $request, Admin $admin)
    {
        $validated = $request->validated();

        $updateData = [
            'name' => $validated['name'] ?? $admin->name,
            'email' => $validated['email'] ?? $admin->email,
        ];

        if (isset($validated['password'])) {
            $updateData['password'] = $validated['password'];
        }

        $admin->update($updateData);
        $admin->load('roles.permissions');

        return ResponseHelper::success(new AdminResource($admin),'admin updated successfully');
    }

    // Remove admin.
    public function destroy(Admin $admin)
    {
        $admin->delete();
        return ResponseHelper::successMessage('admin deleted successfully'); 
    }

    // search about specified resource
    public function search(AdminRequest $request)
    {
        $admins = Admin::where('name','like',$request->search . '%')
            ->limit(50)
            ->paginate(10);
        return ResponseHelper::success(AdminResource::collection($admins)); 
    }

    // ============ ROLES MANAGEMENT ============

    /**
     * assign role
     */
    public function assignRole(RoleManagementRequest $request, Admin $admin): JsonResponse
    {
        // check if admin edit his roles
        if ($request->user('api_admin')->id === $admin->id) {
            return ResponseHelper::error('you cant edit your role');
        }

        $admin->assignRole($request->role);
        $admin->load('roles.permissions');

        return ResponseHelper::success(new AdminResource($admin),'role added to admin');
    }

    /**
     * remove role
     */
    public function removeRole(RoleManagementRequest $request, Admin $admin): JsonResponse
    {
        // check if admin try to edit his roles
        if ($request->user('api_admin')->id === $admin->id) {
            return ResponseHelper::error('you cant edit your role');
        }

        // deny remove last role to admin 
        if ($admin->roles->count() <= 1) {
            return ResponseHelper::error('you cant remove last role to admin');
        }

        $admin->removeRole($request->role);
        $admin->load('roles.permissions');

        return ResponseHelper::success(new AdminResource($admin),'role removed successfuly');
    }

    /**
     * sync roles
     */
    public function syncRoles(RoleManagementRequest $request, Admin $admin): JsonResponse
    {
        // check if admin try to edit his roles
        if ($request->user('api_admin')->id === $admin->id) {
            return ResponseHelper::error('you cant edit your role');
        }

        $admin->syncRoles($request->roles);
        $admin->load('roles.permissions');

        return ResponseHelper::success(new AdminResource($admin),'roles synced successfuly'); 
    }

    // ============ PERMISSIONS MANAGEMENT ============

    /**
     * Give permission to admin
     */
    public function givePermissionTo(PermissionManagementRequest $request, Admin $admin): JsonResponse
    {
        // check if admin try to edit his permissions
        if ($request->user('api_admin')->id === $admin->id) {
            return ResponseHelper::error('you cant edit your permissions');
        }

        $admin->givePermissionTo($request->permission);
        $admin->load('roles.permissions');

        return ResponseHelper::success(new AdminResource($admin),'permission given to admin');
    }

    /**
     * Revoke permission from admin
     */
    public function revokePermissionFrom(PermissionManagementRequest $request, Admin $admin): JsonResponse
    {
        // check if admin try to edit his permissions
        if ($request->user('api_admin')->id === $admin->id) {
            return ResponseHelper::error('you cant edit your permissions');
        }

        $admin->revokePermissionTo($request->permission);
        $admin->load('roles.permissions');

        return ResponseHelper::success(new AdminResource($admin),'permission revoked from admin');
    }

    /**
     * Sync permissions for admin
     */
    public function syncPermissions(PermissionManagementRequest $request, Admin $admin): JsonResponse
    {
        // check if admin try to edit his permissions
        if ($request->user('api_admin')->id === $admin->id) {
            return ResponseHelper::error('you cant edit your permissions');
        }

        $admin->syncPermissions($request->permissions);
        $admin->load('roles.permissions');

        return ResponseHelper::success(new AdminResource($admin),'permissions synced successfully');
    }

    /**
     * Get all permissions 
     */
    public function getAllPermissions(): JsonResponse
    {
        $permissions = Permission::all()->pluck('name');
        return ResponseHelper::success($permissions, 'All permissions retrieved');
    }

    /**
     * Get admin's direct permissions (excluding role permissions)
     */
    public function getDirectPermissions(PermissionManagementRequest $admin): JsonResponse
    {
        $directPermissions = $admin->getDirectPermissions()->pluck('name');
        return ResponseHelper::success($directPermissions, 'Direct permissions retrieved');
    }

    // ============ ROLE-PERMISSION MANAGEMENT ============

    /**
     * Give permission to role
     */
    public function givePermissionToRole(RolePermissionRequest $request): JsonResponse
    {
        $role = Role::findByName($request->role);
        $role->givePermissionTo($request->permission);

        return ResponseHelper::success([
            'role' => $role->name,
            'permissions' => $role->permissions->pluck('name')
        ], 'permission given to role');
    }

    /**
     * Revoke permission from role
     */
    public function revokePermissionFromRole(RolePermissionRequest $request): JsonResponse
    {
        $role = Role::findByName($request->role);
        $role->revokePermissionTo($request->permission);

        return ResponseHelper::success([
            'role' => $role->name,
            'permissions' => $role->permissions->pluck('name')
        ], 'permission removed from role');
    }

    /**
     * Sync permissions for role
     */
    public function syncRolePermissions(RolePermissionRequest $request): JsonResponse
    {
        $role = Role::findByName($request->role);
        $role->syncPermissions($request->permissions);

        return ResponseHelper::success([
            'role' => $role->name,
            'permissions' => $role->permissions->pluck('name')
        ], 'permissions synced for role');
    }

    /**
     * Get role permissions
     */
    public function getRolePermissions(RolePermissionRequest $request): JsonResponse
    {
        $role = Role::findByName($request->role);
        $permissions = $role->permissions->pluck('name');

        return ResponseHelper::success($permissions, 'Role permissions retrieved');
    }
}