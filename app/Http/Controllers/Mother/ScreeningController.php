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
            $answers = $request->all();

            if (empty($answers)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Jawaban kosong'
                ], 422);
            }

            $features = $mlService->transform($answers);

            $response = \Http::post('http://127.0.0.1:5000/predict', [
                'features' => $features
            ]);

            if (!$response->ok()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal koneksi ke ML'
                ], 500);
            }

            $result = $response->json();

            return response()->json([
                'status' => true,
                'features' => $features,
                'prediction' => $result
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