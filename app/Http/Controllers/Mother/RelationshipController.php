<?php

namespace App\Http\Controllers\Mother;

use App\Http\Controllers\Controller;
use App\Models\Relationship;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class RelationshipController extends Controller
{
    public function pendingRequests(Request $request)
    {
        $mother = $request->user();
        $motherObjectId = $this->toObjectId($mother->_id);

        $relationships = Relationship::where('mother_id', $motherObjectId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $data = $relationships
            ->map(fn ($relationship) => $this->formatConnectionRequest($relationship))
            ->filter()
            ->values();

        $activeRelationship = Relationship::where('mother_id', $motherObjectId)
            ->where('status', 'active')
            ->orderBy('connected_at', 'desc')
            ->first();

        return response()->json([
            'status' => true,
            'message' => 'Permintaan koneksi berhasil diambil.',
            'count' => $data->count(),
            'active_connection' => $activeRelationship
                ? $this->formatActiveConnection($activeRelationship)
                : null,
            'data' => $data,
        ]);
    }

    public function acceptFather(Request $request, NotificationService $notificationService, ?string $fatherId = null)
    {
        $fatherObjectId = $this->fatherObjectIdFromRequest($request, $fatherId);
        if (!$fatherObjectId) {
            return $this->invalidFatherResponse();
        }

        $mother = $request->user();
        $motherObjectId = $this->toObjectId($mother->_id);

        if ($this->hasActiveFather($motherObjectId)) {
            return response()->json([
                'status' => false,
                'message' => 'Ibu sudah memiliki koneksi ayah aktif.',
                'is_connected' => true,
            ], 422);
        }

        $relationship = $this->pendingRelationship($motherObjectId, $fatherObjectId);
        if (!$relationship) {
            return response()->json([
                'status' => false,
                'message' => 'Permintaan koneksi ayah tidak ditemukan atau sudah tidak dapat diterima.',
                'is_connected' => false,
            ], 404);
        }

        $now = $this->bsonDate();

        $relationship->update([
            'status' => 'active',
            'connected_at' => $now,
            'disconnected_at' => null,
            'disconnected_by' => null,
            'updated_at' => $now,
        ]);

        $father = $this->fatherFromRelationship($relationship);
        if ($father) {
            $notificationService->createNotification(
                $father->_id,
                'father',
                'Koneksi Diterima',
                'Ibu telah menerima permintaan koneksi. Anda sekarang dapat melihat report ibu.',
                'connection',
                [
                    'mother_id' => (string) $mother->_id,
                    'relationship_status' => 'active',
                ]
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'Permintaan koneksi ayah berhasil diterima.',
            'is_connected' => true,
            'data' => $this->formatRelationshipStatus($relationship, 'active', [
                'connected_at' => $this->formatDateTime($now),
            ]),
        ]);
    }

    public function rejectFather(Request $request, NotificationService $notificationService, ?string $fatherId = null)
    {
        $fatherObjectId = $this->fatherObjectIdFromRequest($request, $fatherId);
        if (!$fatherObjectId) {
            return $this->invalidFatherResponse();
        }

        $mother = $request->user();
        $motherObjectId = $this->toObjectId($mother->_id);
        $relationship = $this->pendingRelationship($motherObjectId, $fatherObjectId);

        if (!$relationship) {
            return response()->json([
                'status' => false,
                'message' => 'Permintaan koneksi ayah tidak ditemukan atau sudah tidak dapat ditolak.',
                'is_connected' => false,
            ], 404);
        }

        $now = $this->bsonDate();

        $relationship->update([
            'status' => 'blocked',
            'disconnected_at' => $now,
            'disconnected_by' => 'mother',
            'updated_at' => $now,
        ]);

        $father = $this->fatherFromRelationship($relationship);
        if ($father) {
            $notificationService->createNotification(
                $father->_id,
                'father',
                'Permintaan Koneksi Ditolak',
                'Ibu menolak permintaan koneksi. Anda tidak dapat terhubung ke akun ibu ini.',
                'connection',
                [
                    'mother_id' => (string) $mother->_id,
                    'relationship_status' => 'blocked',
                ]
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'Permintaan koneksi ayah berhasil ditolak dan diblokir.',
            'is_connected' => false,
            'data' => $this->formatRelationshipStatus($relationship, 'blocked', [
                'disconnected_by' => 'mother',
                'disconnected_at' => $this->formatDateTime($now),
                'connection_code' => $mother->anonymous_id ?? null,
                'connection_code_available' => true,
            ]),
        ]);
    }

    public function blockFather(Request $request, NotificationService $notificationService, ?string $fatherId = null)
    {
        $mother = $request->user();
        $motherObjectId = $this->toObjectId($mother->_id);

        $query = Relationship::where('mother_id', $motherObjectId)
            ->where('status', 'active');

        $fatherObjectId = $this->fatherObjectIdFromRequest($request, $fatherId, false);
        if ($fatherObjectId) {
            $query->where('father_id', $fatherObjectId);
        }

        $relationship = $query->orderBy('connected_at', 'desc')->first();
        if (!$relationship) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada koneksi ayah aktif untuk diblokir.',
                'is_connected' => false,
            ], 404);
        }

        $now = $this->bsonDate();

        $relationship->update([
            'status' => 'blocked',
            'disconnected_at' => $now,
            'disconnected_by' => 'mother',
            'updated_at' => $now,
        ]);

        $father = $this->fatherFromRelationship($relationship);
        if ($father) {
            $notificationService->createNotification(
                $father->_id,
                'father',
                'Koneksi Diputus',
                'Ibu telah memutuskan koneksi. Anda tidak dapat mengakses report ibu lagi.',
                'connection',
                [
                    'mother_id' => (string) $mother->_id,
                    'relationship_status' => 'blocked',
                ]
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'Koneksi ayah berhasil diputus dan diblokir.',
            'is_connected' => false,
            'data' => $this->formatRelationshipStatus($relationship, 'blocked', [
                'disconnected_by' => 'mother',
                'disconnected_at' => $this->formatDateTime($now),
                'connection_code' => $mother->anonymous_id ?? null,
                'connection_code_available' => true,
            ]),
        ]);
    }

    private function pendingRelationship(ObjectId $motherObjectId, ObjectId $fatherObjectId): ?Relationship
    {
        return Relationship::where('mother_id', $motherObjectId)
            ->where('father_id', $fatherObjectId)
            ->where('status', 'pending')
            ->first();
    }

    private function hasActiveFather(ObjectId $motherObjectId): bool
    {
        return Relationship::where('mother_id', $motherObjectId)
            ->where('status', 'active')
            ->exists();
    }

    private function fatherObjectIdFromRequest(Request $request, ?string $fatherId, bool $required = true): ?ObjectId
    {
        $value = $fatherId ?: $request->input('father_id');

        if (!$value && !$required) {
            return null;
        }

        if (!is_string($value) || !preg_match('/^[0-9a-fA-F]{24}$/', $value)) {
            return null;
        }

        return new ObjectId($value);
    }

    private function invalidFatherResponse()
    {
        return response()->json([
            'status' => false,
            'message' => 'father_id wajib diisi dengan format ObjectId yang valid.',
            'errors' => [
                'father_id' => ['father_id wajib diisi dengan format ObjectId yang valid.'],
            ],
        ], 422);
    }

    private function fatherFromRelationship(Relationship $relationship): ?User
    {
        return User::where('_id', $relationship->father_id)->first();
    }

    private function formatConnectionRequest(Relationship $relationship): ?array
    {
        $father = $this->fatherFromRelationship($relationship);
        if (!$father) {
            return null;
        }

        return [
            'relationship_id' => (string) $relationship->_id,
            'father_id' => (string) $relationship->father_id,
            'father_username' => $father->username ?? null,
            'father_email' => $father->email ?? null,
            'status' => $relationship->status,
            'requested_at' => $this->formatDateTime($relationship->created_at ?? $relationship->connected_at ?? null),
        ];
    }

    private function formatActiveConnection(Relationship $relationship): ?array
    {
        $father = $this->fatherFromRelationship($relationship);
        if (!$father) {
            return null;
        }

        return [
            'relationship_id' => (string) $relationship->_id,
            'father_id' => (string) $relationship->father_id,
            'father_username' => $father->username ?? null,
            'father_email' => $father->email ?? null,
            'status' => $relationship->status,
            'connected_at' => $this->formatDateTime($relationship->connected_at ?? $relationship->created_at ?? null),
        ];
    }

    private function formatRelationshipStatus(Relationship $relationship, string $status, array $extra = []): array
    {
        return array_merge([
            'relationship_id' => (string) $relationship->_id,
            'mother_id' => (string) $relationship->mother_id,
            'father_id' => (string) $relationship->father_id,
            'status' => $status,
        ], $extra);
    }

    private function bsonDate($time = null): UTCDateTime
    {
        return new UTCDateTime(($time ?? now())->getTimestamp() * 1000);
    }

    private function toObjectId($value): ObjectId
    {
        if ($value instanceof ObjectId) {
            return $value;
        }

        return new ObjectId((string) $value);
    }

    private function formatDateTime($value): ?string
    {
        if ($value instanceof UTCDateTime) {
            return $value->toDateTime()->format(\DateTime::ATOM);
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format(\DateTime::ATOM);
        }

        return $value ? (string) $value : null;
    }
}
