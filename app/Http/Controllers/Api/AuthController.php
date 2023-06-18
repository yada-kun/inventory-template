<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\AuthRegisterResource;
use App\Http\Resources\AuthResource;
use App\Models\User;
use App\Http\Services\AuthService;
use App\Traits\HttpResponses;
use App\Utilities\HttpStatusCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HttpResponses;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(AuthLoginRequest $request)
    {
        $refreshTokenCookie = $request->cookie('refresh_token');

        $result = $this->authService->login($request->validated(), $refreshTokenCookie);

        if (isset($result['error'])) {
            return $this->error($result['error'], HttpStatusCodes::HTTP_UNAUTHORIZED);
        }

        return $this->success(AuthResource::make($result), "Authentication successful", HttpStatusCodes::HTTP_OK)
            ->withCookie($result['cookie']);
    }

    public function register(StoreUserRequest $request)
    {
        $user = User::create($request->validated());

        return $this->success(AuthRegisterResource::make($user), 'User has been registered successfully', HttpStatusCodes::HTTP_OK);
    }

    public function refreshToken(Request $request)
    {
        $refreshTokenCookie = $request->cookie('refresh_token');

        $result = $this->authService->checkCookie($refreshTokenCookie);

        if (isset($result['error'])) {
            return $this->error($result['error'], HttpStatusCodes::HTTP_UNAUTHORIZED);
        }

        return response()->json(['error' => 'Token has expired. Please sign in again.'])
            ->cookie(cookie()->forget('refresh_token'));
    }

    public function logout()
    {
        // Implement your logout logic here
    }
}
