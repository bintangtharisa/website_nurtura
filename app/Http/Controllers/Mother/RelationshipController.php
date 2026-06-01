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
    public function getKoneksi(Request $request)
    {
        $mother = $request->user();
        $motherObjectId = $this->toObjectId($mother->_id);

        // Cek koneksi aktif
        $active = Relationship::where('mother_id', $motherObjectId)
            ->where('status', 'active')
            ->first();

        if ($active) {
            $father = User::where('_id', $active->father_id)->first();
            return response()->json([
                'status' => true,
                'data' => [
                    'pasangan' => $father ? [
                        'id'    => (string) $father->_id,
                        'name'  => $father->name ?? $father->username,
                        'email' => $father->email,
                        'photo' => $father->photo ?? null,
                        'sejak' => $active->connected_at
                                    ? $active->connected_at->toDateTime()->format('d M Y')
                                    : '-',
                    ] : null,
                    'pending_request' => null,
                ],
            ]);
        }

        // Cek pending request
        $pending = Relationship::where('mother_id', $motherObjectId)
            ->where('status', 'pending')
            ->whereNull('disconnected_by')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($pending) {
            $father = User::where('_id', $pending->father_id)->first();
            return response()->json([
                'status' => true,
                'data' => [
                    'pasangan' => null,
                    'pending_request' => $father ? [
                        'id'    => (string) $father->_id,
                        'name'  => $father->name ?? $father->username,
                        'email' => $father->email,
                        'photo' => $father->photo ?? null,
                    ] : null,
                ],
            ]);
        }

        // Tidak ada koneksi
        return response()->json([
            'status' => true,
            'data' => [
                'pasangan'        => null,
                'pending_request' => null,
            ],
        ]);
    }

    public function acceptFather(Request $request, NotificationService $notificationService)
    {
        $request->validate([
            'father_id' => ['required', 'string', 'regex:/^[0-9a-fA-F]{24}$/'],
        ]);

        $mother = $request->user();
        $motherObjectId = $this->toObjectId($mother->_id);
        $fatherObjectId = new ObjectId((string) $request->father_id);

        $activeRelationship = Relationship::where('mother_id', $motherObjectId)
            ->where('status', 'active')
            ->first();

        if ($activeRelationship) {
            return response()->json([
                'status' => false,
                'message' => 'Ibu sudah memiliki koneksi ayah aktif.',
                'is_connected' => true,
            ], 422);
        }

        $relationship = Relationship::where('mother_id', $motherObjectId)
            ->where('father_id', $fatherObjectId)
            ->where('status', 'pending')
            ->whereNull('disconnected_by')
            ->first();

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

        $father = User::where('_id', $relationship->father_id)->first();

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
            'data' => [
                'relationship_id' => (string) $relationship->_id,
                'mother_id' => (string) $relationship->mother_id,
                'father_id' => (string) $relationship->father_id,
                'status' => 'active',
                'connected_at' => $now->toDateTime()->format(\DateTime::ATOM),
            ],
        ]);
    }

    public function blockFather(Request $request, NotificationService $notificationService)
    {
        $mother = $request->user();
        $motherObjectId = $this->toObjectId($mother->_id);

        // Cek koneksi active ATAU pending
        $relationship = Relationship::where('mother_id', $motherObjectId)
            ->whereIn('status', ['active', 'pending'])
            ->whereNull('disconnected_by')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$relationship) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada koneksi untuk diblokir.',
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

        $father = User::where('_id', $relationship->father_id)->first();

        if ($father) {
            $notificationService->createNotification(
                $father->_id,
                'father',
                'Koneksi Ditolak',
                'Ibu telah menolak permintaan koneksi Anda.',
                'connection',
                [
                    'mother_id' => (string) $mother->_id,
                    'relationship_status' => 'blocked',
                ]
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'Permintaan koneksi berhasil ditolak.',
            'is_connected' => false,
            'data' => [
                'relationship_id' => (string) $relationship->_id,
                'status' => 'blocked',
                'disconnected_by' => 'mother',
                'disconnected_at' => $now->toDateTime()->format(\DateTime::ATOM),
            ],
        ]);
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
}