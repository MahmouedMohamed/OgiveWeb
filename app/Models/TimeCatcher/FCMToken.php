<?php

namespace App\Models\TimeCatcher;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FCMToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'token'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
