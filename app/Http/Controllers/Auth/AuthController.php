<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $validatedRequest = $request->validated();
        if (!Auth::attempt($validatedRequest)) {
            return ApiResponse::error(message: 'Credentials do not match our records.', status: Response::HTTP_UNAUTHORIZED);
        }
        $user = Auth::user();
        $token = $user->createToken('user-token')->plainTextToken;
        return ApiResponse::success(
            message: 'User Log in successfully',
            status: Response::HTTP_OK,
            data: [
                "user" => $user,
                "token" => $token
            ]
        );
    }

    public function register(RegisterRequest $request)
    {
        $validatedRequest = $request->validated();
        $user = User::create($validatedRequest);
        return ApiResponse::success(
            message: 'User is created successfully',
            status: Response::HTTP_CREATED,
            data: $user
        );
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return ApiResponse::success(
            message: 'User is successfully logged out.',
            status: Response::HTTP_OK
        );
    }
}
