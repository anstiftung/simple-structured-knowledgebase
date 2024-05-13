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
        Permission::create(['name' => 'delete others articles']);
        Permission::create(['name' => 'delete own articles']);
        Permission::create(['name' => 'update own articles']);
        Permission::create(['name' => 'update others articles']);

        Permission::create(['name' => 'publish articles']);
        Permission::create(['name' => 'edit article creator']);
        Permission::create(['name' => 'clap own articles']);

        // collections
        Permission::create(['name' => 'add collections']);
        Permission::create(['name' => 'update collections']);
        Permission::create(['name' => 'feature collections']);
        Permission::create(['name' => 'edit collection creator']);

        //attachments
        Permission::create(['name' => 'add attachments']);
        Permission::create(['name' => 'update own attachments']);
        Permission::create(['name' => 'update others attachments']);
        Permission::create(['name' => 'delete own attachments']);
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
        $role->givePermissionTo('add articles');
        $role->givePermissionTo('update own articles');
        $role->givePermissionTo('delete own articles');

        $role->givePermissionTo('add attachments');
        $role->givePermissionTo('update own attachments');
        $role->givePermissionTo('delete own attachments');

        $role->givePermissionTo('create comments');

        $role = Role::create(['name' => 'editor']);
        $role->givePermissionTo('update own articles');
        $role->givePermissionTo('update others articles');
        $role->givePermissionTo('edit article creator');
        $role->givePermissionTo('publish articles');
        $role->givePermissionTo('add articles');
        $role->givePermissionTo('clap own articles');
        $role->givePermissionTo('delete others articles');
        $role->givePermissionTo('delete own articles');

        $role->givePermissionTo('add collections');
        $role->givePermissionTo('update collections');
        $role->givePermissionTo('edit collection creator');
        $role->givePermissionTo('feature collections');

        $role->givePermissionTo('add attachments');

        $role->givePermissionTo('delete others attachments');
        $role->givePermissionTo('delete own attachments');
        $role->givePermissionTo('force delete attachments');

        $role->givePermissionTo('list trashed attachments');

        $role->givePermissionTo('update own attachments');
        $role->givePermissionTo('update others attachments');

        $role->givePermissionTo('create comments');
        $role->givePermissionTo('delete comments');
        $role->givePermissionTo('list users');
        $role->givePermissionTo('approve content');

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
    }
}
