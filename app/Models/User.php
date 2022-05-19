<?php

namespace App\Models;

use App\Models\Ataa\FoodSharingMarker;
use App\Models\Ataa\AtaaAchievement;
use App\Models\Ahed\Needy;
use App\Models\Ahed\OnlineTransaction;
use App\Models\Ahed\OfflineTransaction;
use App\Models\MemoryWall\Memory;
use App\Models\MemoryWall\Like;
use App\Models\TimeCatcher\FCMToken;
use App\Models\BreedMe\Pet;
use App\Models\BreedMe\AdoptionRequest;
use App\Models\BreedMe\Consultation;
use App\Models\BreedMe\ConsultationComment;
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

    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'user_name',
        'email',
        'password',
        'gender',
        'phone_number',
        'address',
        'nationality',
        'profile_id'
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
    public function createAccessToken($accessType, $appType)
    {
        $this->deleteRelatedAccessTokens($appType);
        //Hash::make() -> saves only 60 chars to database
        //TODO: Solve & extend to 255 chars
        $accessToken = Str::random(60);
        $expiryDate = Carbon::now('GMT+2')->addMonth();
        $this->accessTokens()->create([
            'id'=> Str::uuid(),
            'access_token' => Hash::make($accessToken),
            'scopes' => '[]',
            'app_type' => $appType,
            'access_type' => $accessType,
            'active' => 1,
            'expires_at' => $expiryDate,

        ]);
        return ['accessToken' => $accessToken, 'expiryDate' => $expiryDate];
    }
    public function deleteRelatedAccessTokens($appType)
    {
        $this->accessTokens()->where('app_type', '=', $appType)->delete();
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
        return $this->hasMany(Memory::class, 'created_by')->orderBy('id', 'DESC');
    }
    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id');
    }
    public function pets()
    {
        return $this->hasMany(Pet::class, 'created_by');
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
        return $this->hasMany(Needy::class, 'created_by');
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
        return $this->hasMany(UserBan::class, 'banned_user');
    }
    public function createdBans()
    {
        return $this->hasMany(UserBan::class, 'created_by');
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimeStamps();
    }
    public function assignRole($role)
    {
        $this->roles()->sync($role);  //save if not there, replace if there // can pass argument(x,false) //false will let us add without dropping anything
    }
    public function abilities()
    {
        return $this->roles->map->abilities->flatten()->pluck('name')->unique();
    }
    public function hasAbility(String $ability)
    {
        return $this->abilities($ability);
    }
    public function fcmTokens()
    {
        return $this->hasOne(FCMToken::class);
    }
    public function timeCatcherTracked()
    {
        return $this->hasMany(TimeCatcherTracking::class, 'tracked_id');
    }
    public function timeCatcherTracker()
    {
        return $this->hasMany(TimeCatcherTracking::class, 'tracker_id');
    }
}
