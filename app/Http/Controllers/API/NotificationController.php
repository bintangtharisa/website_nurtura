<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request, NotificationService $notificationService)
    {
        $payload = $request->validate([
            'page' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|integer|min:1|max:50'
        ]);

        $page = $payload['page'] ?? 1;
        $limit = $payload['limit'] ?? 10;

        $notifications = $notificationService->getNotifications(auth()->user(), $page, $limit);

        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => [
                'items' => NotificationResource::collection($notifications->getCollection())->resolve(),
                'meta' => [
                    'current_page' => $notifications->currentPage(),
                    'per_page' => $notifications->perPage(),
                    'total' => $notifications->total(),
                    'last_page' => $notifications->lastPage()
                ]
            ]
        ]);
    }

    public function unreadCount(NotificationService $notificationService)
    {
        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => [
                'count' => $notificationService->getUnreadCount(auth()->user())
            ]
        ]);
    }

    public function markAsRead(Request $request, string $id, NotificationService $notificationService)
    {
        $request->validate([
            'id' => ['required', 'string', 'size:24', 'regex:/^[0-9a-fA-F]{24}$/']
        ]);

        $notification = $notificationService->markAsRead(auth()->user(), $id);

        if (!$notification) {
            return response()->json([
                'status' => false,
                'message' => 'Notification tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => new NotificationResource($notification)
        ]);
    }

    public function readAll(NotificationService $notificationService)
    {
        $count = $notificationService->markAllAsRead(auth()->user());

        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => [
                'updated_count' => $count
            ]
        ]);
    }

    public function destroy(string $id, NotificationService $notificationService)
    {
        if (!preg_match('/^[0-9a-fA-F]{24}$/', $id)) {
            return response()->json([
                'status' => false,
                'message' => 'ID notification tidak valid'
            ], 422);
        }

        $deleted = $notificationService->deleteNotification(auth()->user(), $id);

        if (!$deleted) {
            return response()->json([
                'status' => false,
                'message' => 'Notification tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Success'
        ]);
    }

    public function updateDeviceToken(Request $request)
    {
        $payload = $request->validate([
            'fcm_token' => 'required|string',
            'platform' => 'nullable|string|max:30',
        ]);

        $user = auth()->user();
        $user->fcm_token = $payload['fcm_token'];
        $user->fcm_platform = $payload['platform'] ?? null;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Device token tersimpan',
        ]);
    }

    public function updateFatherSettings(Request $request)
    {
        $payload = $request->validate([
            'risk_only' => 'required|boolean',
            'all_changes' => 'required|boolean',
        ]);

        $user = auth()->user();
        if (($user->role ?? null) !== 'father') {
            return response()->json([
                'status' => false,
                'message' => 'Hanya father yang dapat mengubah pengaturan ini',
            ], 403);
        }

        $allChanges = (bool) $payload['all_changes'];
        $user->father_notif_all_changes = $allChanges;
        $user->father_notif_risk_only = $allChanges ? true : (bool) $payload['risk_only'];
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Pengaturan notifikasi tersimpan',
            'data' => [
                'risk_only' => (bool) $user->father_notif_risk_only,
                'all_changes' => (bool) $user->father_notif_all_changes,
            ],
        ]);
    }
}
