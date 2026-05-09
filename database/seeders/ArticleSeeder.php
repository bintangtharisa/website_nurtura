<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ArticleCategory;
use App\Models\Article;
use App\Models\User;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        // Create sample categories
        $categories = [
            [
                'name' => 'Parenting Tips',
                'slug' => 'parenting-tips',
                'description' => 'Tips and advice for parents',
                'icon' => '👨‍👩‍👧‍👦',
                'color' => '#FF6B6B',
                'is_active' => true,
            ],
            [
                'name' => 'Child Development',
                'slug' => 'child-development',
                'description' => 'Articles about child growth and development',
                'icon' => '🌱',
                'color' => '#4ECDC4',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            ArticleCategory::create($category);
        }

        // Get admin user (assuming there's an admin user)
        $admin = User::where('role', 'admin')->first();

        if ($admin) {
            // Create sample articles
            $articles = [
                [
                    'title' => 'Understanding Your Child\'s Emotional Development',
                    'slug' => 'understanding-child-emotional-development',
                    'description' => 'Learn about the key stages of emotional development in children and how to support them.',
                    'thumbnail' => 'https://example.com/thumbnail1.jpg',
                    'category_id' => ArticleCategory::where('slug', 'child-development')->first()->_id,
                    'author_id' => $admin->_id,
                    'tags' => ['emotions', 'development', 'parenting'],
                    'status' => 'published',
                ],
                [
                    'title' => 'Daily Routines for Better Family Life',
                    'slug' => 'daily-routines-family-life',
                    'description' => 'Establishing healthy daily routines can improve family harmony and child well-being.',
                    'thumbnail' => 'https://example.com/thumbnail2.jpg',
                    'category_id' => ArticleCategory::where('slug', 'parenting-tips')->first()->_id,
                    'author_id' => $admin->_id,
                    'tags' => ['routines', 'family', 'wellness'],
                    'status' => 'published',
                ],
            ];

            foreach ($articles as $article) {
                Article::create($article);
            }
        }
    }
}