<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Ability;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Admin -> Promote Others, Assign Roles, Allow Abilities, ELSE OF M_ Ahed_A Ataa_A
        //Moderator -> RUD 'User', CRUD 'UserBan',

        $ataaAbilities = [
            'view_ataa_prize', 'create_ataa_prize', 'update_ataa_prize', 'delete_ataa_prize', 'activate_ataa_prize', 'deactivate_ataa_prize',
            'freeze_ataa_achievement', 'defreeze_ataa_achievement', 'view_ataa_achievement',
            'update_food_sharing_marker', 'delete_food_sharing_marker',
            'view_ataa_reports','view_ataa_badge', 'create_ataa_badge', 'update_ataa_badge', 'delete_ataa_badge', 'activate_ataa_badge', 'deactivate_ataa_badge',
        ];
        $ahedAbilities = [
            'update_needy', 'delete_needy', 'approve_needy', 'disapprove_needy', 'collect_offline_transaction', 'view_ahed_reports'
        ];
        $moderatorAbilities = [
            'view_user', 'update_user', 'delete_user',
            'view_user_profile', 'update_user_profile',
            'view_user_ban', 'create_user_ban', 'update_user_ban', 'delete_user_ban', 'activate_user_ban', 'deactivate_user_ban',
            'view_general_dashboard'
        ];
        //TODO: ban_admin to be reviewed
        $adminAbilities = ['assign_roles', 'allow_abilities', 'ban_admin', 'ban_moderator', 'ban_ataa_admin', 'ban_ahed_admin'];
        $adminAbilities = array_merge($adminAbilities, $moderatorAbilities, $ataaAbilities, $ahedAbilities);

        $availableRoles = [
            ['name' => 'Admin', 'label' => 'admin', 'abilities' => $adminAbilities],
            ['name' => 'Moderator', 'label' => 'moderator', 'abilities' => $moderatorAbilities],
            ['name' => 'Ahed Admin', 'label' => 'ahed_admin', 'abilities' => $ahedAbilities],
            ['name' => 'Ataa Admin', 'label' => 'ataa_admin', 'abilities' => $ataaAbilities],
        ];
        foreach ($availableRoles as $role) {
            $insertedRole = Role::create([
                'id' => Str::uuid(),
                'name' => $role['name'],
                'label' => $role['label']
            ]);
            foreach ($role['abilities'] as $ability) {
                $insertedAbility = null;
                $roleAbility = Ability::where('name', '=', $ability)->get()->first();
                if ($roleAbility)
                    $insertedRole->allowTo($roleAbility);
                else {
                    $insertedAbility = Ability::create([
                        'id' => Str::uuid(),
                        'name' => $ability
                    ]);
                    $insertedRole->allowTo(
                        $insertedAbility
                    );
                }
            }
        }
    }
}
