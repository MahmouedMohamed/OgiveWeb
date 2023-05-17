<?php

namespace App\ConverterModels;

class Gender
{
    public static $value = [
        'Male' => 1,
        'Female' => 2,
    ];

    public static $text = [
        1 => 'Male',
        2 => 'Female',
    ];

    public static $text_ar = [
        1 => 'ذكر',
        2 => 'أنثي',
    ];
}
