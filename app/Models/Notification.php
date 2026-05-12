<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Notification extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'notifications';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'target_role',
        'title',
        'message',
        'type',
        'data',
        'is_read',
        'created_at',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'bool'
    ];
}
