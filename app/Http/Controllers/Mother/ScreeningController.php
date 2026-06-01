<?php

namespace App\Http\Controllers\Mother;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Screening;
use App\Services\MLFeatureService;
use App\Services\NotificationService;
use App\Services\FcmService;
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

    public function screening(Request $request, MLFeatureService $mlService, NotificationService $notificationService, FcmService $fcmService, ScreeningValidatorService $validator)
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

            $mlApiUrl = rtrim(config('services.ml_api.url'), '/');

            $response = \Http::post($mlApiUrl . '/predict', [
                'features' => $features,
                'answers' => $answers,
                'mother_id' => (string) $motherObjectId
            ]);

            if (!$response->ok()) {
                throw new \Exception('ML Error (' . $response->status() . '): ' . $response->body());
            }

            $mlResult = $response->json();

            $result = $mlResult['result'] ?? null;

            if (!$result) {
                throw new \Exception('ML API tidak mengembalikan field result');
            }

            $recommendation = $this->recommendationForResponse($mlResult, $answers, $features, $result);
            $mlResult['recommendation'] = $recommendation;

            Screening::create([
                'mother_id' => (string) $motherObjectId,
                'anonymous_id' => strtoupper($user->anonymous_id ?? ''),
                'result' => strtolower($result),
                'prediction' => $mlResult
            ]);

            try {
                $notificationService->createNotification(
                    (string) $motherObjectId,
                    'mother',
                    'Screening Selesai',
                    'Screening Anda telah selesai.',
                    'screening',
                    ['result' => $result]
                );

                $this->notifyConnectedFather(
                    $motherObjectId,
                    $result,
                    $notificationService,
                    $fcmService
                );
            } catch (\Throwable $notificationError) {
                \Log::warning('Screening notification failed after screening was saved', [
                    'mother_id' => (string) $motherObjectId,
                    'error' => $notificationError->getMessage(),
                ]);
            }

            $responseData = [
                'result' => $result,
                'features' => $features,
                'prediction' => $mlResult,
                'recommendation' => $recommendation,
            ];

            return response()->json([
                'status' => true,
                'message' => 'Screening berhasil',
                'data' => $responseData,
                'result' => $responseData['result'],
                'features' => $responseData['features'],
                'prediction' => $responseData['prediction'],
                'recommendation' => $responseData['recommendation'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal screening',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function recommendationForResponse(array $mlResult, array $answers, array $features, string $result): array
    {
        $recommendation = $mlResult['recommendation'] ?? null;

        if (is_array($recommendation) && ($recommendation['source'] ?? null) !== 'local') {
            return $recommendation;
        }

        return $this->buildLaravelRecommendation($answers, $features, $result);
    }

    private function buildLaravelRecommendation(array $answers, array $features, string $result): array
    {
        $isRisky = $this->isRiskyResult($result);
        $suicideSignal = $this->answerHasValue($answers['percobaan_bunuh_diri'] ?? null);
        $totalScore = array_sum(array_map('intval', $features));
        $priority = $suicideSignal ? 'darurat' : ($isRisky ? 'tinggi' : ($totalScore >= 10 ? 'sedang' : 'rendah'));

        $focusAreas = $this->focusAreas($answers);
        $summary = $this->summaryForPriority($priority);
        $actionSteps = $this->actionStepsForPriority($priority, $focusAreas);

        return [
            'source' => 'laravel',
            'priority' => $priority,
            'summary' => $summary,
            'focus_areas' => $focusAreas,
            'action_steps' => $actionSteps,
            'partner_support' => [
                'Minta pasangan atau keluarga membantu tugas rumah dan pengasuhan bayi agar ibu punya waktu istirahat.',
                'Sampaikan kondisi ibu dengan kalimat sederhana, misalnya bagian yang paling berat hari ini dan bantuan yang dibutuhkan.',
                'Ajak pasangan memantau perubahan tidur, nafsu makan, emosi, dan rasa cemas selama beberapa hari ke depan.',
            ],
            'professional_help' => $priority === 'rendah'
                ? 'Tetap lakukan skrining berkala. Jika keluhan muncul atau menetap, konsultasikan dengan bidan, dokter, psikolog, atau puskesmas.'
                : 'Jadwalkan konsultasi dengan bidan, dokter, psikolog, puskesmas, atau rumah sakit agar ibu mendapat evaluasi dan dukungan yang tepat.',
            'emergency_note' => $suicideSignal
                ? 'Karena ada sinyal keselamatan diri, jangan biarkan ibu sendirian dan segera cari bantuan darurat atau tenaga kesehatan terdekat.'
                : null,
            'disclaimer' => 'Rekomendasi ini bersifat pendamping skrining, bukan diagnosis medis.',
        ];
    }

    private function isRiskyResult(string $result): bool
    {
        $normalized = strtolower($result);

        return !str_contains($normalized, 'tidak') &&
            (str_contains($normalized, 'berisiko') || str_contains($normalized, 'beresiko'));
    }

    private function answerHasValue($answer): bool
    {
        $normalized = strtolower(trim((string) $answer));

        return $normalized !== '' && !in_array($normalized, ['no', 'not at all', 'tidak'], true);
    }

    private function focusAreas(array $answers): array
    {
        $labels = [
            'perasaan_sedih_atau_mudah_menangis' => 'Perasaan sedih atau mudah menangis',
            'merasa_cemas' => 'Rasa cemas',
            'kesulitan_tidur_di_malam_hari' => 'Kesulitan tidur',
            'kesulitan_konsentrasi_atau_mengambil_keputusan' => 'Kelelahan dan konsentrasi',
            'kesulitan_membangun_ikatan_dengan_bayi' => 'Ikatan dengan bayi',
            'mudah_marah_terhadap_bayi_dan_pasangan' => 'Dukungan dan emosi terhadap sekitar',
            'makan_berlebihan_atau_kehilangan_nafsu_makan' => 'Nafsu makan',
            'perasaan_bersalah' => 'Perasaan bersalah atau tidak mampu',
            'percobaan_bunuh_diri' => 'Keselamatan diri',
        ];

        $focus = [];

        foreach ($labels as $field => $label) {
            $answer = $answers[$field] ?? null;
            if ($this->answerHasValue($answer)) {
                $focus[] = [
                    'field' => $field,
                    'label' => $label,
                    'answer' => (string) $answer,
                ];
            }
        }

        return array_slice($focus, 0, 4);
    }

    private function summaryForPriority(string $priority): string
    {
        return match ($priority) {
            'darurat' => 'Hasil skrining menunjukkan tanda yang perlu ditangani segera, terutama terkait keselamatan diri.',
            'tinggi' => 'Hasil skrining menunjukkan risiko depresi postpartum sehingga ibu perlu dukungan aktif dan konsultasi profesional.',
            'sedang' => 'Beberapa keluhan cukup terasa. Ibu perlu memantau kondisi, menambah dukungan, dan mengurangi beban harian.',
            default => 'Hasil skrining saat ini belum menunjukkan risiko depresi. Tetap jaga istirahat, komunikasi, dan skrining berkala.',
        };
    }

    private function actionStepsForPriority(string $priority, array $focusAreas): array
    {
        $steps = [];

        if ($priority === 'darurat') {
            $steps[] = 'Minta pendampingan dari pasangan, keluarga, atau orang terpercaya sekarang.';
            $steps[] = 'Hubungi tenaga kesehatan, fasilitas kesehatan terdekat, atau layanan darurat bila ibu merasa tidak aman.';
        }

        if ($priority === 'tinggi') {
            $steps[] = 'Buat janji konsultasi dengan bidan, dokter, psikolog, puskesmas, atau rumah sakit.';
            $steps[] = 'Kurangi beban pekerjaan rumah dan atur jadwal istirahat harian bersama pasangan atau keluarga.';
        }

        if ($priority === 'sedang') {
            $steps[] = 'Catat keluhan utama selama 3-7 hari, termasuk tidur, makan, kecemasan, dan suasana hati.';
            $steps[] = 'Pilih satu bantuan konkret dari pasangan atau keluarga, misalnya menjaga bayi saat ibu beristirahat.';
        }

        if ($priority === 'rendah') {
            $steps[] = 'Pertahankan rutinitas istirahat, makan, dan komunikasi dengan pasangan atau keluarga.';
            $steps[] = 'Ulangi skrining berkala atau lebih cepat jika muncul keluhan baru.';
        }

        foreach ($focusAreas as $area) {
            $steps[] = 'Perhatikan area "' . $area['label'] . '" karena jawaban ibu adalah "' . $area['answer'] . '".';
        }

        return $steps;
    }

    private function notifyConnectedFather(
        ObjectId $motherObjectId,
        string $result,
        NotificationService $notificationService,
        FcmService $fcmService
    ): void {
        $db = $this->db();
        $relationship = $db->selectCollection('relationships')->findOne([
            'mother_id' => $motherObjectId,
            'status' => 'active',
        ], [
            'sort' => ['connected_at' => -1, 'created_at' => -1],
        ]);

        if (!$relationship || empty($relationship['father_id'])) {
            return;
        }

        $fatherId = $relationship['father_id'];
        $fatherFilters = [['_id' => $fatherId, 'role' => 'father']];

        try {
            $fatherIdString = (string) $fatherId;
            if (preg_match('/^[0-9a-fA-F]{24}$/', $fatherIdString)) {
                $fatherFilters[] = ['_id' => new ObjectId($fatherIdString), 'role' => 'father'];
            }
        } catch (\Throwable $e) {
        }

        $father = $db->selectCollection('users')->findOne([
            '$or' => $fatherFilters,
        ]);

        if (!$father) {
            return;
        }

        $isRisky = !str_contains(strtolower($result), 'tidak') &&
            (str_contains(strtolower($result), 'berisiko') ||
             str_contains(strtolower($result), 'beresiko'));

        $allChanges = $father['father_notif_all_changes'] ?? true;
        $riskOnly = $father['father_notif_risk_only'] ?? false;

        if (!$allChanges && !($riskOnly && $isRisky)) {
            return;
        }

        $title = $isRisky
            ? 'Kondisi Istri Berisiko Depresi'
            : 'Kondisi Istri Terpantau Stabil';
        $message = $isRisky
            ? 'Hasil skrining terbaru membutuhkan perhatian dan dukungan Anda.'
            : 'Hasil skrining terbaru menunjukkan kondisi ibu stabil.';
        $type = $isRisky ? 'peringatan' : 'stabil';

        $notificationService->createNotification(
            (string) $father['_id'],
            'father',
            $title,
            $message,
            $type,
            [
                'mother_id' => (string) $motherObjectId,
                'result' => $result,
            ]
        );

        if (!empty($father['fcm_token'])) {
            $fcmService->sendToToken(
                (string) $father['fcm_token'],
                $title,
                $message,
                [
                    'type' => $type,
                    'result' => $result,
                    'mother_id' => (string) $motherObjectId,
                ]
            );
        }
    }
}
