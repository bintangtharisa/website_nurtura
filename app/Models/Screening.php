<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Screening extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'screenings';

    protected $fillable = [
        'mother_id',
        'anonymous_id',
        'result',
        'prediction',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'mother_id'
    ];

    protected $casts = [
        'prediction' => 'array'
    ];
}
