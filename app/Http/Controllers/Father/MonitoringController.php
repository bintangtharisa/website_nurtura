<?php

namespace App\Http\Controllers\Father;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Regex;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;
use Carbon\Carbon;

class MonitoringController extends Controller
{
    private function db()
    {
        $client = new Client(config('database.connections.mongodb.dsn'));
        return $client->selectDatabase(config('database.connections.mongodb.database'));
    }

    public function history(Request $request)
    {
        $father = $request->user();
        $db = $this->db();

        $connectedMothers = $this->getConnectedMothers($db, $father->_id);

        if ($connectedMothers->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Ayah belum terhubung dengan akun ibu atau hubungan tidak aktif.',
                'is_connected' => false,
                'mothers' => [],
                'mother' => null,
                'data' => [],
            ], 404);
        }

        $requestedAnonymousId = $request->filled('anonymous_id')
            ? strtoupper(trim((string) $request->anonymous_id))
            : null;
        $chartPeriod = $request->input('chart_period') === 'bulanan' ? 'bulanan' : 'mingguan';

        $selectedMother = $requestedAnonymousId
            ? $connectedMothers->firstWhere('anonymous_id', $requestedAnonymousId)
            : $connectedMothers->first();

        if (!$selectedMother) {
            return response()->json([
                'status' => false,
                'message' => 'Kode koneksi ibu tidak ditemukan pada relasi ayah ini.',
                'is_connected' => true,
                'mothers' => $connectedMothers->values(),
                'mother' => null,
                'data' => [],
            ], 403);
        }

        $baseFilter = $this->buildPredictionFilter($selectedMother);
        $filter = $baseFilter;

        if ($request->filled('since_days') && is_numeric($request->since_days) && (int) $request->since_days > 0) {
            $filter['created_at'] = [
                '$gte' => new UTCDateTime(now()->subDays((int) $request->since_days)->timestamp * 1000),
            ];
        }

        $dateFilter = $filter['created_at'] ?? [];

        if ($request->filled('start_date')) {
            $startDate = Carbon::parse((string) $request->start_date)->startOfDay();
            $dateFilter['$gte'] = new UTCDateTime($startDate->timestamp * 1000);
        }

        if ($request->filled('end_date')) {
            $endDate = Carbon::parse((string) $request->end_date)->endOfDay();
            $dateFilter['$lte'] = new UTCDateTime($endDate->timestamp * 1000);
        }

        if (!empty($dateFilter)) {
            $filter['created_at'] = $dateFilter;
        }

        if ($request->filled('result')) {
            $filter['result'] = new Regex('^' . preg_quote((string) $request->result, '/') . '$', 'i');
        }

        $findOptions = [
            'sort' => ['created_at' => -1],
        ];

        if ($request->filled('limit') && in_array((int) $request->limit, [10, 20, 30], true)) {
            $findOptions['limit'] = (int) $request->limit;
        }

        $predictionCollection = $db->selectCollection('prediction_results');
        $latestItem = $predictionCollection->findOne($baseFilter, [
            'sort' => ['created_at' => -1],
        ]);

        $cursor = $predictionCollection->find($filter, $findOptions);

        $data = [];
        foreach ($cursor as $item) {
            $data[] = $this->formatPredictionResult($item, $selectedMother);
        }

        return response()->json([
            'status' => true,
            'message' => 'Riwayat skrining istri berhasil diambil.',
            'is_connected' => true,
            'mothers' => $connectedMothers->values(),
            'mother' => $selectedMother,
            'latest_result' => $latestItem ? $this->formatPredictionResult($latestItem, $selectedMother) : null,
            'chart' => $this->buildScreeningFrequencyChart($predictionCollection, $baseFilter, $chartPeriod),
            'data' => $data,
        ]);
    }

    private function buildScreeningFrequencyChart($collection, array $baseFilter, string $period): array
    {
        if ($period === 'bulanan') {
            return $this->buildMonthlyFrequencyChart($collection, $baseFilter);
        }

        return $this->buildWeeklyFrequencyChart($collection, $baseFilter);
    }

    private function buildWeeklyFrequencyChart($collection, array $baseFilter): array
    {
        $start = now()->subDays(6)->startOfDay();
        $end = now()->endOfDay();
        $labels = [];
        $values = [];
        $buckets = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $start->copy()->addDays($i);
            $key = $date->format('Y-m-d');
            $labels[] = $date->locale('id')->translatedFormat('d M');
            $values[] = 0;
            $buckets[$key] = $i;
        }

        $filter = $baseFilter;
        $filter['created_at'] = [
            '$gte' => new UTCDateTime($start->timestamp * 1000),
            '$lte' => new UTCDateTime($end->timestamp * 1000),
        ];

        foreach ($collection->find($filter) as $item) {
            $createdAt = $this->toDateTime($item['created_at'] ?? null);
            if (!$createdAt) {
                continue;
            }

            $key = $createdAt->format('Y-m-d');
            if (isset($buckets[$key])) {
                $values[$buckets[$key]]++;
            }
        }

        return [
            'period' => 'mingguan',
            'labels' => $labels,
            'values' => $values,
        ];
    }

    private function buildMonthlyFrequencyChart($collection, array $baseFilter): array
    {
        $start = now()->subDays(29)->startOfDay();
        $end = now()->endOfDay();
        $labels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4', 'Minggu 5'];
        $values = [0, 0, 0, 0, 0];

        $filter = $baseFilter;
        $filter['created_at'] = [
            '$gte' => new UTCDateTime($start->timestamp * 1000),
            '$lte' => new UTCDateTime($end->timestamp * 1000),
        ];

        foreach ($collection->find($filter) as $item) {
            $createdAt = $this->toDateTime($item['created_at'] ?? null);
            if (!$createdAt) {
                continue;
            }

            $daysFromStart = $start->diffInDays($createdAt);
            $bucket = min(4, (int) floor($daysFromStart / 7));
            $values[$bucket]++;
        }

        return [
            'period' => 'bulanan',
            'labels' => $labels,
            'values' => $values,
        ];
    }

    private function getConnectedMothers($db, $fatherId)
    {
        $relationshipCollection = $db->selectCollection('relationships');
        $userCollection = $db->selectCollection('users');

        $fatherObjectId = $this->toObjectId($fatherId);
        $fatherFilters = [['father_id' => (string) $fatherId]];
        if ($fatherObjectId) {
            $fatherFilters[] = ['father_id' => $fatherObjectId];
        }

        $relationships = $relationshipCollection->find([
            'status' => 'active',
            '$or' => $fatherFilters,
        ]);

        $mothers = collect();

        foreach ($relationships as $relationship) {
            if (empty($relationship['mother_id'])) {
                continue;
            }

            $motherId = $relationship['mother_id'];
            $motherObjectId = $this->toObjectId($motherId);
            $motherFilters = [['role' => 'mother', '_id' => (string) $motherId]];
            if ($motherObjectId) {
                $motherFilters[] = ['role' => 'mother', '_id' => $motherObjectId];
            }

            $mother = $userCollection->findOne(['$or' => $motherFilters]);
            if (!$mother || empty($mother['anonymous_id'])) {
                continue;
            }

            $anonymousId = strtoupper((string) $mother['anonymous_id']);
            $mothers->put($anonymousId, [
                'id' => (string) ($mother['_id'] ?? $motherId),
                'anonymous_id' => $anonymousId,
                'username' => $mother['username'] ?? null,
            ]);
        }

        return $mothers->values();
    }

    private function buildPredictionFilter(array $mother)
    {
        $motherObjectId = $this->toObjectId($mother['id']);
        $orFilters = [
            ['mother_id' => (string) $mother['id']],
            ['anonymous_id' => $mother['anonymous_id']],
        ];

        if ($motherObjectId) {
            $orFilters[] = ['mother_id' => $motherObjectId];
        }

        return ['$or' => $orFilters];
    }

    private function toObjectId($value): ?ObjectId
    {
        try {
            if ($value instanceof ObjectId) {
                return $value;
            }

            $stringValue = (string) $value;
            return preg_match('/^[a-f\d]{24}$/i', $stringValue) ? new ObjectId($stringValue) : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function toDateTime($value): ?\DateTimeInterface
    {
        if ($value instanceof UTCDateTime) {
            return $value->toDateTime();
        }

        if ($value instanceof \DateTimeInterface) {
            return $value;
        }

        return null;
    }

    private function formatPredictionResult($item, array $mother)
    {
        $createdAt = null;
        if (isset($item['created_at']) && $item['created_at'] instanceof UTCDateTime) {
            $createdAt = $item['created_at']->toDateTime()->format(\DateTime::ATOM);
        } elseif (isset($item['created_at']) && $item['created_at'] instanceof \DateTimeInterface) {
            $createdAt = $item['created_at']->format(\DateTime::ATOM);
        } elseif (isset($item['_id']) && method_exists($item['_id'], 'getTimestamp')) {
            $createdAt = $item['_id']->getTimestamp()->format(\DateTime::ATOM);
        }

        $result = isset($item['result']) ? (string) $item['result'] : null;

        return [
            'id' => isset($item['_id']) ? (string) $item['_id'] : null,
            'mother_id' => $mother['id'],
            'anonymous_id' => $mother['anonymous_id'],
            'mother_username' => $mother['username'],
            'result' => $result,
            'prediction' => $item['prediction'] ?? null,
            'created_at' => $createdAt,
        ];
    }
}
