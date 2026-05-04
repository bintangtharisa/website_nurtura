<?php

namespace App\Http\Controllers\Mother;

use App\Http\Controllers\Controller;
use MongoDB\Client;

class QuestionsController extends Controller
{
    private function collection()
    {
        $client = new Client(config('database.connections.mongodb.dsn'));
        $db = $client->selectDatabase(config('database.connections.mongodb.database'));

        return $db->questions;
    }

    public function getQuestions()
    {
        try {
            $collection = $this->collection();

            $questions = $collection->find(
                [],
                ['sort' => ['order' => 1]]
            )->toArray();

            $result = array_map(function ($q) {
                return [
                    'field_key' => $q['field_key'],
                    'question_text' => $q['question_text'],
                    'options' => $q['options'],
                    'category' => $q['category'] ?? null
                ];
            }, $questions);

            return response()->json([
                'status' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal ambil pertanyaan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}