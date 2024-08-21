<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;

class VerifyAuthHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $jwtToken = $request->bearerToken();

        /* Check There Is A JWT Token */
        if (is_null($jwtToken)) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'bearerToken' => 'Sorry, Your Bearer Token Is Required!'
                ],
                'message' => 'Your session has expired! Please, login again'
            ], 401);
        }

        /**attempt authentication with token */
        $user = Auth::guard('api')->user();

        /**check if token is invalid */
        if (!$user) {
            return response()->json(['error' => 'User not found or token invalid'], 401);
        }


        // dd($user->token()->id->expires_at->isPast());

        /**set authenticated user to request */
        Auth::setUser($user);


        /**return request */
        return $next($request);
    }
}
