<?php

namespace Database\Seeders;

use App\Models\AtaaBadge;
use Illuminate\Database\Seeder;

class AtaaBadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $availableBadges = [
            ['name' => 'Badge 1', 'image' => '', 'description' => ''],
            ['name' => 'Badge 2', 'image' => '', 'description' => ''],
            ['name' => 'Badge 3', 'image' => '', 'description' => ''],
            ['name' => 'Badge 4', 'image' => '', 'description' => ''],
        ];
        foreach ($availableBadges as $badge) {
            AtaaBadge::create([
                'name' => $badge['name'],
                'image' => $badge['image'],
                'description' => $badge['description']
            ]);
        }
    }
}
