<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\OauthAccessToken;
use Illuminate\Http\Request;
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
        // $oauthAccessToken = OauthAccessToken::find($request['oauthAccessToken']);
        $activeOauthAccessTokens = OauthAccessToken::get();
        foreach ($activeOauthAccessTokens as $accessToken) {
            if (Hash::check($request['oauthAccessToken'], $accessToken->access_token)) {
                return $this->sendResponse($accessToken->refresh(),'Access Token Refreshed Successfully');
            }
        }
        return $this->sendError('This access token can\'t be found');
    }
}
