<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use MongoDB\BSON\UTCDateTime;

class NotificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => (string) $this->_id,
            'user_id' => (string) $this->user_id,
            'target_role' => $this->target_role,
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'data' => $this->data,
            'is_read' => $this->is_read,
            'created_at' => $this->formatDate($this->created_at),
            'read_at' => $this->formatDate($this->read_at),
        ];
    }

    protected function formatDate($value)
    {
        if ($value instanceof UTCDateTime) {
            return $value->toDateTime()->format(DATE_ATOM);
        }

        return $value;
    }
}
