<?php

namespace App\Models\MemoryWall;

use App\ConverterModels\LikeType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'memory_id', 'type'];

    /**
     * Set the keys for a save update query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery($query)
    {
        $query->where('memory_id', '=', $this->memory_id)->where('user_id', '=', $this->user_id);

        return $query;
    }

    public function memory()
    {
        return $this->belongsTo(Memory::class, 'memory_id');
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
