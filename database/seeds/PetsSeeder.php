<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('pets')->insert([
            'user_id' => '1',
            'name' => 'Leo',
            'user_id' => '1',
            'sex' => '1',
            'age' => '1',
            'notes' => 'Full Vccinaion',
            'status' => '1',

        ]);
    }
}
