<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    public function index(){
        $client = new \MongoDB\Client(env('MONGODB_URI'));
        $collection = $client->selectCollection(env('MONGODB_DATABASE'), 'questions');
        
        $questions = $collection -> find(
            [
                'is_active' => true
             ],
             [
                'sort' => ['order' => 1]
            ]
        )-> toArray();

        $questions = array_map(function ($q) {
            $q['_id'] = (string) $q['_id'];
            return $q;
        }, $questions);
        return $questions;

        return response()->json([
            'status' => 'success',
            'data' => $questions
        ]);
    }

    public function toggle($id){
        $collection = $this->collection();
        $question = $collection->findOne([
            '_id' => new ObjectId($id)
        ]);

        if (!$question) {
            return response()->json([
                'status' => 'error',
                'message' => 'Question not found'
            ], 404);
        }

        $collection->updateOne(
            ['_id' => new ObjectId($id)],
            [
                '$set' => [
                    'is_active' => !$question['is_active'],
                    'updated_at' => new \MongoDB\BSON\UTCDateTime()
                ]
            ]
        );
        return response()->json([
            'status' => true,
            'message' => 'Question updated successfully'
        ]);
    }

    public function update(Request $request, $id){
        $collection = $this->collection();

        $request0->validate([
            'question_text' => 'required|string',
            'category' => 'required|string',
        ]);

        $collection->updateOne(
            ['_id' => new ObjectId($id)],
            [
                '$set' => [
                    'question_text' => $request->question_text,
                    'category' => $request->category,
                    'updated_at' => new \MongoDB\BSON\UTCDateTime()
                ]
            ]
        );
        return response()->json([
            'status' => true,
            'message' => 'Question updated successfully'
        ]);
    }

    public function reorder(Request $request){
        $collection = $this->collection();

        foreach ($request->all() as $item) {
            $collection->updateOne(
                ['_id' => new ObjectId($item['id'])],
                [
                    '$set' => [
                        'order' => (int) $item['order'],
                        'updated_at' => new \MongoDB\BSON\UTCDateTime()
                    ]
                ]
            );
        }
        return response()->json([
            'status' => true,
            'message' => 'Questions reordered successfully'
        ]);
    }
}
