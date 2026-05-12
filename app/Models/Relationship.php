<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Relationship extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'relationships';
    public $timestamps = false;

    protected $fillable = [
        'mother_id',
        'father_id',
        'status',
        'connected_at',
        'disconnected_at',
        'disconnected_by',
        'reconnect_count',
        'last_access_by_father',
        'created_at',
        'updated_at'
    ];
}
