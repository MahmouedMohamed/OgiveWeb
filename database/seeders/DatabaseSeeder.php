<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        //   $this->call(UsersTableSeeder::class);
        //  $this->call(NeediesTableSeeder::class);
        // $this->call(UsersSeeder::class);
        // \App\Models\User::factory(5000)->create();
        // \App\Models\Memory::factory(10)->create();
        // \App\Models\Like::factory(10)->create();
        \App\Models\OauthAccessToken::factory(100)->create();
        // \App\Models\FoodSharingMarker::factory(10000)->create();
        // \App\Models\Needy::factory(1)->create();
    }
}
