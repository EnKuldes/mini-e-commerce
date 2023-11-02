<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $list_user = array(
            array('name' => 'Administrator', 'email' => 'Administrator@admin.com', 'password' => bcrypt('4dm1n987')),
            array('name' => 'User Test', 'email' => 'user@user.com', 'password' => bcrypt('u53rtest')),
            array('name' => 'Manager', 'email' => 'Manager@manager.com', 'password' => bcrypt('m4n46eR')),
        );
        DB::table('users')->insert($list_user);
    }
}
