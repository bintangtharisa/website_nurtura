<?php

namespace App\Http\Controllers\Mother;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MLFeatureService;
use App\Services\ScreeningValidatorService;
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
        ScreeningValidatorService $validator
    ) {
        try {
            $answers = $request->all();

            if (empty($answers)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Jawaban kosong'
                ], 422);
            }

            // ✅ VALIDASI ENUM & FIELD
            $validator->validate($answers);

            // ✅ CONVERT KE ML
            $features = $mlService->transform($answers);

            // ✅ HIT FLASK
            $response = \Http::post('http://127.0.0.1:5000/predict', [
                'features' => $features
            ]);

            if (!$response->ok()) {
                throw new \Exception('ML tidak merespon');
            }

            $mlResult = $response->json();

            // =========================
            // ✅ INSERT HEALTH RECORD
            // =========================
            $db = $this->db();
            $healthCollection = $db->selectCollection('health_records');

            $allowedFields = [
                'perasaan_sedih_atau_mudah_menangis',
                'mudah_marah_terhadap_bayi_dan_pasangan',
                'kesulitan_tidur_di_malam_hari',
                'kesulitan_konsentrasi_atau_mengambil_keputusan',
                'makan_berlebihan_atau_kehilangan_nafsu_makan',
                'perasaan_bersalah',
                'kesulitan_membangun_ikatan_dengan_bayi',
                'merasa_cemas'
            ];

            $healthData = [
                'mother_id' => new ObjectId($request->user()->id),
                'created_at' => new UTCDateTime(),
            ];

            foreach ($allowedFields as $field) {
                if (isset($answers[$field])) {
                    $healthData[$field] = $answers[$field];
                }
            }

            $healthInsert = $healthCollection->insertOne($healthData);
            $healthId = $healthInsert->getInsertedId();

            // =========================
            // ✅ INSERT PREDICTION
            // =========================
            $predictionCollection = $db->selectCollection('prediction_results');

            $result = $mlResult['result']; // "Ya" / "Tidak"
            $confidence = $mlResult['confidence'] ?? null;

            // normalize confidence (0–1)
            if ($confidence !== null && $confidence > 1) {
                $confidence = $confidence / 100;
            }

            if (!in_array($result, ['Ya', 'Tidak'])) {
                throw new \Exception("Format result ML tidak valid");
            }

            $predictionCollection->insertOne([
                'mother_id' => new ObjectId($request->user()->id),
                'health_record_id' => $healthId,
                'result' => $result,
                'confidence' => $confidence,
                'created_at' => new UTCDateTime()
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
}