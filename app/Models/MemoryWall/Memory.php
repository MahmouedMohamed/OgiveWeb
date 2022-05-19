<?php

namespace App\Models\MemoryWall;

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
        return $this->belongsTo(User::class,'createdBy');
    }
    public function likes()
    {
        return $this->hasMany(Like::class,'memoryId')->orderBy('memoryId','DESC');
    }
}
