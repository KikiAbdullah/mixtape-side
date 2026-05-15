<?php

use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
                'name' => 'Super Administrator',
                'email' => 'superadmin@example.com',
                'password' => 'superadmin',
                'nowa' => '081234567890'
            ],
            [
                'username' => 'admin',
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => 'admin',
                'nowa' => '081234567891'
            ],
            [
                'username' => 'staff',
                'name' => 'Staff Operasional',
                'email' => 'staff@example.com',
                'password' => 'staff',
                'nowa' => '081234567892'
            ]
        ];

        foreach ($users as $userData) {
            $user = User::where('username', $userData['username'])->first();
            if (!$user) {
                User::create($userData);
            }
        }
    }
}
