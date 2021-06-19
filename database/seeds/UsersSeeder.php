<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'name' => 'Shrouk',
            'user_name' => 'Shrouk Sobhy',
            'email' => 'a@a.com',
            'gender' => 'female',
            'phone_number' => '012',
            'address' => '1',
            'password' => "23",
        ]);
    }
}
