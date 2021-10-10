<?php

namespace Database\Seeders;

use App\Models\FoodSharingMarker;
use App\Models\User;
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
        // User::factory(19)->create();
        FoodSharingMarker::factory(10000)->create();
        //
        // DB::table('users')->insert([
        //     'name' => 'Shrouk',
        //     'user_name' => 'Shrouk Sobhy',
        //     'email' => 'a@a.com',
        //     'gender' => 'female',
        //     'phone_number' => '012',
        //     'address' => '1',
        //     'password' => "23",
        // ]);
    }
}
