<?php

namespace App\Models\MemoryWall;

use App\ConverterModels\Nationality;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memory extends Model
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
        $this->attributes['nationality'] = Nationality::$value[$text];
    }

    public function getNationalityAttribute($value)
    {
        $source = app()->getLocale() === 'ar' ? 'text_ar' : 'text';
        if ($value) {
            return Nationality::$$source[$value];
        }

        return null;
    }
}
