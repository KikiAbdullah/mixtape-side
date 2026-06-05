<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'username' => 'superadmin',
                'name' => 'Kiki Superadmin',
                'email' => 'superadmin@mixtapeside.com',
                'password' => 'password',
                'nowa' => '081234567890',
                // CAPABILITIES:
                // - Full system access (All permissions).
                // - Database management, user/role management.
                // - System debugging and log viewing.
            ],
            [
                'username' => 'curator',
                'name' => 'Nizar Curator',
                'email' => 'curator@mixtapeside.com',
                'password' => 'password',
                'nowa' => '081234567891',
                // CAPABILITIES:
                // - Moderation (Approve/Reject data drafts).
                // - Claim Validation (Approve/Reject profile claims).
                // - Global data management (Edit any band/release/gig).
            ],
            [
                'username' => 'band_owner',
                'name' => 'Indra Verified Owner',
                'email' => 'owner@burgerkill.com',
                'password' => 'password',
                'nowa' => '081234567892',
                // CAPABILITIES:
                // - Verified Entity status.
                // - Manage OWN band/label profile without moderation (Auto-apply).
                // - Manage OWN events/gigs.
            ],
            [
                'username' => 'contributor',
                'name' => 'Joni Contributor',
                'email' => 'contributor@gmail.com',
                'password' => 'password',
                'nowa' => '081234567893',
                // CAPABILITIES:
                // - Registered User status.
                // - Propose new data or edits (Saved as DRAFTS, needs curator approval).
                // - Claim profiles, attend gigs.
            ]
        ];

        foreach ($users as $userData) {
            $user = User::where('username', $userData['username'])->first();
            if (!$user) {
                $user = User::create($userData);
                $user->markEmailAsVerified();
            }
        }
    }
}
