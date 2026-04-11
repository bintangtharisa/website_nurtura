<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

class AuthController extends Controller
{

    /**
     * Register user baru
     */
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

    /**
     * Login user
     */
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

    /**
     * Kirim link reset password ke email
     * POST /api/forgot-password
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Cek apakah email terdaftar
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Untuk alasan keamanan, tetap return success meski email tidak terdaftar
            return response()->json([
                'message' => 'If email exists, password reset link has been sent'
            ], 200);
        }

        // Generate unique token
        $token = Str::random(64);
        $expiresAt = now()->addHours(1); // Token berlaku 1 jam

        // Simpan token ke database
        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => $token,
                'expires_at' => $expiresAt,
                'created_at' => now()
            ]
        );

        // Kirim email dengan link reset password
        try {
            Mail::to($user->email)->send(new ResetPasswordMail($user, $token));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send reset email'
            ], 500);
        }

        return response()->json([
            'message' => 'Password reset link has been sent to your email'
        ], 200);
    }

    /**
     * Validasi token reset password
     * GET /api/reset-password/verify/{token}
     */
    public function verifyResetToken($token)
    {
        $resetRecord = DB::table('password_resets')
            ->where('token', $token)
            ->first();

        if (!$resetRecord) {
            return response()->json([
                'message' => 'Invalid or expired token'
            ], 400);
        }

        // Cek apakah token sudah expired
        if (now()->greaterThan($resetRecord->expires_at)) {
            // Hapus token yang sudah expired
            DB::table('password_resets')->where('token', $token)->delete();
            
            return response()->json([
                'message' => 'Token has expired'
            ], 400);
        }

        return response()->json([
            'message' => 'Token is valid',
            'email' => $resetRecord->email
        ], 200);
    }

    /**
     * Reset password dengan token
     * POST /api/reset-password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);

        // Cari reset token record
        $resetRecord = DB::table('password_resets')
            ->where('token', $request->token)
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return response()->json([
                'message' => 'Invalid reset token'
            ], 400);
        }

        // Cek apakah token sudah expired
        if (now()->greaterThan($resetRecord->expires_at)) {
            DB::table('password_resets')->where('token', $request->token)->delete();
            
            return response()->json([
                'message' => 'Token has expired'
            ], 400);
        }

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // Update password
        $user->update([
            'password_hash' => Hash::make($request->password),
            'updated_at' => now()
        ]);

        // Hapus token yang sudah digunakan
        DB::table('password_resets')->where('token', $request->token)->delete();

        return response()->json([
            'message' => 'Password has been reset successfully'
        ], 200);
    }
}