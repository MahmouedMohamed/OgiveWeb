<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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
        'gender',
        'phone_number',
        'address',
        'profile'
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
    public function createAccessToken()
    {
        $this->deleteRelatedAccessTokens();
        //Hash::make() -> saves only 60 chars to database
        //ToDo: Solve & extend to 255 chars
        $accessToken = Str::random(60);
        $this->accessTokens()->create([
            'access_token' => Hash::make($accessToken),
            'scopes' => '[]',
            'active' => 1,
            'expires_at' => Carbon::now('GMT+2')->addMinute(),

        ]);
        return $accessToken;
    }
    public function deleteRelatedAccessTokens()
    {
        $this->accessTokens()->delete();
    }
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    public function foodSharingMarkers()
    {
        return $this->hasMany(FoodSharingMarker::class)->orderBy('id', 'DESC');
    }
    public function memories()
    {
        return $this->hasMany(Memory::class)->orderBy('id', 'DESC');
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
    public function createdNeedies()
    {
        return $this->hasMany(Needy::class, 'createdBy');
    }
    public function onlinetransactions()
    {
        return $this->hasMany(OnlineTransaction::class, 'giver');
    }
    public function offlinetransactions()
    {
        return $this->hasMany(OfflineTransaction::class, 'giver');
    }
    public function ataaAchievement()
    {
        return $this->hasOne(AtaaAchievement::class);
    }
    public function bans()
    {
        return $this->hasMany(UserBan::class,'banned_user');
    }

    public function createdBans()
    {
        return $this->hasMany(UserBan::class,'created_by');
    }
    //To Be Done using roles or just a column
    public function isAdmin()
    {
        return false;
    }
}
