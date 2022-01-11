<?php

namespace App\Models\MemoryWall;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memory extends Model
{
    use HasFactory;

    protected $guarded=['createdBy'];
    public function author()
    {
        return $this->belongsTo(User::class,'createdBy');
    }
    public function likes()
    {
        return $this->hasMany(Like::class,'memoryId')->orderBy('memoryId','DESC');
    }
}
