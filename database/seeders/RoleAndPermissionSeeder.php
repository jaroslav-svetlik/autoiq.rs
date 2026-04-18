<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (config('autoiq.permissions') as $permission => $label) {
            Permission::findOrCreate($permission, 'web');
        }

        $roleMatrix = [
            UserRole::Admin->value => array_keys(config('autoiq.permissions')),
            UserRole::Dealer->value => [
                'view market analytics',
                'create listings',
                'manage own listings',
                'manage dealer profile',
            ],
            UserRole::User->value => [
                'view market analytics',
                'create listings',
                'manage own listings',
                'manage favorites',
                'manage saved searches',
            ],
        ];

        foreach ($roleMatrix as $role => $permissions) {
            $roleModel = Role::findOrCreate($role, 'web');
            $roleModel->syncPermissions($permissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
