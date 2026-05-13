<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;

class ArticleController extends Controller
{
    private function getApiUser()
    {
        return auth('api')->user();
    }

    public function index(Request $request)
    {
        $user = $this->getApiUser();
        $query = Article::with(['category', 'author']);

        if (!$user || $user->role !== 'admin') {
            $query->where('status', 'published');
        } elseif ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('category_id')) {
            $query->where('category_id', new ObjectId($request->category_id));
        }

        return response()->json($query->get());
    }

    public function all(Request $request)
    {
        $user = $this->getApiUser();
        $query = Article::with(['category', 'author']);

        if (!$user || $user->role !== 'admin') {
            $query->where('status', 'published');
        }

        if ($request->has('category_id')) {
            $query->where('category_id', new ObjectId($request->category_id));
        }

        return response()->json($query->get());
    }

    public function category(ArticleCategory $category, Request $request)
    {
        $user = $this->getApiUser();
        $query = Article::with(['category', 'author'])
            ->where('category_id', $category->_id);

        if (!$user || $user->role !== 'admin') {
            $query->where('status', 'published');
        } elseif ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:200',
            'slug' => 'required|string',
            'description' => 'required|string|min:20',
            'thumbnail' => 'required|string',
            'category_id' => 'required|string|size:24',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
            'status' => 'required|in:draft,published'
        ]);

        // Cek slug duplikat
        if (Article::where('slug', $validated['slug'])->exists()) {
            return response()->json(['message' => 'Slug already exists'], 422);
        }

        // Cek kategori ada
        $category = ArticleCategory::find(new ObjectId($validated['category_id']));
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 422);
        }

        // Prepare data - HANYA field yang divalidasi, TIDAK ada extra field
        $now = now();
        
        $articleData = [
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'description' => $validated['description'],
            'thumbnail' => $validated['thumbnail'],
            'category_id' => new ObjectId($validated['category_id']),
            'author_id' => new ObjectId(auth()->id()),
            'tags' => $validated['tags'] ?? [],
            'status' => $validated['status'],
            'views_count' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // Hanya sertakan published_at jika status adalah published
        if ($validated['status'] === 'published') {
            $articleData['published_at'] = $now;
        }

        try {
            // Gunakan create() dengan data yang sudah disiapkan
            // MongoDB-Laravel akan handle ObjectId fields otomatis
            $article = Article::create($articleData);

            return response()->json(
                $article->load(['category', 'author']),
                201
            );
        } catch (\Exception $e) {
            \Log::error('Article creation error: ' . $e->getMessage(), [
                'exception' => $e,
                'data' => $articleData
            ]);
            
            return response()->json([
                'message' => 'Failed to create article',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function show(Article $article)
    {
        $user = $this->getApiUser();
        if ((!$user || $user->role !== 'admin') && $article->status !== 'published') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($article->load(['category', 'author']));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:200',
            'slug' => 'required|string',
            'description' => 'required|string|min:20',
            'thumbnail' => 'required|string',
            'category_id' => 'required|string|size:24',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
            'status' => 'required|in:draft,published'
        ]);

        // Cek slug duplikat (exclude dokumen saat ini)
        if ($validated['slug'] !== $article->slug && Article::where('slug', $validated['slug'])->exists()) {
            return response()->json(['message' => 'Slug already exists'], 422);
        }

        // Cek kategori ada
        $category = ArticleCategory::find(new ObjectId($validated['category_id']));
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 422);
        }

        $now = now();

        $updateData = [
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'description' => $validated['description'],
            'thumbnail' => $validated['thumbnail'],
            'category_id' => new ObjectId($validated['category_id']),
            'tags' => $validated['tags'] ?? [],
            'status' => $validated['status'],
            'updated_at' => $now
        ];

        // Handle published_at based on status
        if ($validated['status'] === 'published') {
            // Jika status published dan belum ada published_at, set sekarang
            if (!$article->published_at) {
                $updateData['published_at'] = $now;
            }
            // Jika sudah ada published_at, biarkan saja (tidak diubah)
        } else {
            // Jika status bukan published, hapus published_at dari update data
            // (biarkan field existing dihapus dengan unset jika perlu)
            // Untuk sekarang, kita biarkan field existing tetap ada untuk backward compatibility
        }

        try {
            $article->update($updateData);

            return response()->json(
                $article->load(['category', 'author'])
            );
        } catch (\Exception $e) {
            \Log::error('Article update error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Failed to update article',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function destroy(Article $article)
    {
        try {
            $article->delete();
            return response()->json(['message' => 'Article deleted']);
        } catch (\Exception $e) {
            \Log::error('Article delete error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Failed to delete article',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}