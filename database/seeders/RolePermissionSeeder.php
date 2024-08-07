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
        ];

        $userPermissions =[
            Permission::create(['name' => 'user:viewAny']),
            Permission::create(['name' => 'user:view']),
            Permission::create(['name' => 'user:create']),
            Permission::create(['name' => 'user:update']),
            Permission::create(['name' => 'user:delete']),
            Permission::create(['name' => 'user:restore']),
            Permission::create(['name' => 'user:ViewRoles']),
            Permission::create(['name' => 'user:assignRole']),
            Permission::create(['name' => 'user:removeRole']),
        ];

        $boxPermissions =[
            Permission::create(['name' => 'box:viewAny']),
            Permission::create(['name' => 'box:view']),
            Permission::create(['name' => 'box:create']),
            Permission::create(['name' => 'box:update']),
            Permission::create(['name' => 'box:delete']),
        ];
        $boxHistoryPermissions =[
            Permission::create(['name' => 'boxHistory:viewAny']),
            Permission::create(['name' => 'boxHistory:view']),
            Permission::create(['name' => 'boxHistory:create']),
            Permission::create(['name' => 'boxHistory:update']),
            Permission::create(['name' => 'boxHistory:delete']),
            Permission::create(['name' => 'boxHistory:workshop']),
        ];
        $handkerchiefPermissions =[
            Permission::create(['name' => 'handkerchief:viewAny']),
            Permission::create(['name' => 'handkerchief:view']),
            Permission::create(['name' => 'handkerchief:create']),
            Permission::create(['name' => 'handkerchief:update']),
            Permission::create(['name' => 'handkerchief:delete']),
        ];

        $handkerchiefHistoryPermissions =[
            Permission::create(['name' => 'handkerchiefHistory:viewAny']),
            Permission::create(['name' => 'handkerchiefHistory:view']),
            Permission::create(['name' => 'handkerchiefHistory:create']),
            Permission::create(['name' => 'handkerchiefHistory:update']),
            Permission::create(['name' => 'handkerchiefHistory:delete']),
            Permission::create(['name' => 'handkerchiefHistory:sold']),
        ];

        $SoldHankerchiefPermissions =[
            Permission::create(['name' => 'SoldHankerchief:viewAny']),
            Permission::create(['name' => 'SoldHankerchief:view']),
            Permission::create(['name' => 'SoldHankerchief:create']),
            Permission::create(['name' => 'SoldHankerchief:update']),
            Permission::create(['name' => 'SoldHankerchief:delete']),
        ];



        $admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions($permissions,$userPermissions,$boxPermissions,$boxHistoryPermissions,$handkerchiefPermissions,$handkerchiefHistoryPermissions,$SoldHankerchiefPermissions);

        $Ombor_mudiri = Role::create(['name' => 'Ombor mudiri', 'guard_name' => 'web']);
        $Ombor_mudiri->syncPermissions($boxPermissions,$boxHistoryPermissions);

        $Ish_boshqaruvchi = Role::create(['name' => 'Ish boshqaruvchi', 'guard_name' => 'web']);
        $Ish_boshqaruvchi->syncPermissions($handkerchiefPermissions,$handkerchiefHistoryPermissions);

        $worker = Role::create(['name' => 'worker', 'guard_name' => 'web']);
//        $worker->syncPermissions('user:view');
    }
}
