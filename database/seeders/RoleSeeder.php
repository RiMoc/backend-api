<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $nullPermission = [];
        $nullName = [];

        $rolePermissions = [
            'role-view','role-create', 'role-edit', 'role-delete'
        ];
        $roleName = [
            'แสดงข้อมูลระดับการเข้าถึง','สร้างข้อมูลระดับการเข้าถึง','แก้ไขข้อมูลระดับการเข้าถึง','ลบข้อมูลระดับการเข้าถึง'
        ];

        $permissionPermissions = [
            'permission-view','permission-create',  'permission-edit', 'permission-delete'
        ];
        $permissionName = [
            'แสดงข้อมูลสิทธิ์','สร้างข้อมูลสิทธิ์','แก้ไขข้อมูลสิทธิ์','ลบข้อมูลสิทธิ์'
        ];

        $userPermissions = [
            'users-view','users-create',  'users-edit', 'users-delete'
        ];
        $userName = [
            'แสดงข้อมูลผู้ใช้งาน','สร้างข้อมูลผู้ใช้งาน','แก้ไขข้อมูลผู้ใช้งาน','ลบข้อมูลผู้ใช้งาน'
        ];

        $permissions = array_merge($nullPermission, $rolePermissions, $permissionPermissions, $userPermissions);
        $permissionsName = array_merge($nullName, $roleName, $permissionName, $userName);

        $perrmission_array = array_map(function ($perm, $names){ return ['name' => $perm, 'title' => $names, 'type' => 'settings']; }, $permissions,$permissionsName);

        foreach ($perrmission_array as $permission) {
            Permission::create($permission);
        }

        $role = Role::create(['name' => 'user', 'title' => 'สมาชิก']);
        $role->givePermissionTo(array_merge($nullPermission, ['role-view', 'permission-view', 'users-view']));

        $role = Role::create(['name'=> 'admin', 'title' => 'ผู้ดูแลระบบ']);
        $role->givePermissionTo(Permission::all());

    }
}
