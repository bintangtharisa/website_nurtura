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
}
