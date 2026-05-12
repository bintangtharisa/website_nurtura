<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;

class ArticleCategoryController extends Controller
{
    public function index()
    {
        $categories = ArticleCategory::where('is_active', true)->get();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:50',
            'slug' => 'required|string|regex:/^[a-z0-9-]+$/',
            'description' => 'nullable|string|max:300',
            'icon' => 'nullable|string',
            'color' => 'nullable|string|regex:/^#([A-Fa-f0-9]{6})$/',
            'is_active' => 'required|boolean',
        ]);

        // Check if slug already exists
        if (ArticleCategory::where('slug', $validated['slug'])->exists()) {
            return response()->json(['message' => 'Slug already exists'], 422);
        }

        $category = ArticleCategory::create($validated);

        return response()->json($category, 201);
    }

    public function show(ArticleCategory $category)
    {
        return response()->json($category);
    }

    public function update(Request $request, ArticleCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:50',
            'slug' => 'required|string|regex:/^[a-z0-9-]+$/',
            'description' => 'nullable|string|max:300',
            'icon' => 'nullable|string',
            'color' => 'nullable|string|regex:/^#([A-Fa-f0-9]{6})$/',
            'is_active' => 'required|boolean',
        ]);

        // Check if slug already exists (excluding current category)
        if ($validated['slug'] !== $category->slug && ArticleCategory::where('slug', $validated['slug'])->exists()) {
            return response()->json(['message' => 'Slug already exists'], 422);
        }

        $category->update($validated);

        return response()->json($category);
    }

    public function destroy(ArticleCategory $category)
    {
        $category->delete();
        return response()->json(['message' => 'Category deleted']);
    }
}