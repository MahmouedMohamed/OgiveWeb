<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NeediesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('needies')->insert([
            'id'=> Str::uuid(),
            'name' => 'كفالة يتيم',
            'severity' => '1',
            'type' => 'كفالة',
            'address'=> 'Cair, EG',
            'need' => '1000',
            'collected' => '10',
            'type' => 'كفالة',
            'details'=> "في اسراع وقت",
            'approved'=>'1',
            'createdBy'=> '3'

        ]);
    }
}
