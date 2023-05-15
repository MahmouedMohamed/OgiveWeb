<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
            'id' => Str::uuid(),
            'name' => 'Shrouk',
            'user_name' => 'Shrouk Sobhy',
            'email' => 'a@a.com',
            'gender' => 'female',
            'phone_number' => '012',
            'address' => '1',
            'password' => '12345678',
        ]);
        DB::table('users')->insert([
            'id' => Str::uuid(),
            'name' => 'Mahmoued',
            'user_name' => 'Mahmoued',
            'email' => 'mahmouedmartin222@yahoo.com',
            'gender' => 'male',
            'phone_number' => '0123456789',
            'address' => 'Here',
            'password' => '12345678',
        ]);
    }
}
