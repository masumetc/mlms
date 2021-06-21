<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class UserTablseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'MD.Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('1234'),
        ]);


        DB::table('users')->insert([
            'name' => 'MD.User',
            'username' => 'user',
            'email' => 'user@gmail.com',
            'password' => bcrypt('1234'),
        ]);
    }
}
