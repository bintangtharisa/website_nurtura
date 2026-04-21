<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->_id,
            'username' => $this->username,
            'email' => $this->email,
            'role' => $this->role,
            'notification_enabled' => $this->notification_enabled,
            'created_at' => $this->created_at,
        ];
    }
}