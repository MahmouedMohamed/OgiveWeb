<?php

namespace App\Models;

class UserAccount extends BaseModel
{
    public $incrementing = false;

    protected $fillable = [
        'id',
        'balance',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
