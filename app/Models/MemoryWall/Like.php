<?php

namespace App\Models\MemoryWall;

use App\ConverterModels\LikeType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','memory_id'];
    public function memory()
    {
        return $this->belongsTo(Memory::class,'memory_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function setTypeAttribute($text)
    {
        $this->attributes['type'] = LikeType::$value[$text];
    }

    public function getTypeAttribute($value)
    {
        $source = app()->getLocale() === 'ar' ? 'text_ar' : 'text';
        if ($value) {
            return LikeType::$$source[$value];
        }

        return null;
    }
}
