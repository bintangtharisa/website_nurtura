<?php

namespace App\Http\Controllers\Mother;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MLFeatureService;
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

    public function screening(Request $request, MLFeatureService $mlService)
    {
        try {
            $user = auth()->user();
            $answers = $request->all();

            if (empty($answers)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Jawaban kosong'
                ], 422);
            }

            $healthCollection = $this->db()->health_records;

            $healthData = [
                'mother_id' => new ObjectId($user->_id),
                'created_at' => new UTCDateTime()
            ];

            foreach ($answers as $key => $value) {
                $healthData[$key] = $value;
            }

            $insertHealth = $healthCollection->insertOne($healthData);
            $healthId = $insertHealth->getInsertedId();

            $features = $mlService->transform($answers);

            $response = Http::post('http://127.0.0.1:5000/predict', [
                'features' => $features
            ]);

            if (!$response->ok()) {
                throw new \Exception('Gagal konek ke ML service');
            }

            $mlResult = $response->json();

            $predictionCollection = $this->db()->prediction_results;

            $predictionCollection->insertOne([
                'mother_id' => new ObjectId($user->_id),
                'health_record_id' => $healthId,
                'result' => $mlResult['result'],
                'confidence' => (float) $mlResult['confidence'],
                'created_at' => new UTCDateTime()
            ]);

            return response()->json([
                'status' => true,
                'result' => $mlResult['result'],
                'confidence' => $mlResult['confidence']
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