<?php

namespace App\Services;

use MongoDB\Client;

class MLFeatureService
{
    private $collection;

    public function __construct()
    {
        $client = new Client(config('database.connections.mongodb.dsn'));
        $this->collection = $client
            ->selectDatabase(config('database.connections.mongodb.database'))
            ->selectCollection('questions');
    }

    public function transform(array $answers)
    {
        $questions = $this->collection->find(
            [],
            ['sort' => ['ml_index' => 1]]
        )->toArray();

        $features = [];

        foreach ($questions as $q) {
            $field = $q['field_key'];
            $valueMap = $q['value_map'];

            $answer = $answers[$field] ?? null;

            if (!$answer || !isset($valueMap[$answer])) {
                $features[] = $valueMap['Tidak'] ?? 1;
            } else {
                $features[] = (int) $valueMap[$answer];
            }
        }

        return $features;
    }
}