<?php
namespace App\Models;
abstract class AvailableAbilities{

        const ViewAtaaPrize = "view_ataa_prize";                             //Implemented
        const CreateAtaaPrize = "create_ataa_prize";                             //Implemented
        const UpdateAtaaPrize = "update_ataa_prize";                             //Implemented
        const DeleteAtaaPrize = "delete_ataa_prize";                             //Implemented
        const ActivateAtaaPrize = "activate_ataa_prize";
        const DeactivateAtaaPrize = "deactivate_ataa_prize";
        const FreezeAtaaAchievement = "freeze_ataa_achievement";                             //Implemented
        const DefreezeAtaaAchievement = "defreeze_ataa_achievement";                             //Implemented
        const ViewAtaaAchievement = "view_ataa_achievement";                             //Implemented
        const UpdateFoodSharingMarker = "update_food_sharing_marker";
        const DeleteFoodSharingMarker = "delete_food_sharing_marker";
        const ViewAtaaReports = "view_ataa_reports";
        const UpdateNeedy = "update_needy";
        const DeleteNeedy = "delete_needy";
        const ApproveNeedy = "approve_needy";
        const DisapproveNeedy = "disapprove_needy";
        const CollectOfflineTransaction = "collect_offline_transaction";
        const ViewAhedReports = "view_ahed_reports";
        const ViewUser = "view_user";
        const UpdateUser = "update_user";
        const DeleteUser = "delete_user";
        const ViewUserProfile = "view_user_profile";
        const UpdateUserProfile = "update_user_profile";
        const ViewUserBan = "view_user_ban";
        const CreateUserBan = "create_user_ban";
        const UpdateUserBan = "update_user_ban";
        const DeleteUserBan = "delete_user_ban";
        const ActivateUser = "activate_user_ban";
        const DeactivateUser = "deactivate_user_ban";
        const ViewGeneralDashboard = "view_general_dashboard";
        const AssignRoles = "assign_roles";
        const AllowAbilities = "allow_abilities";
        //TODO: ban_admin to be reviewed
        const BanAdmin = "ban_admin";
        const BanModerator = "ban_moderator";
        const  BanAtaaAdmin = "ban_ataa_admin";
        const  BanAhedAdmin = "ban_ahed_admin";
}
