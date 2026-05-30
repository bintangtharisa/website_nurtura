<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Regex;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;

class DashboardAdminController extends Controller
{
    public function dashboard()
    {
        try {

            $twoMonthsAgo = new UTCDateTime(
                now()->subMonths(2)->timestamp * 1000
            );

            $totalUser = User::whereIn('role', ['father', 'mother'])
                ->whereNotNull('last_login')
                ->where('last_login', '>=', $twoMonthsAgo)
                ->count();

            $totalPengguna = User::whereIn('role', ['father', 'mother'])->count();

            $client = new Client(config('database.connections.mongodb.dsn'));
            $db = $client->selectDatabase(config('database.connections.mongodb.database'));
            $collection = $db->selectCollection('prediction_results');
            $usersCollection = $db->selectCollection('users');

            $options = [
                'sort' => ['created_at' => -1],
                'limit' => 5,
            ];
            $cursor = $collection->find([], $options);

            $recentScreenings = [];
            foreach ($cursor as $item) {
                $recentScreenings[] = $this->formatPredictionResult($item);
            }

            return response()->json([
                'status' => true,
                'totalUser' => $totalUser,
                'totalPengguna' => $totalPengguna,
                'recentScreenings' => $recentScreenings,
                'predictionTrends' => $this->getPredictionTrends($collection, $usersCollection),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Dashboard error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getPredictionTrends($collection, $usersCollection): array
    {
        $year = (int) now()->year;
        $startOfYear = new UTCDateTime(now()->startOfYear()->timestamp * 1000);
        $endOfYear = new UTCDateTime(now()->endOfYear()->timestamp * 1000);

        $motherIds = [];
        $motherCursor = $usersCollection->find(
            ['role' => 'mother'],
            ['projection' => ['_id' => 1]]
        );

        foreach ($motherCursor as $mother) {
            if (!empty($mother['_id'])) {
                $motherIds[] = $mother['_id'];
            }
        }

        $quarters = [
            1 => ['label' => 'Q1', 'high' => 0, 'low' => 0, 'unknown' => 0, 'total' => 0],
            2 => ['label' => 'Q2', 'high' => 0, 'low' => 0, 'unknown' => 0, 'total' => 0],
            3 => ['label' => 'Q3', 'high' => 0, 'low' => 0, 'unknown' => 0, 'total' => 0],
            4 => ['label' => 'Q4', 'high' => 0, 'low' => 0, 'unknown' => 0, 'total' => 0],
        ];

        if (empty($motherIds)) {
            return [
                'year' => $year,
                'labels' => array_column($quarters, 'label'),
                'series' => $this->formatTrendSeries($quarters),
                'quarters' => array_values($quarters),
            ];
        }

        $cursor = $collection->find([
            'mother_id' => ['$in' => $motherIds],
            'created_at' => [
                '$gte' => $startOfYear,
                '$lte' => $endOfYear,
            ],
        ]);

        foreach ($cursor as $item) {
            $createdAt = null;
            if (isset($item['created_at']) && $item['created_at'] instanceof UTCDateTime) {
                $createdAt = $item['created_at']->toDateTime();
            } elseif (isset($item['_id']) && method_exists($item['_id'], 'getTimestamp')) {
                $timestamp = $item['_id']->getTimestamp();
                $createdAt = $timestamp instanceof \DateTimeInterface
                    ? $timestamp
                    : (new \DateTimeImmutable())->setTimestamp((int) $timestamp);
            }

            if (!$createdAt) {
                continue;
            }

            $month = (int) $createdAt->format('n');
            $quarter = (int) ceil($month / 3);
            $category = $this->trendCategory($item['result'] ?? null);

            $quarters[$quarter][$category]++;
            $quarters[$quarter]['total']++;
        }

        return [
            'year' => $year,
            'labels' => array_column($quarters, 'label'),
            'series' => $this->formatTrendSeries($quarters),
            'quarters' => array_values($quarters),
        ];
    }

    private function formatTrendSeries(array $quarters): array
    {
        return [
            'high' => array_column($quarters, 'high'),
            'low' => array_column($quarters, 'low'),
            'unknown' => array_column($quarters, 'unknown'),
            'total' => array_column($quarters, 'total'),
        ];
    }

    private function trendCategory($result): string
    {
        $normalizedResult = strtolower((string) $result);

        if (str_contains($normalizedResult, 'tidak')) {
            return 'low';
        }

        if (str_contains($normalizedResult, 'ya') || str_contains($normalizedResult, 'depresi')) {
            return 'high';
        }

        return 'unknown';
    }

    private function formatPredictionResult($item)
    {
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
            try {
                $motherObjectId = $item['mother_id'] instanceof ObjectId
                    ? $item['mother_id']
                    : new ObjectId((string) $item['mother_id']);

                $motherUser = User::where('_id', $motherObjectId)
                    ->where('role', 'mother')
                    ->first();

                if ($motherUser && !empty($motherUser->anonymous_id)) {
                    $anonymousId = strtoupper((string) $motherUser->anonymous_id);
                }
            } catch (\Exception $e) {
                // fallback to anonymized code if lookup fails
            }

            if ($anonymousId === null) {
                $anonymousId = 'ANON-' . strtoupper(substr(md5((string) $item['mother_id']), 0, 8));
            }
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

        return [
            'anonymous_id' => $anonymousId,
            'result' => $result,
            'risk_category' => $riskCategory,
            'prediction' => $item['prediction'] ?? null,
            'created_at' => $createdAt,
        ];
    }

    public function screenings(Request $request)
    {
        $client = new Client(config('database.connections.mongodb.dsn'));
        $db = $client->selectDatabase(config('database.connections.mongodb.database'));
        $collection = $db->selectCollection('prediction_results');

        $filter = [];

        if ($request->filled('anonymous_id')) {
            $filter['anonymous_id'] = strtoupper($request->anonymous_id);
        }

        if ($request->filled('result')) {
            $filter['result'] = new Regex('^' . preg_quote(strtolower($request->result), '/') . '$', 'i');
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
                try {
                    $motherObjectId = $item['mother_id'] instanceof ObjectId
                        ? $item['mother_id']
                        : new ObjectId((string) $item['mother_id']);

                    $motherUser = User::where('_id', $motherObjectId)
                        ->where('role', 'mother')
                        ->first();

                    if ($motherUser && !empty($motherUser->anonymous_id)) {
                        $anonymousId = strtoupper((string) $motherUser->anonymous_id);
                    }
                } catch (\Exception $e) {
                    // fallback to anonymized code if lookup fails
                }

                if ($anonymousId === null) {
                    $anonymousId = 'ANON-' . strtoupper(substr(md5((string) $item['mother_id']), 0, 8));
                }
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
    }
}
