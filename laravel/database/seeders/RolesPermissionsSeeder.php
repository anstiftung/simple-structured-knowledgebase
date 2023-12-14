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

        // create roles and assign created permissions

        // this can be done as separate statements
        $role = Role::create(['name' => 'writer']);
        $role->givePermissionTo(['edit articles', 'publish articles', 'add articles']);

        // or may be done by chaining
        $role = Role::create(['name' => 'editor']);
        $role->givePermissionTo(['publish articles', 'unpublish articles']);
        $role->givePermissionTo(['edit articles', 'publish articles', 'add articles']);
        $role->givePermissionTo(['add collections','edit collections', 'delete collections']);

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
    }
}
