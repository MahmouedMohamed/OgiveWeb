<?php

namespace App\ConverterModels;

class FoodSharingMarkerPriority
{
    public static $value = [
        'Low' => 1,
        'Above Low' => 3,
        'Normal' => 5,
        'High' => 7,
        'Very High' => 10,
    ];

    public static $text = [
        1 => 'Low' ,
        3 => 'Above Low' ,
        5 => 'Normal' ,
        7 => 'High' ,
        10 => 'Very High',
    ];

    public static $text_ar = [
        1 => 'قليل' ,
        3 => 'أعلي من القليل' ,
        5 => 'متوسط' ,
        7 => 'مهم' ,
        10 => 'الأكثر أهمية',
    ];
}
