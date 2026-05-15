<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Relationship;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\UserNotificationMail;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private function bsonDate($time = null)
    {
        return new UTCDateTime(($time ?? now())->getTimestamp() * 1000);
    }

    public function register(Request $request, NotificationService $notificationService)
    {
        $request->merge([
            'email' => strtolower($request->email)
        ]);

        $request->validate([
            'name' => ['required', 'string', 'min:4', 'max:50'],
            'email' => [
                'required',
                'string',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'unique:users,email'
            ],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'string', 'in:ayah,ibu,admin'],
            'connection_code' => [
                'required_if:role,ayah',
                'nullable',
                'string',
                'size:6'
            ]
        ], [
            'email.unique' => 'Email sudah digunakan oleh akun lain.'
        ]);

        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Email sudah digunakan oleh akun lain.',
                'errors' => [
                    'email' => ['Email sudah digunakan oleh akun lain.']
                ]
            ], 422);
        }

        $role = match ($request->role) {
            'ayah' => 'father',
            'ibu'  => 'mother',
            default => 'admin'
        };

        $mother = null;

        if ($role === 'father') {

            $mother = User::where([
                'anonymous_id' => strtoupper($request->connection_code),
                'role' => 'mother'
            ])->first();

            if (!$mother) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kode koneksi ibu tidak ditemukan atau salah.',
                    'errors' => [
                        'connection_code' => ['Kode koneksi ibu tidak ditemukan atau salah.']
                    ]
                ], 422);
            }

            $existingRelationship = Relationship::where('mother_id', new ObjectId((string) $mother->_id))->exists();

            if ($existingRelationship) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kode koneksi ibu sudah digunakan oleh ayah lain.',
                    'errors' => [
                        'connection_code' => ['Kode koneksi ibu sudah digunakan oleh ayah lain.']
                    ]
                ], 422);
            }
        }

        $data = [
            'username' => $request->name,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'role' => $role,
            'notification_enabled' => true,
            'last_login' => null,
            'created_at' => $this->bsonDate(),
            'updated_at' => null,
        ];

        if ($role === 'mother') {
            $data['anonymous_id'] = strtoupper(Str::random(6));
        }

        $user = User::create($data);

        $notificationService->createNotification(
            $user->_id,
            $role,
            'Selamat Datang',
            'Akun Anda berhasil dibuat',
            'welcome'
        );

        Mail::to($user->email)->send(new UserNotificationMail(
            'Selamat Datang di Nurtura',
            'Selamat Datang di Nurtura!',
            'Terima kasih telah mendaftar. Akun Nurtura Anda sudah aktif dan Anda dapat mulai menggunakan layanan kami untuk mendukung perjalanan keluarga Anda.'
        ));

        if ($role === 'father') {

            Relationship::create([
                'mother_id' => new ObjectId((string) $mother->_id),
                'father_id' => new ObjectId((string) $user->_id),
                'status' => 'active',
                'connected_at' => $this->bsonDate(),
                'disconnected_at' => null,
                'disconnected_by' => null,
                'reconnect_count' => 0,
                'last_access_by_father' => null,
                'created_at' => $this->bsonDate(),
                'updated_at' => null,
            ]);
            $notificationService->createNotification(
                $mother->_id,
                'mother',
                'Koneksi Berhasil',
                'Ayah berhasil terhubung dengan Anda.',
                'connection',
                ['father_id' => (string) $user->_id]
            );        }

        return response()->json([
            'status' => true,
            'message' => 'Register berhasil',
            'data' => [
                'id' => (string) $user->_id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'connection_code' => $user->anonymous_id ?? null
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $request->merge([
            'email' => strtolower($request->email)
        ]);

        $request->validate([
            'email' => [
                'required',
                'string',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            'password' => ['required', 'string']
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Email tidak ditemukan'
            ], 404);
        }

        if (!Hash::check($request->password, $user->password_hash)) {
            return response()->json([
                'status' => false,
                'message' => 'Password salah'
            ], 401);
        }

        $user->update([
            'last_login' => $this->bsonDate(),
            'updated_at' => $this->bsonDate()
        ]);

        try {
            $token = JWTAuth::fromUser($user);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal membuat token'
            ], 500);
        }

        $relationship = null;

        if ($user->role === 'father') {
            $relationship = Relationship::where([
                'father_id' => $user->_id,
                'status' => 'active'
            ])->first();
        }

        if ($user->role === 'mother') {
            $relationship = Relationship::where([
                'mother_id' => $user->_id,
                'status' => 'active'
            ])->first();
        }

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
            'user' => [
                'id' => (string) $user->_id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'connection_code' => $user->anonymous_id ?? null,
                'is_connected' => $relationship ? true : false
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
                'status' => true,
                'message' => 'Logout berhasil'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Gagal logout',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}