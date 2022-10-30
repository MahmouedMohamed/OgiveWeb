<?php

namespace App\ConverterModels;

use Illuminate\Support\Collection;

class BaseConvertorModel
{
    public static function prepareOptions(): array
    {
        $source = app()->getLocale() === 'ar' ? 'text_ar' : 'text';
        $options = (new Collection(static::$$source))
            ->map(function ($item, $key) {
                return ['value' => $key, 'label' => $item];
            })
            ->values()
            ->all();

        return $options;
    }
}
