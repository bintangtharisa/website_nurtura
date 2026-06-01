<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NotificationService;
use App\Mail\UserNotificationMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
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
                    'photo' => $user->photo ?? null,
                    'connection' => $this->fatherConnection($user)
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
                    'photo' => $user->photo ?? null,
                    'connection' => $this->fatherConnection($user)
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

    public function updatePhoto(Request $request)
    {
        $path = null;

        try {
            $user = $this->authenticateRequest($request);

            $request->validate([
                'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            $oldPhoto = $user->photo ?? null;
            $path = $request->file('photo')->store('profile-photos', 'public');

            if (!$path) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menyimpan foto profil'
                ], 500);
            }

            $user->photo = $path;
            $user->save();

            $this->deleteOldProfilePhoto($oldPhoto, $path);

            return response()->json([
                'status' => true,
                'message' => 'Foto profil berhasil diperbarui',
                'data' => [
                    'id' => (string) $user->_id,
                    'name' => $user->name ?? $user->username,
                    'username' => $user->username ?? null,
                    'email' => $user->email,
                    'role' => $user->role,
                    'photo' => $user->photo ?? null,
                    'photo_url' => Storage::disk('public')->url($user->photo),
                    'connection' => $this->fatherConnection($user)
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (JWTException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        } catch (\Throwable $e) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            Log::error('Profile photo upload failed', [
                'error' => $e->getMessage(),
                'user_id' => isset($user) && $user ? (string) $user->_id : null,
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui foto profil'
            ], 500);
        }
    }

    private function authenticateRequest(Request $request)
    {
        $token = $request->bearerToken() ?: $request->input('token');

        if ($token) {
            return JWTAuth::setToken($token)->authenticate();
        }

        return JWTAuth::parseToken()->authenticate();
    }

    private function fatherConnection($user): ?array
    {
        if (($user->role ?? null) !== 'father') {
            return null;
        }

        $db = $this->db();
        $fatherId = (string) $user->_id;
        $fatherObjectId = $this->toObjectId($fatherId);
        $fatherFilters = [['father_id' => $fatherId]];

        if ($fatherObjectId) {
            $fatherFilters[] = ['father_id' => $fatherObjectId];
        }

        $relationship = $db->selectCollection('relationships')->findOne([
            'status' => 'active',
            '$or' => $fatherFilters,
        ], [
            'sort' => ['connected_at' => -1, 'created_at' => -1],
        ]);

        if (!$relationship || empty($relationship['mother_id'])) {
            return [
                'is_connected' => false,
                'mother' => null,
                'connected_at' => null,
            ];
        }

        $motherId = $relationship['mother_id'];
        $motherObjectId = $this->toObjectId($motherId);
        $motherFilters = [['_id' => (string) $motherId, 'role' => 'mother']];

        if ($motherObjectId) {
            $motherFilters[] = ['_id' => $motherObjectId, 'role' => 'mother'];
        }

        $mother = $db->selectCollection('users')->findOne([
            '$or' => $motherFilters,
        ]);

        return [
            'is_connected' => (bool) $mother,
            'mother' => $mother ? [
                'id' => (string) ($mother['_id'] ?? $motherId),
                'username' => $mother['username'] ?? null,
                'anonymous_id' => $mother['anonymous_id'] ?? null,
            ] : null,
            'connected_at' => $this->formatDateTime($relationship['connected_at'] ?? $relationship['created_at'] ?? null),
        ];
    }

    private function db()
    {
        $client = new Client(config('database.connections.mongodb.dsn'));
        return $client->selectDatabase(config('database.connections.mongodb.database'));
    }

    private function toObjectId($value): ?ObjectId
    {
        try {
            if ($value instanceof ObjectId) {
                return $value;
            }

            $stringValue = (string) $value;
            return preg_match('/^[a-f\d]{24}$/i', $stringValue) ? new ObjectId($stringValue) : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function formatDateTime($value): ?string
    {
        if ($value instanceof UTCDateTime) {
            return $value->toDateTime()->format(\DateTime::ATOM);
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format(\DateTime::ATOM);
        }

        if (!empty($value)) {
            return (string) $value;
        }

        return null;
    }

    private function deleteOldProfilePhoto($oldPhoto, ?string $newPhoto = null): void
    {
        if (empty($oldPhoto) || $oldPhoto === $newPhoto) {
            return;
        }

        $oldPhoto = ltrim((string) $oldPhoto, '/');

        if (preg_match('/^https?:\/\//i', $oldPhoto) || !str_starts_with($oldPhoto, 'profile-photos/')) {
            return;
        }

        if (Storage::disk('public')->exists($oldPhoto)) {
            Storage::disk('public')->delete($oldPhoto);
        }
    }
}
