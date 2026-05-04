<?php

namespace App\Http\Controllers\Mother;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MLFeatureService;
use Illuminate\Support\Facades\Http;
use MongoDB\Client;
use MongoDB\BSON\UTCDateTime;

class ScreeningController extends Controller
{
    private function screeningCollection()
    {
        $client = new Client(config('database.connections.mongodb.dsn'));
        $db = $client->selectDatabase(config('database.connections.mongodb.database'));

        return $db->screenings;
    }

    public function screening(Request $request, MLFeatureService $mlService)
    {
        try {
            $answers = $request->json()->all();

            if (empty($answers)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Jawaban kosong'
                ], 422);
            }

            $features = $mlService->transform($answers);

            $mlResponse = Http::timeout(5)->post('http://127.0.0.1:5000/predict', [
                'features' => $features
            ]);

            if (!$mlResponse->ok()) {
                return response()->json([
                    'status' => false,
                    'message' => 'ML service tidak merespon'
                ], 500);
            }

            $mlResult = $mlResponse->json();

            if (!isset($mlResult['prediction'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Response ML tidak valid'
                ], 500);
            }

            $this->screeningCollection()->insertOne([
                'user_id' => auth()->id(),
                'answers' => $answers,
                'features' => $features,
                'prediction' => $mlResult['prediction'],
                'confidence' => $mlResult['confidence'] ?? null,
                'created_at' => new UTCDateTime()
            ]);

            return response()->json([
                'status' => true,
                'prediction' => $mlResult['prediction'],
                'confidence' => $mlResult['confidence'] ?? null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal proses screening',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}