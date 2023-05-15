<?php

namespace App\ConverterModels;

class PetType
{
    public static $value = [
        'Dog' => 1,
        'Cat' => 2,
        'Bird' => 3,
        'Fish' => 4,
    ];

    public static $text = [
        1 => 'Dog',
        2 => 'Cat',
        3 => 'Bird',
        4 => 'Fish',

    ];

    public static $text_ar = [
        1 => 'كلب',
        2 => 'قط',
        3 => 'طائر',
        4 => 'سمك',

    ];
}
