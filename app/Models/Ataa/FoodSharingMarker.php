<?php

namespace App\Models\Ataa;

use App\ConverterModels\FoodSharingMarkerPriority;
use App\ConverterModels\FoodSharingMarkerType;
use App\ConverterModels\Nationality;
use App\ConverterModels\OwnerType;
use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class FoodSharingMarker extends BaseModel
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'food_sharing_markers';

    protected $fillable = [
        'id', 'owner_type', 'latitude', 'longitude', 'type', 'description', 'quantity', 'priority', 'collected',
        'nationality', 'existed', 'collected_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function collect(bool $existed)
    {
        $this->collected = true;
        $this->existed = $existed;
        $this->collected_at = Carbon::now('GMT+2');
        $this->save();
    }
    public function setTypeAttribute($text)
    {
        $this->attributes['type'] = FoodSharingMarkerType::$value[$text];
    }

    public function getTypeAttribute($value)
    {
        $source = app()->getLocale() === 'ar' ? 'text_ar' : 'text';
        if ($value) {
            return FoodSharingMarkerType::$$source[$value];
        }

        return null;
    }

    public function getPriorityAttribute($value)
    {
        $source = app()->getLocale() === 'ar' ? 'text_ar' : 'text';
        if ($value) {
            return FoodSharingMarkerPriority::$$source[$value];
        }

        return null;
    }

    public function setNationalityAttribute($text)
    {
        $source = app()->getLocale() === 'ar' ? 'value_ar' : 'value';

        $this->attributes['nationality'] = Nationality::$$source[$text];
    }

    public function getNationalityAttribute($value)
    {
        $source = app()->getLocale() === 'ar' ? 'text_ar' : 'text';
        if ($value) {
            return Nationality::$$source[$value];
        }

        return null;
    }

    public function getOwnerTypeAttribute($value)
    {
        $source = app()->getLocale() === 'ar' ? 'text_ar' : 'text';
        if ($value) {
            return OwnerType::$$source[$value];
        }

        return null;
    }
}
