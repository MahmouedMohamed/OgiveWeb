<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OauthAccessToken extends Model
{
    use HasFactory;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'owner_id',
        'owner_type',
        'access_token',
        'scopes',
        'app_type',
        'access_type',
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
    ];
    public function user()
    {
        if ($this->owner_type == 1)
            return $this->belongsTo(User::class, 'owner_id');
        return $this->belongsTo(AnonymousUser::class, 'owner_id');
    }
    public function refreshToken($accessType, $appType)
    {
        return $this->user->createAccessToken($accessType, $appType);
    }
}
