<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['user_id'];
    public function memory()
    {
        return $this->belongsTo(Memory::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
