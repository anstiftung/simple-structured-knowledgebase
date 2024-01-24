<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'add articles']);
        Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => 'delete articles']);
        Permission::create(['name' => 'publish articles']);
        Permission::create(['name' => 'unpublish articles']);
        Permission::create(['name' => 'add collections']);
        Permission::create(['name' => 'edit collections']);
        Permission::create(['name' => 'delete collections']);
        Permission::create(['name' => 'feature collections']);
        Permission::create(['name' => 'create attached files']);
        Permission::create(['name' => 'update attached files']);
        Permission::create(['name' => 'delete attached files']);
        Permission::create(['name' => 'create attached urls']);
        Permission::create(['name' => 'update attached urls']);
        Permission::create(['name' => 'delete attached urls']);
        Permission::create(['name' => 'update others attached files']);
        Permission::create(['name' => 'update others attached urls']);
        Permission::create(['name' => 'create others attached files']);
        Permission::create(['name' => 'create others attached urls']);
        Permission::create(['name' => 'delete others attached files']);
        Permission::create(['name' => 'delete others attached urls']);

        // create roles and assign created permissions

        // this can be done as separate statements
        $role = Role::create(['name' => 'writer']);
        $role->givePermissionTo(['edit articles', 'publish articles', 'add articles']);
        $role->givePermissionTo(['create attached files','update attached files']);
        $role->givePermissionTo(['create attached urls','update attached urls']);

        // or may be done by chaining
        $role = Role::create(['name' => 'editor']);
        $role->givePermissionTo(['publish articles', 'unpublish articles']);
        $role->givePermissionTo(['edit articles', 'publish articles', 'add articles']);
        $role->givePermissionTo(['add collections','edit collections', 'delete collections','feature collections']);
        $role->givePermissionTo(['create attached files','update attached files', 'delete attached files']);
        $role->givePermissionTo(['create attached urls','update attached urls', 'delete attached urls']);
        $role->givePermissionTo(['create others attached files','update others attached files', 'delete others attached files']);
        $role->givePermissionTo(['create others attached urls','update others attached urls', 'delete others attached urls']);

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
    }
}
