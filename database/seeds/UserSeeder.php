<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'admin',
            'name' => 'Administrator',
            'email' => 'nizar.hdt@gmail.com',
            'password' => md5('admin'),
            'nowa' => '082132922001'
        ]);   
    }
}
