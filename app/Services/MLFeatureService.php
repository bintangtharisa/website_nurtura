<?php

namespace App\Services;

use MongoDB\Client;

class MLFeatureService
{
    private function collection()
    {
        $client = new Client(config('database.connections.mongodb.dsn'));
        $db = $client->selectDatabase(config('database.connections.mongodb.database'));

        return $db->questions;
    }

    public function transform(array $answers)
    {
        $questions = $this->collection()->find()->toArray();

        $features = [];

        foreach ($questions as $q) {

            $fieldKey = $q['field_key'];
            $mlIndex  = $q['ml_index'];
            $valueMap = $q['value_map'];

            $answer = $answers[$fieldKey] ?? null;

            if (!$answer || !isset($valueMap[$answer])) {
                throw new \Exception("Jawaban tidak valid untuk: $fieldKey");
            }

            $features[$mlIndex] = $valueMap[$answer];
        }

        ksort($features);

        return array_values($features);
    }
}