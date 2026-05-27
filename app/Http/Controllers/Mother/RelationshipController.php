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

        $query = Relationship::where('status', 'active')
            ->where('mother_id', $motherObjectId);

        if ($request->filled('father_id')) {
            $request->validate([
                'father_id' => ['string', 'regex:/^[0-9a-fA-F]{24}$/'],
            ]);

            $query->where('father_id', new ObjectId((string) $request->father_id));
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

        $father = User::where('_id', $relationship->father_id)->first();

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
            'data' => [
                'relationship_id' => (string) $relationship->_id,
                'mother_id' => (string) $relationship->mother_id,
                'father_id' => (string) $relationship->father_id,
                'status' => 'blocked',
                'disconnected_by' => 'mother',
                'disconnected_at' => $now->toDateTime()->format(\DateTime::ATOM),
                'connection_code' => $mother->anonymous_id ?? null,
                'connection_code_available' => true,
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
