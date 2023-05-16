<?php

namespace App\Models;

use App\ConverterModels\Nationality;
use App\ConverterModels\OwnerType;
use App\Models\Ataa\AtaaAchievement;
use App\Models\Ataa\FoodSharingMarker;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

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
        $expiryDate = Carbon::now('GMT+2')->addMonth();
        $accessToken = $this->accessTokens()->create([
            'owner_type' => OwnerType::$value[class_basename($this)],
            'owner_id' => $this->id,
            'app_type' => $appType,
            'access_type' => $accessType,
            'active' => 1,
            'expires_at' => $expiryDate,
        ]);

        //ToDo: Try to Add Roles
        $key = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        $encryptedData['token_id'] = $accessToken->id;
        $encryptedData['owner_id'] = $this->id;
        $encryptedData['owner_type'] = OwnerType::$value[class_basename($this)];
        $encryptedData['app_type'] = $appType;
        $encryptedData['access_type'] = $accessType;
        $encryptedData['expiryDate'] = $expiryDate;

        $cipherText = sodium_crypto_secretbox(json_encode($encryptedData), $nonce, $key);
        $accessToken = sodium_bin2base64($cipherText, 5).'.'.sodium_bin2base64($nonce, 5).'.'.sodium_bin2base64($key, 5);

        return ['accessToken' => $accessToken, 'expiryDate' => $expiryDate];
    }

    public function deleteRelatedAccessTokens($appType)
    {
        $this->accessTokens()->where('app_type', '=', $appType)->delete();
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

    public function foodSharingMarkers(): MorphMany
    {
        return $this->morphMany(FoodSharingMarker::class, 'user', 'owner_type', 'owner_id');
    }
}
