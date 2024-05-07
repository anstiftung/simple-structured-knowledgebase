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
        Permission::create(['name' => 'edit collection creator']);

        //attachments
        Permission::create(['name' => 'create attachments']);
        Permission::create(['name' => 'update attachments']);
        Permission::create(['name' => 'delete others attachments']);
        Permission::create(['name' => 'force delete attachments']);
        Permission::create(['name' => 'list trashed attachments']);

        // comments
        Permission::create(['name' => 'create comments']);
        Permission::create(['name' => 'delete comments']);

        // users
        Permission::create(['name' => 'list users']);

        // general
        Permission::create(['name' => 'approve content']);

        $role = Role::create(['name' => 'writer']);
        $role->givePermissionTo('edit articles');
        $role->givePermissionTo('add articles');

        $role->givePermissionTo('create attachments');

        $role->givePermissionTo('create comments');

        $role = Role::create(['name' => 'editor']);
        $role->givePermissionTo('edit articles');
        $role->givePermissionTo('edit article creator');
        $role->givePermissionTo('publish articles');
        $role->givePermissionTo('add articles');
        $role->givePermissionTo('update others articles');
        $role->givePermissionTo('clap own articles');
        $role->givePermissionTo('delete articles');

        $role->givePermissionTo('add collections');
        $role->givePermissionTo('edit collections');
        $role->givePermissionTo('edit collection creator');
        $role->givePermissionTo('delete collections');
        $role->givePermissionTo('feature collections');

        $role->givePermissionTo('create attachments');

        $role->givePermissionTo('delete others attachments');
        $role->givePermissionTo('force delete attachments');
        $role->givePermissionTo('list trashed attachments');
        $role->givePermissionTo('update attachments');

        $role->givePermissionTo('create comments');
        $role->givePermissionTo('delete comments');
        $role->givePermissionTo('list users');
        $role->givePermissionTo('approve content');

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
    }
}
