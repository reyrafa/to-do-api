<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {

    }

    public function register(RegisterRequest $request)
    {
        $validatedRequest = $request->validated();
        $user = User::create($validatedRequest);
        return ApiResponse::success(message: 'User is created successfully', status: 201, data: $user);
    }
}
