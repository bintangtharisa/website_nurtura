<?php

namespace App\Http\Controllers\Father;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;

class DashboardController extends Controller
{
    private function db()
    {
        $client = new Client(config('database.connections.mongodb.dsn'));
        return $client->selectDatabase(config('database.connections.mongodb.database'));
    }

    public function dashboard(Request $request)
    {
        $father = $request->user();
        $db = $this->db();
        $mother = $this->connectedMother($db, $father->_id);

        if (!$mother) {
            return response()->json([
                'status' => true,
                'user' => $this->formatFather($father),
                'is_connected' => false,
                'mother' => null,
                'statusRisiko' => 'Belum Ada Data',
                'persentaseRisiko' => 0,
                'latest_result' => null,
                'chart' => $this->emptyTodayChart(),
                'history' => [],
            ]);
        }

        $predictionCollection = $db->selectCollection('prediction_results');
        $baseFilter = $this->predictionFilter($mother);
        $latestResult = $predictionCollection->findOne($baseFilter, [
            'sort' => ['created_at' => -1],
        ]);

        return response()->json([
            'status' => true,
            'user' => $this->formatFather($father),
            'is_connected' => true,
            'mother' => $mother,
            'statusRisiko' => $latestResult['result'] ?? 'Belum Ada Data',
            'persentaseRisiko' => $this->resultPercentage($latestResult['result'] ?? null),
            'latest_result' => $latestResult ? $this->formatPrediction($latestResult) : null,
            'chart' => $this->todayFrequencyChart($predictionCollection, $baseFilter),
            'history' => $this->latestHistory($predictionCollection, $baseFilter),
        ]);
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
            'connected_at' => $this->formatDateTime($relationship['connected_at'] ?? $relationship['created_at'] ?? null),
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

    private function todayFrequencyChart($collection, array $baseFilter): array
    {
        $start = now()->startOfDay();
        $end = now()->endOfDay();
        $labels = ['00-03', '03-06', '06-09', '09-12', '12-15', '15-18', '18-21', '21-24'];
        $values = array_fill(0, count($labels), 0);

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

            $hour = (int) $createdAt->format('H');
            $bucket = min(7, (int) floor($hour / 3));
            $values[$bucket]++;
        }

        return [
            'labels' => $labels,
            'data' => $values,
        ];
    }

    private function emptyTodayChart(): array
    {
        return [
            'labels' => ['00-03', '03-06', '06-09', '09-12', '12-15', '15-18', '18-21', '21-24'],
            'data' => [0, 0, 0, 0, 0, 0, 0, 0],
        ];
    }

    private function latestHistory($collection, array $baseFilter): array
    {
        $history = [];
        $cursor = $collection->find($baseFilter, [
            'sort' => ['created_at' => -1],
            'limit' => 3,
        ]);

        foreach ($cursor as $item) {
            $history[] = $this->formatPrediction($item);
        }

        return $history;
    }

    private function formatPrediction($item): array
    {
        $createdAt = $this->toDateTime($item['created_at'] ?? null);

        return [
            'result' => $item['result'] ?? 'Belum Ada Data',
            'level' => $item['result'] ?? 'Belum Ada Data',
            'time' => $createdAt ? $createdAt->format('H:i') : '-',
            'created_at' => $createdAt ? $createdAt->format(\DateTime::ATOM) : null,
        ];
    }

    private function resultPercentage($result): int
    {
        return $result === 'Beresiko Depresi' ? 85 : ($result === 'Tidak Beresiko Depresi' ? 25 : 0);
    }

    private function formatFather($father): array
    {
        return [
            'id' => (string) $father->_id,
            'name' => $father->username ?? $father->name ?? 'Bapak',
            'username' => $father->username ?? null,
            'email' => $father->email ?? null,
            'role' => $father->role ?? null,
        ];
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

    private function formatDateTime($value): ?string
    {
        $dateTime = $this->toDateTime($value);
        return $dateTime ? $dateTime->format(\DateTime::ATOM) : null;
    }
}
