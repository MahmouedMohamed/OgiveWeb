<?php

namespace App\Models;
class BanType
{
    public $types = [
        "LoginBan" => "login_ban",
        "AddNeediesBan" => "add_needies_ban",
        "AddFoodSharingMarker" => "add_food_sharing_marker_ban",
        "CollectFoodSharingMarker" => "collect_food_sharing_marker_ban",
        //ToDo: Add All Functionalities
        //ToDo: Add Admin Functionalities
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
