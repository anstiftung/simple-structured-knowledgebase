<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // articles
        Permission::create(['name' => 'add articles']);
        Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => 'delete articles']);
        Permission::create(['name' => 'publish articles']);
        Permission::create(['name' => 'edit article creator']);
        Permission::create(['name' => 'update others articles']);
        Permission::create(['name' => 'clap own articles']);
        // collections
        Permission::create(['name' => 'add collections']);
        Permission::create(['name' => 'edit collections']);
        Permission::create(['name' => 'delete collections']);
        Permission::create(['name' => 'feature collections']);
        // attachments
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
        // comments
        Permission::create(['name' => 'create comments']);
        Permission::create(['name' => 'delete comments']);
        // users
        Permission::create(['name' => 'list users']);
        // general
        Permission::create(['name' => 'approve content']);

        // create roles and assign created permissions

        // this can be done as separate statements
        $role = Role::create(['name' => 'writer']);
        $role->givePermissionTo(['edit articles', 'add articles']);
        $role->givePermissionTo(['create attached files','update attached files']);
        $role->givePermissionTo(['create attached urls','update attached urls']);
        $role->givePermissionTo(['create comments']);

        // or may be done by chaining
        $role = Role::create(['name' => 'editor']);
        $role->givePermissionTo(['edit articles', 'edit article creator', 'publish articles', 'add articles', 'update others articles', 'clap own articles']);
        $role->givePermissionTo(['add collections','edit collections', 'delete collections','feature collections']);
        $role->givePermissionTo(['create attached files','update attached files', 'delete attached files']);
        $role->givePermissionTo(['create attached urls','update attached urls', 'delete attached urls']);
        $role->givePermissionTo(['create others attached files','update others attached files', 'delete others attached files']);
        $role->givePermissionTo(['create others attached urls','update others attached urls', 'delete others attached urls']);
        $role->givePermissionTo(['create comments', 'delete comments']);
        $role->givePermissionTo(['list users']);
        $role->givePermissionTo(['approve content']);

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
    }
}
