<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OauthAccessToken extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'access_token',
        'scopes',
        'appType',
        'accessType',
        'active',
        'expires_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'access_token',
        // 'password',
        // 'remember_token',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function refresh()
    {
        return $this->user->createAccessToken();
    }
}
