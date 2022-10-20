<?php

namespace App\ConverterModels;

class PlaceType
{
    public static $value = [
        'Sales' => 1,
        'Clinics' => 2,
    ];

    public static $text = [
        1 => 'Sales',
        2 => 'Clinics',
    ];

    public static $text_ar = [
        1 => 'مبيعات',
        2 => 'عيادات',
    ];
}
