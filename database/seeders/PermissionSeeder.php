<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Admin;
use App\Enums\UserRole;
use App\Enums\PermissionType;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // admin permissions
        $adminPermissions = [
             // admins manag
             PermissionType::VIEW_ADMINS->value,
             PermissionType::CREATE_ADMINS->value,
             PermissionType::EDIT_ADMINS->value,
             PermissionType::DELETE_ADMINS->value,

            // users manag
            PermissionType::VIEW_USERS->value,
            PermissionType::CREATE_USERS->value,
            PermissionType::EDIT_USERS->value,
            PermissionType::DELETE_USERS->value,

            // product manag
            PermissionType::VIEW_PRODUCTS->value,
            PermissionType::CREATE_PRODUCTS->value,
            PermissionType::EDIT_PRODUCTS->value,
            PermissionType::DELETE_PRODUCTS->value,

            // categories manag
            PermissionType::VIEW_CATEGORIES->value,
            PermissionType::CREATE_CATEGORIES->value,
            PermissionType::EDIT_CATEGORIES->value,
            PermissionType::DELETE_CATEGORIES->value,

            // colors manag
            PermissionType::VIEW_COLORS->value,
            PermissionType::CREATE_COLORS->value,
            PermissionType::EDIT_COLORS->value,
            PermissionType::DELETE_COLORS->value,

            // sizes manag
            PermissionType::VIEW_SIZES->value,
            PermissionType::CREATE_SIZES->value,
            PermissionType::EDIT_SIZES->value,
            PermissionType::DELETE_SIZES->value,

            // carts view
            PermissionType::VIEW_CARTS->value,

            // orders view
            PermissionType::VIEW_ORDERS->value,
        ];

        foreach ($adminPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'api_admin'
            ]);
        }

        // user permissions
        $userPermissions = [

            // view permissions
            PermissionType::VIEW_PRODUCTS->value,
            PermissionType::VIEW_CATEGORIES->value,

            // cart permissions
            PermissionType::VIEW_CART->value,
            PermissionType::CREATE_CARTS->value,
            PermissionType::EDIT_CARTS->value,
            PermissionType::DELETE_CARTS->value,

            // order permissions
            PermissionType::VIEW_ORDER->value,
            PermissionType::CREATE_ORDERS->value,
            PermissionType::EDIT_ORDERS->value,
        ];

        foreach ($userPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'api_user'
            ]);
        }

        $superAdminRole = Role::firstOrCreate([
            'name' => UserRole::SUPER_ADMIN->value,
            'guard_name' => 'api_admin'
        ]);

        $adminRole = Role::firstOrCreate([
            'name' => UserRole::ADMIN->value,
            'guard_name' => 'api_admin'
        ]);

        $userRole = Role::firstOrCreate([
            'name' => UserRole::USER->value,
            'guard_name' => 'api_user'
        ]);

        $allAdminPermissions = Permission::where('guard_name', 'api_admin')->get();
        $superAdminRole->syncPermissions($allAdminPermissions);

        $adminRole->syncPermissions(
            Permission::whereIn('name', $adminPermissions)->where('guard_name', 'api_admin')->get()
        );

        $userRole->syncPermissions(
            Permission::whereIn('name', $userPermissions)->where('guard_name', 'api_user')->get()
        );

        $superAdmin = Admin::firstOrCreate([
            'email' => 'superadmin@store.com'
        ], [
            'name' => 'ahmad ali',
            'password' => Hash::make('Password123'),
        ]);
        $superAdmin->assignRole($superAdminRole);

        $admin = Admin::firstOrCreate([
            'email' => 'admin@store.com'
        ], [
            'name' => 'mohamad ali', 
            'password' => Hash::make('Password123'),
        ]);
        $admin->assignRole($adminRole);

        $user = User::firstOrCreate([
            'email' => 'customer@store.com'
        ], [
            'name' => 'yusef ali',
            'password' => Hash::make('Password123'),
        ]);
        $user->assignRole($userRole);
    }
}