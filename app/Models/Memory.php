<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Memory extends Model
{
    protected $guarded=['createdBy'];
    public function author()
    {
        return $this->belongsTo(User::class,'createdBy');
    }
    public function likes()
    {
        return $this->hasMany(Like::class)->orderBy('memory_id','DESC');
    }
}
