<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Enums\UserRole;
use App\Enums\PermissionType;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PermissionType::all() as $permission) {
            Permission::firstOrCreate([
                'name' => $permission 
            ]);
        }

        $superAdminRole = Role::firstOrCreate([
            'name' => UserRole::SUPER_ADMIN->value
        ]);

        $adminRole = Role::firstOrCreate([
            'name' => UserRole::ADMIN->value 
        ]);

        $userRole = Role::firstOrCreate([
            'name' => UserRole::USER->value
        ]);

        $allPermissions = Permission::all();
        $superAdminRole->syncPermissions($allPermissions); 

        $adminPermissions = [
            PermissionType::VIEW_PRODUCTS->value,
            PermissionType::CREATE_PRODUCTS->value,
            PermissionType::EDIT_PRODUCTS->value,
            PermissionType::DELETE_PRODUCTS->value,

            PermissionType::VIEW_CATEGORIES->value,
            PermissionType::CREATE_CATEGORIES->value,
            PermissionType::EDIT_CATEGORIES->value,
            PermissionType::DELETE_CATEGORIES->value,

            PermissionType::VIEW_COLORS->value,
            PermissionType::CREATE_COLORS->value,
            PermissionType::EDIT_COLORS->value,
            PermissionType::DELETE_COLORS->value,

            PermissionType::VIEW_SIZES->value,
            PermissionType::CREATE_SIZES->value,
            PermissionType::EDIT_SIZES->value,
            PermissionType::DELETE_SIZES->value,

            PermissionType::VIEW_CARTS->value,
            PermissionType::VIEW_ORDERS->value,
        ];

        $adminRole->syncPermissions(
            Permission::whereIn('name', $adminPermissions)->get()
        );

        $userPermissions = [
            PermissionType::VIEW_PRODUCTS->value,
            PermissionType::VIEW_CATEGORIES->value,
            PermissionType::VIEW_COLORS->value,
            PermissionType::VIEW_SIZES->value,

            PermissionType::VIEW_CARTS->value,
            PermissionType::CREATE_CARTS->value,
            PermissionType::EDIT_CARTS->value,
            PermissionType::DELETE_CARTS->value,
            
            PermissionType::VIEW_ORDERS->value,
            PermissionType::CREATE_ORDERS->value,
        ];

        $userRole->syncPermissions(
            Permission::whereIn('name', $userPermissions)->get()
        );

        $superAdmin = User::firstOrCreate([
            'email' => 'superadmin@store.com'
        ], [
            'name' => 'ahmad ali',
            'password' => Hash::make('Password123'),
        ]);
        $superAdmin->assignRole($superAdminRole); 

        $admin = User::firstOrCreate([
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