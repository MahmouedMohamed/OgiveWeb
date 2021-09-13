<?php

namespace App\Models;
class BanType
{
    public $types = [
        "Login" => "login_ban",
        "CollectFoodSharingMarker" => "collect_food_sharing_marker_ban",
        //TODO: Add All Functionalities
        "CreateNeedy" => "create_needy_ban",
        "UpdateNeedy" => "update_needy_ban",
        "DeleteNeedy" => "delete_needy_ban",
        "ViewFoodSharingMarker" => "view_food_sharing_marker",
        "CreateFoodSharingMarker" => "create_food_sharing_marker",
        "CollectFoodSharingMarker" => "collect_food_sharing_marker",
        //TODO: Add Admin Functionalities
        "ViewAtaaPrize" => "view_ataa_prize_ban",
        "CreateAtaaPrize" => "create_ataa_prize_ban",
        "UpdateAtaaPrize" => "update_ataa_prize_ban",
        "DeleteAtaaPrize" => "delete_ataa_prize_ban",
        "ActivateAtaaPrize" => "activate_ataa_prize_ban",
        "DeactivateAtaaPrize" => "deactivate_ataa_prize_ban",
        "FreezeAtaaAtchievement" => "freeze_ataa_achievement_ban",
        "DefreezeAtaaAtchievement" => "defreeze_ataa_achievement_ban",
        "ViewAtaaAtchievement" => "view_ataa_achievement_ban",
        "UpdateFoodSharingMarker" => "update_food_sharing_marker_ban",
        "DeleteFoodSharingMarker" => "delete_food_sharing_marker_ban",
        "ViewAtaaReports" => "view_ataa_reports_ban",
        "UpdateNeedy" => "update_needy_ban",
        "DeleteNeedy" => "delete_needy_ban",
        "ApproveNeedy" => "approve_needy_ban",
        "DisapproveNeedy" => "disapprove_needy_ban",
        "CollectOfflineTransaction" => "collect_offline_transaction_ban",
        "ViewAhedReports" => "view_ahed_reports_ban",
        "ViewUser" => "view_user_ban",
        "UpdateUser" => "update_user_ban",
        "DeleteUser" => "delete_user_ban",
        "ViewUserProfile" => "view_user_profile_ban",
        "UpdateUserProfile" => "update_user_profile_ban",
        "ViewUser" => "view_user_ban_ban",
        "CreateUser" => "create_user_ban_ban",
        "UpdateUser" => "update_user_ban_ban",
        "DeleteUser" => "delete_user_ban_ban",
        "ActivateUser" => "activate_user_ban_ban",
        "DeactivateUser" => "deactivate_user_ban_ban",
        "ViewGeneralDashboard" => "view_general_dashboard_ban",
        "AssignRoles" => "assign_roles_ban",
        "AllowAbilities" => "allow_abilities_ban",
        //TODO: ban_admin to be reviewed
        "BanAdmin" => "ban_admin_ban",
        "BanModerator" => "ban_moderator_ban",
        "BanAtaaAdmin" => "ban_ataa_admin_ban",
        "BanAhedAdmin" => "ban_ahed_admin_ban",
    ];
    public function toString()
    {
        $s = "";
        $typesValues = array_values($this->types);
        for ($index = 0; $index < count($typesValues); $index++) {
            $s .= $typesValues[$index];
            if ($index + 1 < count($typesValues))
                $s .= ",";
        }
        return $s;
    }
}
