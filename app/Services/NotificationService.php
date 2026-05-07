<?php

namespace App\Services;

use App\Models\Notification;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class NotificationService
{
    public function createNotification($userId, string $targetRole, string $title, string $message, string $type, $data = null): Notification
    {
        $notification = Notification::create([
            'user_id' => $this->toObjectId($userId),
            'target_role' => $targetRole,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'data' => $this->normalizeData($data),
            'is_read' => false,
            'created_at' => $this->utcNow(),
            'read_at' => null,
        ]);

        return $notification;
    }

    public function getNotifications($user, int $page = 1, int $perPage = 10)
    {
        return Notification::where('user_id', $this->toObjectId($user->_id))
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function getUnreadCount($user): int
    {
        return Notification::where('user_id', $this->toObjectId($user->_id))
            ->where('is_read', false)
            ->count();
    }

    public function markAsRead($user, string $notificationId): ?Notification
    {
        $notification = Notification::where('_id', $this->toObjectId($notificationId))
            ->where('user_id', $this->toObjectId($user->_id))
            ->first();

        if (!$notification) {
            return null;
        }

        $notification->is_read = true;
        $notification->read_at = $this->utcNow();
        $notification->save();

        return $notification;
    }

    public function markAllAsRead($user): int
    {
        $query = Notification::where('user_id', $this->toObjectId($user->_id))
            ->where('is_read', false);

        $count = $query->count();

        if ($count > 0) {
            $query->update([
                'is_read' => true,
                'read_at' => $this->utcNow()
            ]);
        }

        return $count;
    }

    public function latestNotifications($user, int $limit = 5)
    {
        return Notification::where('user_id', $this->toObjectId($user->_id))
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    protected function utcNow(): UTCDateTime
    {
        return new UTCDateTime(now()->getTimestamp() * 1000);
    }

    protected function normalizeData($data)
    {
        if ($data === null) {
            return null;
        }

        if (is_object($data)) {
            return $data;
        }

        if (is_array($data)) {
            $json = json_encode($data);
            $decoded = json_decode($json, false);

            if (is_object($decoded)) {
                return $decoded;
            }

            return (object) $data;
        }

        return null;
    }

    protected function toObjectId($value): ObjectId
    {
        if ($value instanceof ObjectId) {
            return $value;
        }

        if (is_string($value) && preg_match('/^[0-9a-fA-F]{24}$/', $value)) {
            return new ObjectId($value);
        }

        if (is_object($value) && isset($value->_id)) {
            return new ObjectId((string) $value->_id);
        }

        throw new \InvalidArgumentException('Invalid ObjectId');
    }
}
