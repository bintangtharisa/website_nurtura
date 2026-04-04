<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{


public function register(Request $request)
{
    if (User::where('email', $request->email)->exists()) {
        return response()->json([
            'message' => 'Email already exists'
        ], 400);
    }

    $user = User::create([
        'username'        => $request->username,
        'email'           => $request->email,
        'password_hash'   => Hash::make($request->password),
        'role'            => $request->role,
        'connection_code' => Str::random(6),
        'code_used'       => false,
        'created_at'      => now(),
        'updated_at'      => now()
    ]);

    $token = JWTAuth::fromUser($user);

    return response()->json([
        'message' => 'Register success',
        'token'   => $token,
        'user'    => $user
    ], 201);
}


public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password_hash)) {
        return response()->json([
            'message' => 'Invalid email or password'
        ], 401);
    }

        $token = JWTAuth::fromUser($user);

    return response()->json([
        'message' => 'Login success',
        'token' => $token,
        'user' => $user
    ]);
}

}