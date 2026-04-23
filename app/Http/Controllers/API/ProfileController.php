<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
public function me()
{
    return response()->json([
        'message' => 'Berhasil mengambil profil',
        'data' => [
            'id' => (string) auth()->user()->_id,
            'username' => auth()->user()->username,
            'email' => auth()->user()->email,
            'role' => auth()->user()->role
        ]
    ]);
}

   public function updateProfile(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $request->validate([
                'username'     => 'sometimes|string|max:255',
                'email'        => 'sometimes|email|max:255|unique:users,email,' . $user->_id . ',_id',
                'old_password' => 'required'
            ]);


            if (!Hash::check($request->old_password, $user->password_hash)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Password salah, data tidak diubah'
                ], 400);
            }


            $user->username = $request->input('username', $user->username);
            $user->email    = $request->input('email', $user->email);

            $user->save();

            return response()->json([
                'status'   => true,
                'id'       => (string) $user->_id,
                'username' => $user->username,
                'email'    => $user->email,
                'role'     => $user->role
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
    }

 public function changePassword(Request $request)
{
    try {
        $user = JWTAuth::parseToken()->authenticate();

        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6'
        ]);

        if (!Hash::check($request->old_password, $user->password_hash)) {
            return response()->json([
                'status' => false,
                'message' => 'Password lama salah'
            ], 400);
        }

        if (Hash::check($request->new_password, $user->password_hash)) {
            return response()->json([
                'status' => false,
                'message' => 'Password baru tidak boleh sama dengan password lama'
            ], 400);
        }

        $user->password_hash = bcrypt($request->new_password);
        $user->save();

        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'status' => true,
            'message' => 'Password berhasil diubah, silakan login ulang'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}
}