<?php

namespace App\Models;

abstract class BanTypes
{
    const Login = "login_ban";
    const CollectFoodSharingMarker = "collect_food_sharing_marker_ban";
    const ViewFoodSharingMarker = "view_food_sharing_marker_ban";
    const CreateFoodSharingMarker = "create_food_sharing_marker_ban";
    const ViewAtaaPrize = "view_ataa_prize_ban";                             //Implemented
    const CreateAtaaPrize = "create_ataa_prize_ban";                             //Implemented
    const UpdateAtaaPrize = "update_ataa_prize_ban";                             //Implemented
    const DeleteAtaaPrize = "delete_ataa_prize_ban";                             //Implemented
    const ActivateAtaaPrize = "activate_ataa_prize_ban";                             //Implemented
    const DeactivateAtaaPrize = "deactivate_ataa_prize_ban";                             //Implemented
    const FreezeAtaaAchievement = "freeze_ataa_achievement_ban";                             //Implemented
    const DefreezeAtaaAchievement = "defreeze_ataa_achievement_ban";                             //Implemented
    const ViewAtaaAchievement = "view_ataa_achievement_ban";                             //Implemented
    const UpdateFoodSharingMarker = "update_food_sharing_marker_ban";                             //Implemented
    const DeleteFoodSharingMarker = "delete_food_sharing_marker_ban";                             //Implemented
    const ViewAtaaReports = "view_ataa_reports_ban";
    const CreateNeedy = "create_needy_ban";
    const UpdateNeedy = "update_needy_ban";                             //Implemented
    const DeleteNeedy = "delete_needy_ban";                             //Implemented
    const ApproveNeedy = "approve_needy_ban";                             //Implemented
    const DisapproveNeedy = "disapprove_needy_ban";                             //Implemented
    const CollectOfflineTransaction = "collect_offline_transaction_ban";                             //Implemented
    const ViewAhedReports = "view_ahed_reports_ban";
    const ViewUser = "view_user_ban";
    const UpdateUser = "update_user_ban";
    const DeleteUser = "delete_user_ban";
    const ViewUserProfile = "view_user_profile_ban";                             //Implemented
    const UpdateUserProfile = "update_user_profile_ban";                             //Implemented
    const ViewUserBan = "view_user_ban_ban";                             //Implemented
    const CreateUserBan = "create_user_ban_ban";                             //Implemented
    const UpdateUserBan = "update_user_ban_ban";                             //Implemented
    const DeleteUserBan = "delete_user_ban_ban";                             //Implemented
    const ActivateUser = "activate_user_ban_ban";                             //Implemented
    const DeactivateUser = "deactivate_user_ban_ban";                             //Implemented
    const ViewGeneralDashboard = "view_general_dashboard_ban";
    const AssignRoles = "assign_roles_ban";
    const AllowAbilities = "allow_abilities_ban";
    const ViewOnlineTransaction = "view_online_transaction_ban";                             //Implemented
    const ViewOfflineTransaction = "view_offline_transaction_ban";                             //Implemented
    const UpdateOfflineTransaction = "update_offline_transaction_ban";                             //Implemented
    const DeleteOfflineTransaction = "delete_offline_transaction_ban";                             //Implemented
    //TODO: ban_admin to be reviewed
    const BanAdmin = "ban_admin_ban";
    const BanModerator = "ban_moderator_ban";
    const BanAtaaAdmin = "ban_ataa_admin_ban";
    const BanAhedAdmin = "ban_ahed_admin_ban";
    const ViewAtaaBadge = "view_ataa_badge_ban";                             //Implemented
    const CreateAtaaBadge = "create_ataa_badge_ban";                             //Implemented
    const UpdateAtaaBadge = "update_ataa_badge_ban";                             //Implemented
    const DeleteAtaaBadge = "delete_ataa_badge_ban";                             //Implemented
    const ActivateAtaaBadge = "activate_ataa_badge_ban";                             //Implemented
    const DeactivateAtaaBadge = "deactivate_ataa_badge_ban";                             //Implemented
}
