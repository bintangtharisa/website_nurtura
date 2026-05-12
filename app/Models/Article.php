<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $collection = 'articles';

    /*
    |--------------------------------------------------------------------------
    | Primary Key - MongoDB gunakan _id
    |--------------------------------------------------------------------------
    */

    protected $primaryKey = '_id';
    public $incrementing = false;
    protected $keyType = 'string';

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment - SEMUA field yang boleh diubah
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail',
        'category_id',
        'author_id',
        'tags',
        'status',
        'views_count',
        'published_at',
        'created_at',
        'updated_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    | Hanya cast field yang perlu transformasi
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'tags' => 'array',
        'views_count' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Timestamps
    |--------------------------------------------------------------------------
    | Disable Laravel automatic timestamps - handle manual di controller
    |--------------------------------------------------------------------------
    */

    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(
            ArticleCategory::class,
            'category_id',
            '_id'
        );
    }

    public function author()
    {
        return $this->belongsTo(
            User::class,
            'author_id',
            '_id'
        );
    }
}