<?php

namespace App\ConverterModels;

class FoodSharingMarkerType
{
    public static $value = [
        'Food' => 1,
        'Drink' => 2,
        'Both of them' => 3,
    ];

    public static $text = [
        1 => 'Food',
        2 => 'Drink',
        3 => 'Both of them',
    ];

    public static $text_ar = [
        1 => 'طعام',
        2 => 'شراب',
        3 => 'طعام و شراب',
    ];
}
