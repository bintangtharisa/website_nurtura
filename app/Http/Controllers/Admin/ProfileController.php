<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ProfileController extends Controller
{
    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            return response()->json([
                'status'   => true,
                'id'       => (string) $user->_id,
                'username' => $user->username,
                'email'    => $user->email,
                'role'     => $user->role
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }
    }
}