<?php

namespace App\Http\Controllers\Mother;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

    public function screening(
        Request $request,
        MLFeatureService $mlService,
        NotificationService $notificationService,
        ScreeningValidatorService $validator
    ) {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            $db = $this->db();
            $usersCollection = $db->selectCollection('users');

            $userExists = $usersCollection->findOne([
                '_id' => new ObjectId((string) $user->_id)
            ]);

            if (!$userExists) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            if ($userExists['role'] !== 'mother') {
                return response()->json([
                    'status' => false,
                    'message' => 'Hanya mother yang bisa screening'
                ], 403);
            }

            $answers = $request->all();

            if (empty($answers)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Jawaban kosong'
                ], 422);
            }

            // VALIDASI
            $validator->validate($answers);

            // FEATURES
            $features = $mlService->transform($answers);

            // ML CALL
            $response = Http::post(
                config('services.ml.url', 'https://web-production-f1df64.up.railway.app') . '/predict',
                ['features' => $features]
            );

            if (!$response->ok()) {
                throw new \Exception('ML Error: ' . $response->body());
            }

            $mlResult = $response->json();

            $result = $mlResult['result'] == 'Beresiko'
                ? 'Beresiko Depresi'
                : 'Tidak Beresiko Depresi';

            // FIXED: pakai ID user login saja (INI KUNCI UTAMA)
            $motherId = new ObjectId((string) $user->_id);

            // SAVE HEALTH RECORD
            $healthRecordsCollection = $db->selectCollection('health_records');

            $healthInsert = $healthRecordsCollection->insertOne([
                'mother_id' => $motherId,

                'perasaan_sedih_atau_mudah_menangis' => $answers['perasaan_sedih_atau_mudah_menangis'],
                'mudah_marah_terhadap_bayi_dan_pasangan' => $answers['mudah_marah_terhadap_bayi_dan_pasangan'],
                'kesulitan_tidur_di_malam_hari' => $answers['kesulitan_tidur_di_malam_hari'],
                'kesulitan_konsentrasi_atau_mengambil_keputusan' => $answers['kesulitan_konsentrasi_atau_mengambil_keputusan'],
                'makan_berlebihan_atau_kehilangan_nafsu_makan' => $answers['makan_berlebihan_atau_kehilangan_nafsu_makan'],
                'perasaan_bersalah' => $answers['perasaan_bersalah'],
                'kesulitan_membangun_ikatan_dengan_bayi' => $answers['kesulitan_membangun_ikatan_dengan_bayi'],
                'merasa_cemas' => $answers['merasa_cemas'],
                'percobaan_bunuh_diri' => $answers['percobaan_bunuh_diri'],

                'created_at' => new UTCDateTime()
            ]);

            $healthRecordId = $healthInsert->getInsertedId();

            // SAVE PREDICTION
            $predictionCollection = $db->selectCollection('prediction_results');

            $predictionCollection->insertOne([
                'mother_id' => $motherId,
                'health_record_id' => $healthRecordId,
                'cluster' => (int) ($mlResult['cluster'] ?? 0),
                'result' => $result,

                'recommendation' => [
                    'source' => 'AI System',
                    'priority' => $result === 'Beresiko Depresi' ? 'tinggi' : 'rendah',
                    'summary' => $result === 'Beresiko Depresi'
                        ? 'Terdapat indikasi depresi pasca melahirkan.'
                        : 'Tidak ditemukan indikasi depresi pasca melahirkan.',
                    'focus_areas' => [],
                    'action_steps' => [
                        'Jaga pola tidur',
                        'Komunikasi dengan pasangan',
                        'Pantau kondisi emosional'
                    ],
                    'partner_support' => [
                        'Berikan dukungan emosional',
                        'Bantu pekerjaan rumah'
                    ],
                    'professional_help' => $result === 'Beresiko Depresi'
                        ? 'Disarankan konsultasi dengan psikolog atau tenaga medis.'
                        : '',
                    'emergency_note' => '',
                    'disclaimer' => 'Hasil ini bukan diagnosis medis resmi.'
                ],

                'created_at' => new UTCDateTime()
            ]);

            // NOTIFIKASI
            $notificationService->createNotification(
                (string) $motherId,
                'mother',
                'Screening Selesai',
                'Screening Anda telah selesai.',
                'screening',
                ['result' => $result]
            );

            return response()->json([
                'status' => true,
                'message' => 'Screening berhasil',
                'result' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal screening',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =========================
    // HISTORY FIXED
    // =========================
   public function screeningHistory(Request $request)
{
    try {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $db = $this->db();
        $collection = $db->selectCollection('prediction_results');

        // ✅ WAJIB ObjectId (sesuai saat insert)
        $motherId = new ObjectId((string) $user->_id);

        $data = $collection->find(
            ['mother_id' => $motherId],
            ['sort' => ['created_at' => -1]]
        )->toArray();

        // ✅ FORMAT UNTUK FLUTTER
        $formatted = array_map(function ($item) {

            $isRisk = ($item['result'] ?? '') === 'Beresiko Depresi';

            return [
                'result' => $item['result'] ?? '',
                'risk_category' => $isRisk ? 'tinggi' : 'rendah',
                'created_at' => isset($item['created_at'])
                    ? $item['created_at']->toDateTime()->format('Y-m-d H:i:s')
                    : null,
            ];
        }, $data);

        return response()->json([
            'status' => true,
            'data' => $formatted
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Gagal mengambil history',
            'error' => $e->getMessage()
        ], 500);
    }
}
}