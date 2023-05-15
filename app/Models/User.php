<?php

namespace App\Models;

use App\ConverterModels\Gender;
use App\ConverterModels\Nationality;
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

class User extends BaseUserModel
{

    protected $table = 'users';

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
        'nationality'
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

    public function profile()
    {
        return $this->hasOne(Profile::class);
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
    public function account()
    {
        return $this->hasOne(UserAccount::class);
    }
    public function settings()
    {
        return $this->hasOne(UserSettings::class);
    }
    public function setGenderAttribute($text)
    {
        $this->attributes['gender'] = Gender::$value[$text];
    }

    public function getGenderAttribute($value)
    {
        $source = app()->getLocale() === 'ar' ? 'text_ar' : 'text';
        if ($value) {
            return Gender::$$source[$value];
        }

        return null;
    }
}
