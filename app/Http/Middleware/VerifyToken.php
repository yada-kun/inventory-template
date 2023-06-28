<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\HttpResponses;
use App\Utilities\HttpStatusCodes;

class VerifyToken
{
    use HttpResponses;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $accessToken = PersonalAccessToken::findToken($request->bearerToken());

        if ($accessToken) {
         
                $expiresAt = Carbon::createFromTimestamp($accessToken['expires_at']);

                if ($expiresAt->isPast()) {
                    $accessToken->delete();
                    return $this->error('Access token has expired. Please Sign in again', HttpStatusCodes::HTTP_UNAUTHORIZED);
                }

              
                return $next($request);
            }
        

        // Access token not provided or invalid
        return response()->json(['error' => 'Invalid access token.'], 401);
    }
}
