<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create permissions
        Permission::create(['name' => 'view_permissions']);
        Permission::create(['name' => 'view_roles']);
        Permission::create(['name' => 'edit_roles']);
        Permission::create(['name' => 'view_users']);
        Permission::create(['name' => 'add_users']);
        Permission::create(['name' => 'edit_users']);
        Permission::create(['name' => 'delete_users']);

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'SUPERADMIN']);

        $role2 = Role::create(['name' => 'ADMIN']);
        $role2->givePermissionTo('view_permissions');
        $role2->givePermissionTo('view_roles');
        $role2->givePermissionTo('edit_roles');

        $user = \App\User::find(1);
        $user->assignRole($role1);
    }
}
