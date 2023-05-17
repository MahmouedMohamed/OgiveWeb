<?php

namespace App\Models\MemoryWall;

use App\ConverterModels\Nationality;
use App\Models\BaseModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Memory extends BaseModel
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id', 'created_by', 'person_name', 'birth_date', 'death_date',
        'brief', 'life_story', 'image', 'nationality',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'memory_id')->orderBy('memory_id', 'DESC');
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

    public function getAgeAttribute()
    {
        $death_date = Carbon::parse($this->death_date);
        $birth_date = Carbon::parse($this->birth_date);

        return $death_date->diffInYears($birth_date);
    }
}
