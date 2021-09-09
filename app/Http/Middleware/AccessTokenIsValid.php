<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // dd($request->bearerToken());
        // return $this->sendForbidden('Invalid Accesstoken');
        return $next($request);
    }
}
