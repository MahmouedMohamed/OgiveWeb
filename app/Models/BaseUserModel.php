<?php

namespace App\Models;

use App\ConverterModels\Nationality;
use App\ConverterModels\OwnerType;
use App\Http\Resources\RoleResource;
use App\Models\Ataa\FoodSharingMarker;
use App\Models\Ataa\AtaaAchievement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class BaseUserModel extends AuthenticatableUser
{
    use HasFactory, Notifiable;

    public $incrementing = false;

    protected $table = null;

    public function accessTokens()
    {
        return $this->hasMany(OauthAccessToken::class, 'owner_id');
    }

    public function createAccessToken($accessType, $appType)
    {
        $this->deleteRelatedAccessTokens($appType);
        //Hash::make() -> saves only 60 chars to database
        //TODO: Solve & extend to 255 chars
        $accessToken = Str::random(60);
        $expiryDate = Carbon::now('GMT+2')->addMonth();
        $this->accessTokens()->create([
            'id' => Str::uuid(),
            'owner_type' => OwnerType::$value[class_basename($this)],
            'owner_id' => $this->id,
            'access_token' => Hash::make($accessToken),
            'scopes' => '[]',
            'app_type' => $appType,
            'access_type' => $accessType,
            'active' => 1,
            'expires_at' => $expiryDate,

        ]);

        //ToDo: Try to Add Roles
        $key = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        $encryptedData['token'] = $accessToken;
        $encryptedData['roles'] = $this->roles != null ? RoleResource::collection($this->roles) : null;
        $encryptedData['expiryDate'] = $expiryDate;

        $cipherText = sodium_crypto_secretbox(json_encode($encryptedData), $nonce, $key);
        $accessToken = sodium_bin2base64($cipherText, 5) . '.' . sodium_bin2base64($nonce, 5) . '.' . sodium_bin2base64($key, 5);

        return ['accessToken' => $accessToken, 'expiryDate' => $expiryDate];
    }

    public function deleteRelatedAccessTokens($appType)
    {
        $this->accessTokens()->where('app_type', '=', $appType)->delete();
    }

    public function foodSharingMarkers()
    {
        return $this->hasMany(FoodSharingMarker::class, 'owner_id')->orderBy('id', 'DESC');
    }

    public function ataaAchievement()
    {
        return $this->hasOne(AtaaAchievement::class, 'owner_id');
    }

    public function bans()
    {
        return $this->hasMany(UserBan::class, 'banned_user');
    }

    public function setNationalityAttribute($text)
    {
        $source = app()->getLocale() === 'ar' ? 'value_ar' : 'value';

        $this->attributes['nationality'] = Nationality::$$source[$text];
    }

    public function getNationalityAttribute($value)
    {
        $source = app()->getLocale() === 'ar' ? 'text_ar' : 'text';
        if ($value) {
            return Nationality::$$source[$value];
        }

        return null;
    }

    public function getNationalityValue()
    {
        $source = app()->getLocale() === 'ar' ? 'value_ar' : 'value';
        if ($this->nationality) {
            return Nationality::$$source[$this->nationality];
        }

        return null;
    }
}
