<?php

namespace App\Http\Middleware;

use App\Models\OauthAccessToken;
use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserIsAuthorized
{
    use ApiResponse;

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
        $activeOauthAccessTokens = OauthAccessToken::where('active', '=', 1)
            ->get();

        foreach ($activeOauthAccessTokens as $accessToken) {
            if (Hash::check($request->bearerToken(), $accessToken->access_token)) {
                if ($this->isValidAccessToken($accessToken, $accessToken->appType)){
                    request()->merge([
                        'user_id' => $accessToken->user_id
                    ]);
                    return $next($request);
                }
            }
        }
        return $this->sendForbidden('Invalid Access token');
    }
}
