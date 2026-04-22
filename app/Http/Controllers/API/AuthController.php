<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private function bsonDate($time = null)
    {
        return new UTCDateTime(($time ?? now())->getTimestamp() * 1000);
    }

public function register(Request $request)
{
    $request->validate([
        'name' => ['required', 'string', 'min:4', 'max:50'],
        'email' => ['required', 'string', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
        'password' => ['required', 'string', 'min:6', 'confirmed'],
        'role' => ['required', 'string', 'in:ayah,ibu,admin']
    ]);

    $role = match ($request->role) {
        'ayah' => 'father',
        'ibu'  => 'mother',
        default => 'admin'
    };

    $data = [
        'username'      => $request->name,
        'email'         => $request->email,
        'password_hash' => Hash::make($request->password),
        'role'          => $role,
        'created_at'    => $this->bsonDate(),
        'updated_at'    => $this->bsonDate(),
    ];

    if ($role === 'father') {
        $data['connection_code'] = Str::random(6);
        $data['code_used'] = false;
        $data['code_expires_at'] = $this->bsonDate(now()->addDay());
    }

    if ($role === 'mother') {
        $data['anonymous_id'] = Str::random(10);
    }

    User::create($data);

    return response()->json([
        'message' => 'Register berhasil',
        'status' => true
    ], 201);
}

public function login(Request $request)
{
    $request->validate([
        'email' => ['required', 'string', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
        'password' => ['required', 'string']
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['message' => 'Email tidak ditemukan'], 404);
    }

    if (!Hash::check($request->password, $user->password_hash)) {
        return response()->json(['message' => 'Password salah'], 401);
    }

    \Log::info('before update', ['user_id' => (string) $user->_id]);

    $updated = $user->update([
        'last_login' => $this->bsonDate(),
        'updated_at' => $this->bsonDate()
    ]);

    \Log::info('after update', [
        'updated'    => $updated,
        'last_login' => (string) $user->fresh()->last_login
    ]);

    try {
        $token = JWTAuth::fromUser($user);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Gagal membuat token'], 500);
    }

    return response()->json([
        'message'    => 'Login berhasil',
        'token'      => $token,
        'token_type' => 'bearer',
        'expires_in' => config('jwt.ttl') * 60,
        'user'       => [
            'id'       => (string) $user->_id,
            'username' => $user->username,
            'email'    => $user->email,
            'role'     => $user->role
        ]
    ]);
}

public function logout(Request $request)
{
    try {
        $token = JWTAuth::getToken();

        if ($token) {
            JWTAuth::invalidate($token);
        }

        return response()->json([
            'message' => 'Logout berhasil'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Gagal logout',
            'error' => $e->getMessage()
        ], 500);
    }
}
}