<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Services\NotificationService;
use App\Mail\UserNotificationMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ProfileController extends Controller
{
public function me()
{
    return response()->json([
        'status' => true,
        'message' => 'Success',
        'data' => [
            'id' => (string) auth()->user()->_id,
            'username' => auth()->user()->username,
            'email' => auth()->user()->email,
            'role' => auth()->user()->role
        ]
    ]);
}

   public function updateProfile(Request $request, NotificationService $notificationService)
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


            $changedEmail = $request->filled('email') && $request->email !== $user->email;
            $changedUsername = $request->filled('username') && $request->username !== $user->username;

            $user->username = $request->input('username', $user->username);
            $user->email = $request->input('email', $user->email);
            $user->save();

            if ($changedEmail || $changedUsername) {
                $title = 'Profil Diperbarui';
                $message = $changedEmail ? 'Email Anda berhasil diubah.' : 'Profil Anda berhasil diperbarui.';

                $notificationService->createNotification(
                    $user->_id,
                    $user->role,
                    $title,
                    $message,
                    'account'
                );

                Mail::to($user->email)->send(new UserNotificationMail(
                    'Perubahan Akun Nurtura',
                    'Profil Anda telah diperbarui',
                    $changedEmail
                        ? 'Email akun Anda telah berhasil diperbarui. Jika Anda tidak melakukan perubahan ini, segera hubungi tim support.'
                        : 'Username Anda telah berhasil diperbarui. Jika Anda tidak melakukan perubahan ini, segera hubungi tim support.'
                ));
            }

            return response()->json([
                'status' => true,
                'message' => 'Success',
                'data' => [
                    'id' => (string) $user->_id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role
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