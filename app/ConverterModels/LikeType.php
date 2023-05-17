<?php

namespace App\ConverterModels;

class LikeType
{
    public static $value = [
        'Like' => 1,
        'Love' => 2,
    ];

    public static $value_ar = [
        'إعجاب' => 1,
        'أحببته' => 2,
    ];

    public static $text = [
        1 => 'Like',
        2 => 'Love',
    ];

    public static $text_ar = [
        1 => 'إعجاب',
        2 => 'أحببته',
    ];
}
