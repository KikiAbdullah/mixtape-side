<?php

use App\Models\User;
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
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            // users
            'users_view', 'users_add', 'users_edit', 'users_delete',
            // roles
            'roles_view', 'roles_add', 'roles_edit', 'roles_delete',
            // permissions
            'permissions_view',
            // debug
            'debug_view'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // create roles and assign permissions
        
        // 1. SUPERADMIN
        $roleSuper = Role::firstOrCreate(['name' => 'SUPERADMIN']);
        $roleSuper->syncPermissions(Permission::all());

        // 2. ADMIN
        $roleAdmin = Role::firstOrCreate(['name' => 'ADMIN']);
        $roleAdmin->syncPermissions([
            'users_view', 'users_add', 'users_edit',
            'roles_view',
        ]);

        // 3. STAFF
        $roleStaff = Role::firstOrCreate(['name' => 'STAFF']);
        $roleStaff->syncPermissions([
            'users_view'
        ]);

        // Assign roles to users
        $userSuper = User::where('username', 'superadmin')->first();
        if ($userSuper) $userSuper->assignRole($roleSuper);

        $userAdmin = User::where('username', 'admin')->first();
        if ($userAdmin) $userAdmin->assignRole($roleAdmin);

        $userStaff = User::where('username', 'staff')->first();
        if ($userStaff) $userStaff->assignRole($roleStaff);
    }
}
        }

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'SUPERADMIN']);

        $role2 = Role::create(['name' => 'ADMIN']);

        $user = User::find(1);
        $user->assignRole($role1);
    }
}
