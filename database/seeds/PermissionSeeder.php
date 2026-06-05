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

        // 1. DEFINE ALL PERMISSIONS
        $permissions = [
            // User Management
            'users_view', 'users_create', 'users_edit', 'users_delete',
            'roles_view', 'roles_create', 'roles_edit', 'roles_delete',
            'permissions_view',

            // Bands Management
            'bands_view', 'bands_create', 'bands_edit', 'bands_delete',
            'bands_claim', // registered user can claim

            // Releases Management
            'releases_view', 'releases_create', 'releases_edit', 'releases_delete',

            // Labels Management
            'labels_view', 'labels_create', 'labels_edit', 'labels_delete',

            // Gigs & Organizers Management
            'gigs_view', 'gigs_create', 'gigs_edit', 'gigs_delete',
            'organizers_view', 'organizers_create', 'organizers_edit', 'organizers_delete',

            // Moderation System (Crowdsourcing)
            'moderation_view', 'moderation_approve', 'moderation_reject',
            'claims_view', 'claims_approve', 'claims_reject',

            // System Utilities
            'debug_view', 'audit_logs_view',
            'dashboard_view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. CREATE ROLES AND ASSIGN PERMISSIONS

        // --- SUPERADMIN: Access to everything ---
        $roleSuper = Role::firstOrCreate(['name' => 'SUPERADMIN']);
        $roleSuper->syncPermissions(Permission::all());

        // --- ADMIN / KURATOR: Moderation, Claims, and Data Management ---
        $roleAdmin = Role::firstOrCreate(['name' => 'ADMIN']);
        $roleAdmin->syncPermissions([
            'dashboard_view',
            'users_view', 'users_create', 'users_edit',
            'roles_view',
            'bands_view', 'bands_create', 'bands_edit', 'bands_delete',
            'releases_view', 'releases_create', 'releases_edit', 'releases_delete',
            'labels_view', 'labels_create', 'labels_edit', 'labels_delete',
            'gigs_view', 'gigs_create', 'gigs_edit', 'gigs_delete',
            'organizers_view', 'organizers_create', 'organizers_edit', 'organizers_delete',
            'moderation_view', 'moderation_approve', 'moderation_reject',
            'claims_view', 'claims_approve', 'claims_reject',
            'audit_logs_view',
        ]);

        // --- VERIFIED ENTITY: Managed own assets (Bypass Moderation) ---
        $roleVerified = Role::firstOrCreate(['name' => 'VERIFIED_ENTITY']);
        $roleVerified->syncPermissions([
            'dashboard_view',
            'bands_view', 'bands_create', 'bands_edit',
            'releases_view', 'releases_create', 'releases_edit',
            'labels_view', 'labels_create', 'labels_edit',
            'gigs_view', 'gigs_create', 'gigs_edit',
            'organizers_view', 'organizers_create', 'organizers_edit',
        ]);

        // --- REGISTERED USER: Community Contributor (Need Moderation) ---
        $roleRegistered = Role::firstOrCreate(['name' => 'REGISTERED_USER']);
        $roleRegistered->syncPermissions([
            'dashboard_view',
            'bands_view', 'bands_create', // will be saved as draft
            'releases_view', 'releases_create',
            'gigs_view', 'gigs_create',
            'bands_claim',
        ]);

        // 3. ASSIGN ROLES TO SEED USERS
        $this->assignRoleToUser('superadmin', 'SUPERADMIN');
        $this->assignRoleToUser('curator', 'ADMIN');
        $this->assignRoleToUser('band_owner', 'VERIFIED_ENTITY');
        $this->assignRoleToUser('contributor', 'REGISTERED_USER');
    }

    private function assignRoleToUser($username, $roleName)
    {
        $user = User::where('username', $username)->first();
        if ($user) {
            $user->assignRole($roleName);
        }
    }
}
