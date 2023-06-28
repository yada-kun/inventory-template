<?php

namespace App\Http\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService {

    // check for cookie and removes the refreshToken in the database and the cookie.
    public function checkCookie($refreshTokenCookie, $requestMethod, $user = null)
    {
   
        if (!$refreshTokenCookie) 
            return false; 

        
        $refreshToken = PersonalAccessToken::findToken($refreshTokenCookie);
      
    
        if (!$refreshToken)
            return false;

        // Token has an expiry date and it has already passed, consider it expired
        if ($refreshToken->expires_at && Carbon::parse($refreshToken->expires_at)->isPast() || 
              ($user && $refreshToken->tokenable->email !== $user->email)) {
              // Remove the refresh token from the database
              $refreshToken->delete();          
              return true;
          } else {
              if ($requestMethod === 'login') {
                  $refreshToken->delete();          
              } else {
                  return $refreshTokenCookie; // Return the associated user
              }
          }
    }

    public function login($credentials, $refreshTokenCookie)
    {
        // Check for cookie and remove the cookie
        $checkCookie = $this->checkCookie($refreshTokenCookie, 'login');
    
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
    public function refresh($refreshTokenCookie)
    {
        $user = Auth::user();
      

       $checkRefreshToken = $this->checkCookie($refreshTokenCookie, 'refresh', $user);
   
       // if checkRefreshToken is false or true it means that the refresh token has expired or has already been deleted in the database and refresh miss use
       if(!$checkRefreshToken || $checkRefreshToken === true)
       return [
        'error' => 'Invalid token, please sign in again',
        ];


        $accessToken = $this->createTokens($user, 'access');

        return [
            'user' => $user,
            'access_token' => $accessToken,
            'cookie' => $checkRefreshToken
        ];

    }

  //creates access or refresh token basing on the token type
  private function createTokens($user, $type)
  {
      $tokenType = $type == 'access' ? 'Access Token' : 'Refresh Token';
      $expiryDate = $type == 'access' ? now()->addMinutes(15) : now()->addWeek();
  
      // Create access/refresh token
      $accessToken = $user->createToken($tokenType, [$tokenType]);
      $accessToken->accessToken->expires_at = $expiryDate;
      $accessToken->accessToken->save();
  
      if($type === 'access')
      return $accessToken->plainTextToken;
      else
      return $accessToken->plainTextToken;
  }  

}
