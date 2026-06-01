<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = [
        'username',
        'email',
        'password_hash',
        'role',
        'linked_mother_id',
        'anonymous_id',
        'fcm_token',
        'fcm_platform',
        'father_notif_risk_only',
        'father_notif_all_changes',
        'notification_enabled',
        'photo',
        'created_at',
        'updated_at',
        'last_login'
    ];

    protected $hidden = [
        'password_hash'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}
