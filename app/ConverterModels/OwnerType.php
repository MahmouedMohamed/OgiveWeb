<?php

namespace App\ConverterModels;

class OwnerType
{
    //Class name => User, AnonymousUser
    public static $value = [
        'User' => 1,
        'AnonymousUser' => 2,
    ];

    public static $text = [
        1 => 'User',
        2 => 'Anonymous User',
    ];

    public static $text_ar = [
        1 => 'مستخدم حقيقي',
        2 => 'مستخدم وهمي'
    ];
}
