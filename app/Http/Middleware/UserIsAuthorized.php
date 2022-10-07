<?php

namespace App\Http\Middleware;

use App\Models\OauthAccessToken;
use App\Traits\ApiResponse;
use Closure;
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

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->bearerToken()) {
            $activeOauthAccessTokens = Cache::remember('oauthAccessTokens', 60 * 60 * 24, function () {
                return OauthAccessToken::where('active', '=', 1)
                    ->get();
            });

            foreach ($activeOauthAccessTokens as $accessToken) {
                if (Hash::check($request->bearerToken(), $accessToken->access_token)) {
                    if ($this->isValidAccessToken($accessToken, $accessToken->appType)) {
                        request()->merge([
                            'user' => $accessToken->user
                        ]);
                        return $next($request);
                    }
                }
            }
        } else if (Route::currentRouteName() == static::PUBLIC_ROUTE_NAME) {
            return $next($request);
        }
        return $this->sendForbidden('Invalid Access token');
    }
}
