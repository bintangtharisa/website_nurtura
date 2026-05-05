<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Carbon\Carbon;
use App\Models\User;
use App\Models\PasswordResetToken;
use App\Mail\ResetPasswordMail;

class ForgotPasswordController extends Controller
{
        public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $key = 'forgot-password:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            return response()->json([
                'status' => false,
                'message' => 'Terlalu banyak request, coba lagi nanti'
            ]);
        }

        RateLimiter::hit($key, 60);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Email tidak ditemukan'
            ]);
        }

        $plainToken = Str::random(64);
        $hashedToken = Hash::make($plainToken);

        PasswordResetToken::updateOrCreate(
            ['email' => $request->email],
            [
                'token' => $hashedToken,
                'created_at' => now()
            ]
        );
        $link = url('/reset-password?token=' . $plainToken . '&email=' . $request->email);

        Mail::to($request->email)->send(new ResetPasswordMail($link));

        return response()->json([
            'status' => true,
            'message' => 'Link reset password dikirim ke email'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $record = PasswordResetToken::where('email', $request->email)->first();

        if (!$record) {
            return response()->json([
                'status' => false,
                'message' => 'Token tidak ditemukan'
            ]);
        }

        if (!Hash::check($request->token, $record->token)) {
            return response()->json([
                'status' => false,
                'message' => 'Token tidak valid'
            ]);
        }

        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            return response()->json([
                'status' => false,
                'message' => 'Token sudah expired'
            ]);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan'
            ]);
        }

        $user->password_hash = Hash::make($request->password);

        if (isset($user->token_version)) {
            $user->token_version += 1;
        }

        $user->save();

        PasswordResetToken::where('email', $request->email)->delete();

        Mail::raw("Password anda berhasil diubah. Jika bukan anda, segera hubungi admin.", function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Password Berhasil Diubah');
        });

        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login dengan password baru Anda.');
    }
}
