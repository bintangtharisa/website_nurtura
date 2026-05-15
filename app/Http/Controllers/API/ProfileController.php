<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NotificationService;
use App\Mail\UserNotificationMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ProfileController extends Controller
{
    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            return response()->json([
                'status' => true,
                'message' => 'Success',
                'data' => [
                    'id' => (string) $user->_id,
                    'name' => $user->name ?? $user->username,
                    'username' => $user->username ?? null,
                    'email' => $user->email,
                    'role' => $user->role,
                    'photo' => $user->photo ?? null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }
    }

    public function updateProfile(Request $request, NotificationService $notificationService)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $request->validate([
                'username'     => 'sometimes|string|max:255',
                'old_password' => 'sometimes|string'
            ]);

            $changedUsername = $request->filled('username') && $request->username !== $user->username;
            if ($changedUsername && !$request->filled('old_password')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Password lama wajib diisi untuk mengubah username.'
                ], 422);
            }

            if ($request->filled('old_password')) {
                if (!Hash::check($request->old_password, $user->password_hash)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Password salah, data tidak diubah'
                    ], 400);
                }
            }

            if ($changedUsername) {
                $user->username = $request->input('username');
                $user->save();

                $notificationService->createNotification(
                    $user->_id,
                    $user->role,
                    'Profil Diperbarui',
                    'Username Anda berhasil diperbarui.',
                    'account'
                );

                Mail::to($user->email)->send(new UserNotificationMail(
                    'Perubahan Akun Nurtura',
                    'Profil Anda telah diperbarui',
                    'Username Anda telah berhasil diperbarui. Jika Anda tidak melakukan perubahan ini, segera hubungi tim support.'
                ));
            }

            return response()->json([
                'status' => true,
                'message' => 'Success',
                'data' => [
                    'id' => (string) $user->_id,
                    'name' => $user->name ?? $user->username,
                    'username' => $user->username ?? null,
                    'email' => $user->email,
                    'role' => $user->role,
                    'photo' => $user->photo ?? null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
    }

    public function changePassword(Request $request, NotificationService $notificationService)
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

            $notificationService->createNotification(
                $user->_id,
                $user->role,
                'Password Berhasil Diubah',
                'Password Anda berhasil diubah.',
                'security'
            );

            Mail::to($user->email)->send(new UserNotificationMail(
                'Password Berhasil Diubah',
                'Password Akun Anda Telah Diubah',
                'Password akun Nurtura Anda telah berhasil diganti. Jika Anda tidak melakukan perubahan ini, segera ganti password dan hubungi tim support.'
            ));

            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'status' => true,
                'message' => 'Password berhasil diubah, silakan login ulang',
                'data' => []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
