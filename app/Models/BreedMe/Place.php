<?php

namespace App\Models\BreedMe;

use App\ConverterModels\PlaceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    public $incrementing = false;

    public function setTypeAttribute($text)
    {
        $this->attributes['type'] = PlaceType::$value[$text];
    }

    public function getTypeAttribute($value)
    {
        $source = app()->getLocale() === 'ar' ? 'text_ar' : 'text';
        if ($value) {
            return PlaceType::$$source[$value];
        }

        return null;
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', '=', $type);
    }
}
