<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {

            $request->merge(['user' => JWTAuth::parseToken()->authenticate()]);
        } catch (\Throwable $th) {

            abort(401, "Token Not Valid");
        }
        return $next($request);
    }
}
