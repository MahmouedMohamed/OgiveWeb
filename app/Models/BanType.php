<?php

namespace App\Models;
class BanType
{
    public $types = [
        "Login" => "login_ban",
        "AddFoodSharingMarker" => "add_food_sharing_marker_ban",
        "CollectFoodSharingMarker" => "collect_food_sharing_marker_ban",
        //ToDo: Add All Functionalities
        "CreateNeedy" => "create_needy_ban",
        "UpdateNeedy" => "update_needy_ban",
        "DeleteNeedy" => "delete_needy_ban",
        "ViewFoodSharingMarker" => "view_food_sharing_marker",
        "CreateFoodSharingMarker" => "create_food_sharing_marker",
        "CollectFoodSharingMarker" => "collect_food_sharing_marker",
        //ToDo: Add Admin Functionalities
        "ApproveNeedy" => "approve_needy_ban",
        "DisapproveNeedy" => "disapprove_needy_ban",

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
