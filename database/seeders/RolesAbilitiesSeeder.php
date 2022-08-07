<?php

namespace Database\Seeders;

use App\Models\Ability;
use App\Models\AvailableAbilities;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolesAbilitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $availableRoles = ['Admin', 'Ahed Admin', 'Ataa Admin'];
        foreach ($availableRoles as $role) {
            $addedRole = Role::create([
                'id' => Str::uuid(),
                'name' => $role,
                'label' => $role,
                // 'created_at' => Carbon::now(),
            ]);
            $availableAbilities = AvailableAbilities::getAll($role);
            foreach ($availableAbilities as $ability) {
                $addedAbility = Ability::firstOrCreate([
                    'id' => Str::uuid(),
                    'name' => $ability,
                    // 'created_at' => Carbon::now(),
                ]);
                $addedRole->allowTo($addedAbility);
            }
        }
    }
}
