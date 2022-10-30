<?php

namespace App\Http\Middleware;

use App\Models\OauthAccessToken;
use App\Traits\ApiResponse;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class UserIsAuthorized
{
    use ApiResponse;

    const PUBLIC_ROUTE_NAME = 'public';

    public function isValidAccessToken($accessToken, $appType)
    {
        //ToDo: Check if accessToken is specified for this appType
        return $accessToken->active;
    }

    public function allowedToAnonymous()
    {
        $routeNames = explode('.', Route::currentRouteName());
        foreach ($routeNames as $name) {
            if ($name == 'anonymous') {
                return true;
            }
        }
        return false;
    }
    public function allowedToPublic()
    {
        $routeNames = explode('.', Route::currentRouteName());
        foreach ($routeNames as $name) {
            if ($name == static::PUBLIC_ROUTE_NAME) {
                return true;
            }
        }
        return false;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = $request->bearerToken();
            if ($token) {
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
                    if (
                        Hash::check($encodedUserData['token'], $accessToken->access_token)
                    ) {
                        if ($this->isValidAccessToken($accessToken, $accessToken->appType)) {
                            if (
                                $accessToken->owner_type != 2 //anonymous
                                || $this->allowedToAnonymous()
                                || $this->allowedToPublic()
                            ) {
                                request()->merge([
                                    'user' => $accessToken->user
                                ]);
                                return $next($request);
                            }
                        }
                    }
                }
            } else if ($this->allowedToPublic()) {
                return $next($request);
            }
        } catch (Exception $ex) {
            return $this->sendForbidden('Invalid Access token');
        }
        return $this->sendForbidden(__('General.InvalidAccess'));
    }
}
