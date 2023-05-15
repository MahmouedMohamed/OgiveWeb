<?php

namespace App\Http\Controllers\api;

use App\Models\OauthAccessToken;
use Illuminate\Http\Request;

class TokensController extends BaseController
{
    /**
     * Refresh the specified token.
     *
     * @return \Illuminate\Http\Response
     */
    public function refresh(Request $request)
    {
        $token = explode('.', $request->bearerToken());
        $userData = sodium_base642bin($token[0], 5);
        $nonceBin = sodium_base642bin($token[1], 5);
        $keyBin = sodium_base642bin($token[2], 5);
        $encodedUserData = (array) json_decode(sodium_crypto_secretbox_open($userData, $nonceBin, $keyBin));
        $accessToken = OauthAccessToken::find($encodedUserData['token_id'] ?? null);
        if ($accessToken) {
            return $this->sendResponse($accessToken->refreshToken($accessToken->access_type, $accessToken->app_type), 'Access Token Refreshed Successfully');
        }

        return $this->sendError('This access token can\'t be found');
    }
}
