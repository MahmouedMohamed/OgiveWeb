<?php

namespace App\Models;

abstract class AvailableAbilities
{

    const ViewAtaaPrize = "view_ataa_prize";                             //Implemented
    const CreateAtaaPrize = "create_ataa_prize";                             //Implemented
    const UpdateAtaaPrize = "update_ataa_prize";                             //Implemented
    const DeleteAtaaPrize = "delete_ataa_prize";                             //Implemented
    const ActivateAtaaPrize = "activate_ataa_prize";                             //Implemented
    const DeactivateAtaaPrize = "deactivate_ataa_prize";                             //Implemented
    const FreezeAtaaAchievement = "freeze_ataa_achievement";                             //Implemented
    const DefreezeAtaaAchievement = "defreeze_ataa_achievement";                             //Implemented
    const ViewAtaaAchievement = "view_ataa_achievement";                             //Implemented
    const UpdateFoodSharingMarker = "update_food_sharing_marker";                             //Implemented
    const DeleteFoodSharingMarker = "delete_food_sharing_marker";                             //Implemented
    const ViewAtaaReports = "view_ataa_reports";
    const UpdateNeedy = "update_needy";                             //Implemented
    const DeleteNeedy = "delete_needy";                             //Implemented
    const ApproveNeedy = "approve_needy";                             //Implemented
    const DisapproveNeedy = "disapprove_needy";                             //Implemented
    const CollectOfflineTransaction = "collect_offline_transaction";                             //Implemented
    const ViewAhedReports = "view_ahed_reports";
    const ViewUser = "view_user";
    const UpdateUser = "update_user";
    const DeleteUser = "delete_user";
    const ViewUserProfile = "view_user_profile";                             //Implemented
    const UpdateUserProfile = "update_user_profile";                             //Implemented
    const ViewUserBan = "view_user_ban";                             //Implemented
    const CreateUserBan = "create_user_ban";                             //Implemented
    const UpdateUserBan = "update_user_ban";                             //Implemented
    const DeleteUserBan = "delete_user_ban";                             //Implemented
    const ActivateUser = "activate_user_ban";                             //Implemented
    const DeactivateUser = "deactivate_user_ban";                             //Implemented
    const ViewGeneralDashboard = "view_general_dashboard";
    const AssignRoles = "assign_roles";
    const AllowAbilities = "allow_abilities";
    const ViewOnlineTransaction = "view_online_transaction";                             //Implemented
    //TODO: ban_admin to be reviewed
    const BanAdmin = "ban_admin";
    const BanModerator = "ban_moderator";
    const BanAtaaAdmin = "ban_ataa_admin";
    const BanAhedAdmin = "ban_ahed_admin";
    const ViewAtaaBadge = "view_ataa_badge";                             //Implemented
    const CreateAtaaBadge = "create_ataa_badge";                             //Implemented
    const UpdateAtaaBadge = "update_ataa_badge";                             //Implemented
    const DeleteAtaaBadge = "delete_ataa_badge";                             //Implemented
    const ActivateAtaaBadge = "activate_ataa_badge";                             //Implemented
    const DeactivateAtaaBadge = "deactivate_ataa_badge";                             //Implemented

}
