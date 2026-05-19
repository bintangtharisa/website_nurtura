<?php

namespace App\Http\Controllers\Father;

use App\Http\Controllers\Controller;
use App\Models\User;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Regex;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;
use DateTime;

class MonitoringController extends Controller
{
    private function db()
    {
        $client = new Client(config('database.connections.mongodb.dsn'));
        return $client->selectDatabase(config('database.connections.mongodb.database'));
    }

    public function index()
    {
        $db = $this->db();
        $collection = $db->selectCollection('prediction_results');

        $totalScreenings = $collection->countDocuments([]);
        $highRiskCount = $collection->countDocuments([
            'result' => new Regex('^(ya|beresiko depresi)$', 'i'),
        ]);
        $lowRiskCount = $collection->countDocuments([
            'result' => new Regex('^tidak$', 'i'),
        ]);

        $cursor = $collection->find([], [
            'sort' => ['created_at' => -1],
            'limit' => 200,
        ]);

        $screenings = [];
        foreach ($cursor as $item) {
            $createdAt = null;
            if (isset($item['created_at']) && $item['created_at'] instanceof UTCDateTime) {
                $createdAt = $item['created_at']->toDateTime()->format(DateTime::ATOM);
            } elseif (isset($item['_id']) && method_exists($item['_id'], 'getTimestamp')) {
                $createdAt = $item['_id']->getTimestamp()->format(DateTime::ATOM);
            }

            $motherName = $this->resolveMotherUsername($item);
            $riskCategory = $this->normalizeRiskCategory($item['result'] ?? null);

            $screenings[] = [
                'mother_username' => $motherName,
                'created_at' => $createdAt,
                'result' => isset($item['result']) ? (string) $item['result'] : null,
                'risk_category' => $riskCategory,
            ];
        }

        return view('father.monitoring', compact('totalScreenings', 'highRiskCount', 'lowRiskCount', 'screenings'));
    }

    private function resolveMotherUsername($item)
    {
        if (!empty($item['mother_id'])) {
            try {
                $motherObjectId = $item['mother_id'] instanceof ObjectId
                    ? $item['mother_id']
                    : new ObjectId((string) $item['mother_id']);

                $mother = User::where('_id', $motherObjectId)
                    ->where('role', 'mother')
                    ->first();

                if ($mother && !empty($mother->username)) {
                    return $mother->username;
                }
            } catch (\Exception $e) {
                // ignore and fallback to default
            }
        }

        if (!empty($item['mother_username'])) {
            return (string) $item['mother_username'];
        }

        return 'Tidak tersedia';
    }

    private function normalizeRiskCategory($result)
    {
        $result = strtolower(trim((string) $result));
        if (str_contains($result, 'tidak')) {
            return 'Rendah';
        }
        if (str_contains($result, 'ya') || str_contains($result, 'depresi')) {
            return 'Tinggi';
        }
        return 'Tidak Diketahui';
    }
}
