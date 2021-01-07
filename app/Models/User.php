<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'user_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function accessTokens()
    {
        return $this->hasMany(OauthAccessToken::class);
    }
    public function markers()
    {
        return $this->hasMany(Marker::class)->orderBy('id','DESC');
    }
    public function memories()
    {
        return $this->hasMany(Memory::class)->orderBy('id','DESC');
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function pets()
    {
        return $this->hasMany(Pet::class);
    }
    public function adoptionRequests()
    {
        return $this->hasMany(AdoptionRequest::class);
    }
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }
    public function consultationsComments()
    {
        return $this->hasMany(ConsultationComment::class);
    }
}
