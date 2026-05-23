<?php

namespace App\Http\Controllers\Mother;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Screening;
use App\Services\MLFeatureService;
use App\Services\NotificationService;
use App\Services\ScreeningValidatorService;
use Illuminate\Support\Facades\Http;
use MongoDB\Client;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class ScreeningController extends Controller
{
    private function db()
    {
        $client = new Client(config('database.connections.mongodb.dsn'));
        return $client->selectDatabase(config('database.connections.mongodb.database'));
    }

    public function screening(Request $request, MLFeatureService $mlService, NotificationService $notificationService, ScreeningValidatorService $validator)
    {
        try {
            $user = $request->user();
            
            // Validasi user ter-autentikasi
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak ter-autentikasi'
                ], 401);
            }
            
            // Validasi user ada di database
            $db = $this->db();
            $usersCollection = $db->selectCollection('users');
            $userExists = $usersCollection->findOne(['_id' => new ObjectId((string) $user->_id)]);
            
            if (!$userExists) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak ditemukan dalam sistem'
                ], 404);
            }
            
            // Validasi user adalah mother
            if ($userExists['role'] !== 'mother') {
                return response()->json([
                    'status' => false,
                    'message' => 'Hanya mother yang bisa melakukan screening'
                ], 403);
            }
            
            $answers = $request->all();

            if (empty($answers)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Jawaban kosong'
                ], 422);
            }

            if (!isset($answers['mother_id'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Field mother_id wajib diisi pada payload'
                ], 422);
            }

            try {
                $motherObjectId = new ObjectId($answers['mother_id']);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Format mother_id tidak valid'
                ], 400);
            }

            // Mencegah user menggunakan mother_id milik orang lain (spoofing)
            if ((string) $motherObjectId !== (string) $user->_id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anda tidak bisa melakukan skrining untuk akun lain. mother_id tidak cocok dengan token.'
                ], 403);
            }

            $motherCheck = $usersCollection->findOne([
                '_id' => $motherObjectId,
                'role' => 'mother'
            ]);

            if (!$motherCheck) {
                return response()->json([
                    'status' => false,
                    'message' => 'mother_id tidak ditemukan pada database atau bukan sebagai mother'
                ], 404);
            }

            $validator->validate($answers);

            $features = $mlService->transform($answers);

            $response = \Http::post('http://127.0.0.1:5000/predict', [
                'features' => $features,
                'answers' => $answers,
                'mother_id' => (string) $motherObjectId
            ]);

            if (!$response->ok()) {
                throw new \Exception('ML Error (' . $response->status() . '): ' . $response->body());
            }

            $mlResult = $response->json();

            $result = $mlResult['result'];

            $notificationService->createNotification(
                (string) $motherObjectId,
                'mother',
                'Screening Selesai',
                'Screening Anda telah selesai.',
                'screening',
                ['result' => $result]
            );

            Screening::create([
                'mother_id' => (string) $motherObjectId,
                'anonymous_id' => strtoupper($user->anonymous_id ?? ''),
                'result' => strtolower($result),
                'prediction' => $mlResult
            ]);

            return response()->json([
                'status' => true,
                'features' => $features,
                'prediction' => $mlResult
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal screening',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function history(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak ter-autentikasi'
                ], 401);
            }

            $db = $this->db();
            $collection = $db->selectCollection('prediction_results');

            $filter = ['mother_id' => new ObjectId((string) $user->_id)];

            if ($request->filled('result')) {
                $filter['result'] = new \MongoDB\BSON\Regex('^' . preg_quote(strtolower($request->result), '/') . '$', 'i');
            }

            if ($request->filled('since_days') && is_numeric($request->since_days) && (int) $request->since_days > 0) {
                $threshold = new UTCDateTime(now()->subDays((int) $request->since_days)->timestamp * 1000);
                $filter['created_at'] = ['$gte' => $threshold];
            }

            $options = ['sort' => ['created_at' => -1]];
            $cursor = $collection->find($filter, $options);

            $data = [];
            foreach ($cursor as $item) {
                $createdAt = null;
                if (isset($item['created_at']) && $item['created_at'] instanceof \MongoDB\BSON\UTCDateTime) {
                    $createdAt = $item['created_at']->toDateTime()->format(\DateTime::ATOM);
                } elseif (isset($item['_id']) && method_exists($item['_id'], 'getTimestamp')) {
                    $createdAt = $item['_id']->getTimestamp()->format(\DateTime::ATOM);
                }

                $anonymousId = null;
                if (!empty($item['anonymous_id'])) {
                    $anonymousId = strtoupper((string) $item['anonymous_id']);
                } elseif (!empty($item['mother_id'])) {
                    $anonymousId = strtoupper($user->anonymous_id ?? 'ANON-' . strtoupper(substr(md5((string) $item['mother_id']), 0, 8)));
                } elseif (!empty($item['_id'])) {
                    $anonymousId = 'ANON-' . strtoupper(substr(md5((string) $item['_id']), 0, 8));
                }

                $result = isset($item['result']) ? (string) $item['result'] : null;
                $normalizedResult = strtolower($result);
                $riskCategory = 'Tidak Diketahui';
                if (str_contains($normalizedResult, 'tidak')) {
                    $riskCategory = 'Rendah';
                } elseif (str_contains($normalizedResult, 'ya') || str_contains($normalizedResult, 'depresi')) {
                    $riskCategory = 'Tinggi';
                }

                $data[] = [
                    'anonymous_id' => $anonymousId,
                    'result' => $result,
                    'risk_category' => $riskCategory,
                    'prediction' => $item['prediction'] ?? null,
                    'created_at' => $createdAt,
                ];
            }

            return response()->json([
                'status' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil riwayat screening',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}