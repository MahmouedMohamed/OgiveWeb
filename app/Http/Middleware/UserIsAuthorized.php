<?php

namespace App\Http\Middleware;

use App\Models\OauthAccessToken;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class UserIsAuthorized
{
    use ApiResponse;

    const PUBLIC_ROUTE_NAME = 'public';

    public function isValidAccessToken($accessToken, $appType)
    {
        //ToDo: Check if accessToken is specified for this appType
        return $accessToken && $accessToken->active && $accessToken->expires_at->isAfter(Carbon::now());
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
                $accessToken = OauthAccessToken::find($encodedUserData['token_id'] ?? null);
                if ($this->isValidAccessToken($accessToken, $accessToken->appType)) {
                    if (
                        $accessToken->owner_type != 2 //anonymous
                        || $this->allowedToAnonymous()
                        || $this->allowedToPublic()
                    ) {
                        request()->merge([
                            'user' => $accessToken->user,
                        ]);

                        return $next($request);
                    }
                }
            } elseif ($this->allowedToPublic()) {
                return $next($request);
            }
        } catch (Exception $ex) {
            return $this->sendForbidden('Invalid Access token');
        }

        return $this->sendForbidden(__('General.InvalidAccess'));
    }
}
