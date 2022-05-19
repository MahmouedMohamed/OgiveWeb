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
                'label' => $role
            ]);
            $availableAbilities = AvailableAbilities::getAll($role);
            foreach ($availableAbilities as $ability) {
                $addedAbility = Ability::firstOrCreate([
                    'name' => $ability
                    'id' => Str::uuid(),
                ]);
                $addedRole->allowTo($addedAbility);
            }
        }
    }
}
