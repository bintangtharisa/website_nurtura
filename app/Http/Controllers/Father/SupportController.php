<?php

namespace App\Http\Controllers\Father;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $article = Article::with('category')
            ->where(function ($query) use ($slug) {
                $query->where('slug', $slug)
                      ->orWhere('_id', $slug);
            })
            ->first();

        if (! $article) {
            abort(404);
        }

        return view('father.article-detail', [
            'article' => $article,
        ]);
    }
}
