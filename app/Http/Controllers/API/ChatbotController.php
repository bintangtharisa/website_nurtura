<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;

class ChatbotController extends Controller
{
    private function db()
    {
        $client = new Client(config('database.connections.mongodb.dsn'));
        return $client->selectDatabase(config('database.connections.mongodb.database'));
    }

    public function sendMessage(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user || !in_array($user->role, ['mother', 'father'], true)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Chatbot hanya tersedia untuk mother dan father'
                ], 403);
            }

            $message = trim((string) $request->input('message', ''));
            if ($message === '') {
                return response()->json([
                    'status' => false,
                    'message' => 'message wajib diisi'
                ], 422);
            }

            $db = $this->db();
            $this->cleanupExpiredSessions($db, $user);

            $maxLength = $this->maxMessageLength();
            if (mb_strlen($message) > $maxLength) {
                return response()->json([
                    'status' => false,
                    'message' => "message maksimal {$maxLength} karakter"
                ], 422);
            }

            $userId = $this->toObjectId($user->_id);
            $context = $this->buildContext($db, $user);
            if (empty($context['latest_prediction'])) {
                return response()->json([
                    'status' => false,
                    'message' => $user->role === 'father'
                        ? 'Belum ada hasil skrining istri yang bisa digunakan untuk chatbot.'
                        : 'Belum ada hasil skrining yang bisa digunakan untuk chatbot.'
                ], 422);
            }

            $session = $this->resolveSession($db, $request->input('session_id'), $user, $context);
            $sessionId = $session['_id'];
            $now = new UTCDateTime(now()->timestamp * 1000);

            $db->selectCollection('chat_messages')->insertOne([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'app_role' => $user->role,
                'message_role' => 'user',
                'message' => $message,
                'metadata' => [],
                'created_at' => $now,
            ]);

            $history = $this->recentHistory($db, $sessionId);
            $mlApiUrl = rtrim(config('services.ml_api.url'), '/');
            $response = Http::timeout(30)->post($mlApiUrl . '/chatbot', [
                'message' => $message,
                'user_role' => $user->role,
                'context' => $context,
                'history' => $history,
            ]);

            if (!$response->ok()) {
                throw new \Exception('Chatbot AI Error (' . $response->status() . '): ' . $response->body());
            }

            $chatbotResult = $response->json();
            $reply = (string) ($chatbotResult['reply'] ?? 'Maaf, saya belum bisa menjawab saat ini.');

            $db->selectCollection('chat_messages')->insertOne([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'app_role' => $user->role,
                'message_role' => 'assistant',
                'message' => $reply,
                'metadata' => [
                    'source' => $chatbotResult['source'] ?? null,
                    'priority' => $chatbotResult['priority'] ?? null,
                    'suggested_actions' => $chatbotResult['suggested_actions'] ?? [],
                    'disclaimer' => $chatbotResult['disclaimer'] ?? null,
                    'ai_error' => $chatbotResult['ai_error'] ?? null,
                ],
                'created_at' => new UTCDateTime(now()->timestamp * 1000),
            ]);

            $db->selectCollection('chat_sessions')->updateOne(
                ['_id' => $sessionId],
                ['$set' => [
                    'last_message' => $reply,
                    'updated_at' => new UTCDateTime(now()->timestamp * 1000),
                ]]
            );

            return response()->json([
                'status' => true,
                'session' => $this->formatSession($session),
                'message' => [
                    'role' => 'assistant',
                    'message' => $reply,
                    'metadata' => [
                        'source' => $chatbotResult['source'] ?? null,
                        'priority' => $chatbotResult['priority'] ?? null,
                        'suggested_actions' => $chatbotResult['suggested_actions'] ?? [],
                        'disclaimer' => $chatbotResult['disclaimer'] ?? null,
                        'ai_error' => $chatbotResult['ai_error'] ?? null,
                    ],
                ],
                'chatbot' => $chatbotResult,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengirim pesan chatbot',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function sessions(Request $request)
    {
        $user = $request->user();
        if (!$user || !in_array($user->role, ['mother', 'father'], true)) {
            return response()->json([
                'status' => false,
                'message' => 'Chatbot hanya tersedia untuk mother dan father'
            ], 403);
        }

        $db = $this->db();
        $this->cleanupExpiredSessions($db, $user);

        $cursor = $db->selectCollection('chat_sessions')->find([
            'user_id' => $this->toObjectId($user->_id),
            'app_role' => $user->role,
            'deleted_at' => ['$exists' => false],
        ], [
            'sort' => ['updated_at' => -1],
            'limit' => 30,
        ]);

        $sessions = [];
        foreach ($cursor as $session) {
            $sessions[] = $this->formatSession($session);
        }

        return response()->json([
            'status' => true,
            'data' => $sessions,
        ]);
    }

    public function messages(Request $request, string $sessionId)
    {
        $user = $request->user();
        if (!$user || !in_array($user->role, ['mother', 'father'], true)) {
            return response()->json([
                'status' => false,
                'message' => 'Chatbot hanya tersedia untuk mother dan father'
            ], 403);
        }

        $sessionObjectId = $this->toObjectId($sessionId);
        if (!$sessionObjectId) {
            return response()->json([
                'status' => false,
                'message' => 'session_id tidak valid'
            ], 400);
        }

        $db = $this->db();
        $this->cleanupExpiredSessions($db, $user);

        $session = $db->selectCollection('chat_sessions')->findOne([
            '_id' => $sessionObjectId,
            'user_id' => $this->toObjectId($user->_id),
            'app_role' => $user->role,
            'deleted_at' => ['$exists' => false],
        ]);

        if (!$session) {
            return response()->json([
                'status' => false,
                'message' => 'Session chatbot tidak ditemukan'
            ], 404);
        }

        $cursor = $db->selectCollection('chat_messages')->find([
            'session_id' => $sessionObjectId,
            'user_id' => $this->toObjectId($user->_id),
            'deleted_at' => ['$exists' => false],
        ], [
            'sort' => ['created_at' => 1],
        ]);

        $messages = [];
        foreach ($cursor as $message) {
            $messages[] = $this->formatMessage($message);
        }

        return response()->json([
            'status' => true,
            'session' => $this->formatSession($session),
            'data' => $messages,
        ]);
    }

    public function deleteSession(Request $request, string $sessionId)
    {
        $user = $request->user();
        if (!$user || !in_array($user->role, ['mother', 'father'], true)) {
            return response()->json([
                'status' => false,
                'message' => 'Chatbot hanya tersedia untuk mother dan father'
            ], 403);
        }

        $sessionObjectId = $this->toObjectId($sessionId);
        if (!$sessionObjectId) {
            return response()->json([
                'status' => false,
                'message' => 'session_id tidak valid'
            ], 400);
        }

        $db = $this->db();
        $userId = $this->toObjectId($user->_id);
        $session = $db->selectCollection('chat_sessions')->findOne([
            '_id' => $sessionObjectId,
            'user_id' => $userId,
            'app_role' => $user->role,
            'deleted_at' => ['$exists' => false],
        ]);

        if (!$session) {
            return response()->json([
                'status' => false,
                'message' => 'Session chatbot tidak ditemukan'
            ], 404);
        }

        $deletedAt = new UTCDateTime(now()->timestamp * 1000);
        $db->selectCollection('chat_sessions')->updateOne(
            ['_id' => $sessionObjectId],
            ['$set' => [
                'deleted_at' => $deletedAt,
                'updated_at' => $deletedAt,
            ]]
        );

        $db->selectCollection('chat_messages')->updateMany(
            [
                'session_id' => $sessionObjectId,
                'user_id' => $userId,
                'deleted_at' => ['$exists' => false],
            ],
            ['$set' => ['deleted_at' => $deletedAt]]
        );

        return response()->json([
            'status' => true,
            'message' => 'Session chatbot berhasil dihapus'
        ]);
    }

    private function resolveSession($db, $sessionId, $user, array $context)
    {
        $collection = $db->selectCollection('chat_sessions');
        $userId = $this->toObjectId($user->_id);

        if ($sessionId) {
            $sessionObjectId = $this->toObjectId($sessionId);
            if (!$sessionObjectId) {
                throw new \Exception('session_id tidak valid');
            }

            $session = $collection->findOne([
                '_id' => $sessionObjectId,
                'user_id' => $userId,
                'app_role' => $user->role,
                'deleted_at' => ['$exists' => false],
            ]);

            if (!$session) {
                throw new \Exception('Session chatbot tidak ditemukan');
            }

            return $session;
        }

        $now = new UTCDateTime(now()->timestamp * 1000);
        $insert = $collection->insertOne([
            'user_id' => $userId,
            'app_role' => $user->role,
            'related_mother_id' => $this->toObjectId($context['related_mother_id'] ?? null),
            'title' => 'Chat Baru',
            'last_message' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return $collection->findOne(['_id' => $insert->getInsertedId()]);
    }

    private function cleanupExpiredSessions($db, $user): void
    {
        $this->purgeDeletedSessions($db, $user);

        $retentionDays = (int) config('services.chatbot.retention_days', 180);
        if ($retentionDays <= 0) {
            return;
        }

        $userId = $this->toObjectId($user->_id);
        if (!$userId) {
            return;
        }

        $cutoff = new UTCDateTime(now()->subDays($retentionDays)->timestamp * 1000);
        $deletedAt = new UTCDateTime(now()->timestamp * 1000);
        $sessionCollection = $db->selectCollection('chat_sessions');
        $messageCollection = $db->selectCollection('chat_messages');

        $expiredSessions = $sessionCollection->find([
            'user_id' => $userId,
            'app_role' => $user->role,
            'deleted_at' => ['$exists' => false],
            'updated_at' => ['$lt' => $cutoff],
        ], [
            'projection' => ['_id' => 1],
        ]);

        $expiredIds = [];
        foreach ($expiredSessions as $session) {
            $expiredIds[] = $session['_id'];
        }

        if (empty($expiredIds)) {
            return;
        }

        $sessionCollection->updateMany(
            ['_id' => ['$in' => $expiredIds]],
            ['$set' => [
                'deleted_at' => $deletedAt,
                'updated_at' => $deletedAt,
            ]]
        );

        $messageCollection->updateMany(
            [
                'session_id' => ['$in' => $expiredIds],
                'user_id' => $userId,
                'deleted_at' => ['$exists' => false],
            ],
            ['$set' => ['deleted_at' => $deletedAt]]
        );
    }

    private function purgeDeletedSessions($db, $user): void
    {
        $purgeDays = (int) config('services.chatbot.purge_deleted_days', 30);
        if ($purgeDays <= 0) {
            return;
        }

        $userId = $this->toObjectId($user->_id);
        if (!$userId) {
            return;
        }

        $cutoff = new UTCDateTime(now()->subDays($purgeDays)->timestamp * 1000);
        $sessionCollection = $db->selectCollection('chat_sessions');
        $messageCollection = $db->selectCollection('chat_messages');

        $deletedSessions = $sessionCollection->find([
            'user_id' => $userId,
            'app_role' => $user->role,
            'deleted_at' => ['$lte' => $cutoff],
        ], [
            'projection' => ['_id' => 1],
        ]);

        $deletedSessionIds = [];
        foreach ($deletedSessions as $session) {
            $deletedSessionIds[] = $session['_id'];
        }

        if (empty($deletedSessionIds)) {
            return;
        }

        $messageCollection->deleteMany([
            'session_id' => ['$in' => $deletedSessionIds],
            'user_id' => $userId,
        ]);

        $sessionCollection->deleteMany([
            '_id' => ['$in' => $deletedSessionIds],
            'user_id' => $userId,
        ]);
    }

    private function maxMessageLength(): int
    {
        return max(100, (int) config('services.chatbot.max_message_length', 1000));
    }

    private function buildContext($db, $user): array
    {
        $relatedMotherId = (string) $user->_id;
        $mother = null;

        if ($user->role === 'father') {
            $mother = $this->connectedMother($db, $user->_id);
            $relatedMotherId = $mother['id'] ?? null;
        } else {
            $mother = [
                'id' => (string) $user->_id,
                'username' => $user->username ?? null,
                'anonymous_id' => $user->anonymous_id ?? null,
            ];
        }

        $latestPrediction = $mother
            ? $db->selectCollection('prediction_results')->findOne($this->predictionFilter($mother), [
                'sort' => ['created_at' => -1],
            ])
            : null;

        return [
            'user_role' => $user->role,
            'related_mother_id' => $relatedMotherId,
            'mother' => $mother,
            'latest_prediction' => $latestPrediction ? $this->formatBson($latestPrediction) : null,
        ];
    }

    private function connectedMother($db, $fatherId): ?array
    {
        $fatherObjectId = $this->toObjectId($fatherId);
        $fatherFilters = [['father_id' => (string) $fatherId]];
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
            return null;
        }

        $motherId = $relationship['mother_id'];
        $motherObjectId = $this->toObjectId($motherId);
        $motherFilters = [['_id' => (string) $motherId, 'role' => 'mother']];
        if ($motherObjectId) {
            $motherFilters[] = ['_id' => $motherObjectId, 'role' => 'mother'];
        }

        $mother = $db->selectCollection('users')->findOne(['$or' => $motherFilters]);
        if (!$mother) {
            return null;
        }

        return [
            'id' => (string) ($mother['_id'] ?? $motherId),
            'username' => $mother['username'] ?? null,
            'anonymous_id' => $mother['anonymous_id'] ?? null,
        ];
    }

    private function predictionFilter(array $mother): array
    {
        $motherObjectId = $this->toObjectId($mother['id']);
        $orFilters = [
            ['mother_id' => (string) $mother['id']],
        ];

        if ($motherObjectId) {
            $orFilters[] = ['mother_id' => $motherObjectId];
        }

        if (!empty($mother['anonymous_id'])) {
            $orFilters[] = ['anonymous_id' => $mother['anonymous_id']];
        }

        return ['$or' => $orFilters];
    }

    private function recentHistory($db, ObjectId $sessionId): array
    {
        $cursor = $db->selectCollection('chat_messages')->find([
            'session_id' => $sessionId,
            'deleted_at' => ['$exists' => false],
        ], [
            'sort' => ['created_at' => -1],
            'limit' => 8,
        ]);

        $history = [];
        foreach ($cursor as $message) {
            $history[] = [
                'role' => $message['message_role'] ?? 'user',
                'message' => $message['message'] ?? '',
            ];
        }

        return array_reverse($history);
    }

    private function formatSession($session): array
    {
        return [
            'id' => (string) $session['_id'],
            'user_id' => isset($session['user_id']) ? (string) $session['user_id'] : null,
            'role' => $session['app_role'] ?? null,
            'related_mother_id' => isset($session['related_mother_id']) ? (string) $session['related_mother_id'] : null,
            'title' => $session['title'] ?? 'Chat Baru',
            'last_message' => $session['last_message'] ?? null,
            'created_at' => $this->formatDateTime($session['created_at'] ?? null),
            'updated_at' => $this->formatDateTime($session['updated_at'] ?? null),
        ];
    }

    private function formatMessage($message): array
    {
        return [
            'id' => (string) $message['_id'],
            'session_id' => isset($message['session_id']) ? (string) $message['session_id'] : null,
            'role' => $message['message_role'] ?? null,
            'message' => $message['message'] ?? '',
            'metadata' => $this->formatBson($message['metadata'] ?? []),
            'created_at' => $this->formatDateTime($message['created_at'] ?? null),
        ];
    }

    private function formatBson($value)
    {
        if ($value instanceof ObjectId) {
            return (string) $value;
        }

        if ($value instanceof UTCDateTime) {
            return $value->toDateTime()->format(\DateTime::ATOM);
        }

        if (is_array($value) || $value instanceof \ArrayObject) {
            $formatted = [];
            foreach ($value as $key => $item) {
                $formatted[$key] = $this->formatBson($item);
            }
            return $formatted;
        }

        return $value;
    }

    private function toObjectId($value): ?ObjectId
    {
        try {
            if ($value instanceof ObjectId) {
                return $value;
            }

            if (!$value) {
                return null;
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

        return null;
    }
}
