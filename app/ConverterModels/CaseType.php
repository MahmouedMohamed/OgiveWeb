<?php

namespace App\ConverterModels;

class CaseType
{
    public static $value = [
        'Finding Better Place for Living' => 1,
        'Upgrading Standard of Living' => 2,
        'Preparing For Joy' => 3,
        'Dept Paying' => 4,
        'Finding a Cure' => 5,
    ];

    public static $value_ar = [
        'إيجاد مسكن مناسب' => 1,
        'تحسين مستوي المعيشة' => 2,
        'تجهيز لفرحة' => 3,
        'سداد الديون' => 4,
        'إيجاد علاج' => 5,
    ];

    public static $text = [
        1 => 'Finding Better Place for Living',
        2 => 'Upgrading Standard of Living',
        3 => 'Preparing For Joy',
        4 => 'Dept Paying',
        5 => 'Finding a Cure',
    ];

    public static $text_ar = [
        1 => 'إيجاد مسكن مناسب',
        2 => 'تحسين مستوي المعيشة',
        3 => 'تجهيز لفرحة',
        4 => 'سداد الديون',
        5 => 'إيجاد علاج'
    ];
}
