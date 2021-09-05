<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Shrouk',
            'user_name' => 'Shrouk Sobhy',
            'email' => 'a@a.com',
            'gender' => 'female',
            'phone_number' => '012',
            'address' => '1',
            'password' => "12345678",
        ]);
        DB::table('users')->insert([
            'name' => 'Mahmoued',
            'user_name' => 'Mahmoued',
            'email' => 'mahmouedmartin222@yahoo.com',
            'gender' => 'male',
            'phone_number' => '0123456789',
            'address' => 'Here',
            'password' => "12345678",
        ]);
    }
}
