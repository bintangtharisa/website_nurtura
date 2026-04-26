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
}
