<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\OauthAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class TokensController extends BaseController
{
    /**
     * Refresh the specified token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function refresh(Request $request)
    {
        $token = explode('.', $request->bearerToken());
        $userData = sodium_base642bin($token[0], 5);
        $nonceBin = sodium_base642bin($token[1], 5);
        $keyBin = sodium_base642bin($token[2], 5);
        $encodedUserData = (array) json_decode(sodium_crypto_secretbox_open($userData, $nonceBin, $keyBin));

        $activeOauthAccessTokens = Cache::remember('oauthAccessTokens', 60 * 60 * 24, function () {
            return OauthAccessToken::where('active', '=', 1)
                ->get();
        });

        foreach ($activeOauthAccessTokens as $accessToken) {
            if (Hash::check($encodedUserData['token'], $accessToken->access_token)) {
                return $this->sendResponse($accessToken->refreshToken($accessToken->access_type, $accessToken->app_type),'Access Token Refreshed Successfully');
            }
        }
        return $this->sendError('This access token can\'t be found');
    }
}
