<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use MongoDB\Client;
use MongoDB\BSON\Regex;

class RiwayatController extends Controller
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
            'result' => new Regex('^(ya|beresiko depresi)$', 'i')
        ]);
        $lowRiskCount = $collection->countDocuments([
            'result' => new Regex('^tidak$', 'i')
        ]);

        return view('admin.riwayat', compact('totalScreenings', 'highRiskCount', 'lowRiskCount'));
    }
}
