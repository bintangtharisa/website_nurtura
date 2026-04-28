<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;
use Illuminate\Support\Facades\Log;

class QuestionsController extends Controller
{
    private function collection()
    {
        $uri = config('database.connections.mongodb.dsn');
        $database = config('database.connections.mongodb.database');
        $client = new Client($uri);
        return $client->selectCollection($database, 'questions');
    }

    private function isValidObjectId($id)
    {
        return (bool) preg_match('/^[a-f\d]{24}$/i', $id);
    }

    public function index()
    {
        try {
            $questions = $this->collection()->find([], ['sort' => ['order' => 1]])->toArray();

            $questions = array_map(function ($q) {
                $q['_id'] = (string) $q['_id'];
                $q['is_active'] = isset($q['is_active']) ? (bool) $q['is_active'] : false;
                return $q;
            }, $questions);

            return response()->json(['status' => true, 'data' => $questions]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function toggle($id)
    {
        try {
            if (!$this->isValidObjectId($id)) {
                return response()->json(['status' => false], 400);
            }

            $collection = $this->collection();
            $objectId = new ObjectId($id);
            $question = $collection->findOne(['_id' => $objectId]);

            if (!$question) {
                return response()->json(['status' => false], 404);
            }

            $newStatus = !(bool) ($question['is_active'] ?? false);

            $result = $collection->findOneAndUpdate(
                ['_id' => $objectId],
                [
                    '$set' => [
                        'is_active' => $newStatus,
                        'updated_at' => new UTCDateTime()
                    ]
                ],
                [
                    'returnDocument' => \MongoDB\Operation\FindOneAndUpdate::RETURN_DOCUMENT_AFTER
                ]
            );

            return response()->json([
                'status' => true,
                'is_active' => (bool) $result['is_active']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if (!$this->isValidObjectId($id)) {
                return response()->json(['status' => false], 400);
            }

            $data = $request->all();
            $updateData = [];

            if (isset($data['question_text'])) {
                $updateData['question_text'] = (string) $data['question_text'];
            }

            if (array_key_exists('category', $data)) {
                $updateData['category'] = $data['category'] !== '' ? (string) $data['category'] : null;
            }

            if (isset($data['options']) && is_array($data['options'])) {
                $updateData['options'] = array_map('strval', $data['options']);
            }

            if (isset($data['order'])) {
                $updateData['order'] = (int) $data['order'];
            }

            $updateData['updated_at'] = new UTCDateTime();

            $this->collection()->updateOne(
                ['_id' => new ObjectId($id)],
                ['$set' => $updateData]
            );

            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function reorder(Request $request)
    {
        try {
            $collection = $this->collection();

            foreach ($request->all() as $item) {
                if (isset($item['id'], $item['order']) && $this->isValidObjectId($item['id'])) {
                    $collection->updateOne(
                        ['_id' => new ObjectId($item['id'])],
                        [
                            '$set' => [
                                'order' => (int) $item['order'],
                                'updated_at' => new UTCDateTime()
                            ]
                        ]
                    );
                }
            }

            return response()->json(['status' => true, 'message' => 'Reorder berhasil']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
        }
    }
}