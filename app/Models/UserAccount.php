<?php

namespace App\Models;

class UserAccount extends BaseModel
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
