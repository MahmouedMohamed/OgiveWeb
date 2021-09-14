<?php

namespace App\Http\Middleware;

use App\Models\OauthAccessToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccessTokenIsValid
{
    public function sendForbidden($message)
    {
        $response = [
            'Err_Flag' => true,
            'message' => $message,
        ];


        return response()->json($response, 403);
    }
    public function isValidAccessToken($accessToken)
    {
        return OauthAccessToken::where('active', '=', 1)->first() != null;
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
                return $next($request);
            }
        }
        return $this->sendForbidden('Invalid Accesstoken');
    }
}
