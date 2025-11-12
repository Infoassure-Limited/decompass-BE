<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MeetingAttendeeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'review_meeting_id' => $this->review_meeting_id,
            'user_id' => $this->user_id,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ];
            }),
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'is_external' => $this->is_external,
            'confirmed' => $this->confirmed,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
