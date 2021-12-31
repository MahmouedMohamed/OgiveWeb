<?php

namespace Database\Seeders;

use App\Models\Ability;
use App\Models\AvailableAbilities;
use App\Models\Role;
use Illuminate\Database\Seeder;

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
                'name' => $role,
                'label' => $role
            ]);
            $availableAbilities = AvailableAbilities::getAll($role);
            foreach ($availableAbilities as $ability) {
                $addedAbility = Ability::firstOrCreate([
                    'name' => $ability
                ]);
                $addedRole->allowTo($addedAbility);
            }
        }
    }
}
