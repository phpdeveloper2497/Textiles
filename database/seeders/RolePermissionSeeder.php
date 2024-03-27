<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            Permission::create(['name' => 'permission:viewAny']),
            Permission::create(['name' => 'permission:view']),
            Permission::create(['name' => 'permission:create']),
            Permission::create(['name' => 'permission:update',]),
            Permission::create(['name' => 'permission:delete']),
            Permission::create(['name' => 'permission:restore']),
        ];

        $userPermissions =[
            Permission::create(['name' => 'user:viewAny']),
            Permission::create(['name' => 'user:view']),
            Permission::create(['name' => 'user:create']),
            Permission::create(['name' => 'user:update',]),
            Permission::create(['name' => 'user:delete']),
            Permission::create(['name' => 'user:restore']),
        ];


        $admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions($userPermissions,$permissions);

        $warehouse_manager = Role::create(['name' => 'warehouse_manager', 'guard_name' => 'web']);

        $case_manager = Role::create(['name' => 'case_manager', 'guard_name' => 'web']);

        $worker = Role::create(['name' => 'worker', 'guard_name' => 'web']);
    }
}
