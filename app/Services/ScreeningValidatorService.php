<?php

namespace App\Services;

use MongoDB\Client;

class ScreeningValidatorService
{
    private $collection;

    public function __construct()
    {
        $client = new Client(config('database.connections.mongodb.dsn'));
        $db = $client->selectDatabase(config('database.connections.mongodb.database'));
        $this->collection = $db->selectCollection('questions');
    }

    public function validate(array $answers)
    {
        $questions = $this->collection->find()->toArray();

        foreach ($questions as $q) {
            $field = $q['field_key'];

            if (!isset($answers[$field])) {
                throw new \Exception("Field $field wajib diisi");
            }

            $options = iterator_to_array($q['options']); // 🔥 FIX BSONArray

            if (!in_array($answers[$field], $options)) {
                throw new \Exception("Jawaban tidak valid untuk $field");
            }
        }
    }
}