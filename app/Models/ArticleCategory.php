<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Carbon\Carbon;

class ArticleCategory extends Model
{
    use HasFactory;

    protected $collection = 'article_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'articles_count',
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'articles_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->created_at ?? Carbon::now();
            $model->articles_count = $model->articles_count ?? 0;
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'category_id');
    }
}