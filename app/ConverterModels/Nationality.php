<?php

namespace App\ConverterModels;

class Nationality extends BaseConvertorModel
{
    public static $value = [
        'Egyptian' => 1,
        'Palestinian' => 2,
    ];

    public static $value_ar = [
        'مصري' => 1,
        'فلسطيني' => 2,
    ];

    public static $text = [
        1 => 'Egyptian',
        2 => 'Palestinian',
    ];

    public static $text_ar = [
        1 => 'مصري',
        2 => 'فلسطيني',
    ];
}
