<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService {

    // check for cookie and removes the refreshToken in the database and the cookie.
    public function checkCookie($refreshTokenCookie){

        if ($refreshTokenCookie) {
            $refreshToken = PersonalAccessToken::findToken($refreshTokenCookie);

            if ($refreshToken) {
                $refreshToken->delete(); // Remove the refresh token from the database
                cookie()->forget('refresh_token');
            }
        }

        return;
    }

    public function login($credentials, $refreshTokenCookie)
    {
        // Check for cookie and remove the cookie
        $this->checkCookie($refreshTokenCookie);
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
    
            // Create access and refresh tokens
            $accessToken = $this->createTokens($user, 'access');
            $refreshToken = $this->createTokens($user, 'refresh');
    
            // Save the refresh token as an HTTP-only cookie
            $cookie = cookie('refresh_token', $refreshToken, 7 * 24 * 60); // 1 week expiration
    
            return [
                'user' => $user,
                'access_token' => $accessToken,
                'cookie' => $cookie,
            ];
        }
    
        return [
            'error' => 'Invalid credentials',
        ];
    }

  //creates access or refresh token basing on the token type
  private function createTokens($user, $type)
  {
    $tokenType = $type == 'access'  ? 'Access Token' : 'Refresh Token';
    $expiryDate = $type == 'access' ? now()->addMinutes(15) : now()->addWeek();
        
    //create token access/refresh token
    $accessToken = $user->createToken($tokenType, [$tokenType]);
    $accessToken->accessToken->expires_at = $expiryDate;
    $accessToken->accessToken->save();
   
   
   return $accessToken->plainTextToken;
     
  }
  

}
