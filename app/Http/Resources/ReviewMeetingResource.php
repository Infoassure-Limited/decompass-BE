<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewMeetingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'vendor_id' => $this->vendor_id,
            'vendor' => $this->whenLoaded('vendor', function () {
                return [
                    'id' => $this->vendor->id,
                    'name' => $this->vendor->name,
                    'email' => $this->vendor->email,
                ];
            }),
            'title' => $this->title,
            'agenda' => $this->agenda,
            'scheduled_at' => $this->scheduled_at,
            'ended_at' => $this->ended_at,
            'duration_minutes' => $this->duration_minutes,
            'location' => $this->location,
            'meeting_link' => $this->meeting_link,
            'status' => $this->status,
            'scheduled_by' => $this->scheduled_by,
            'created_by' => $this->created_by,
            'creator' => $this->whenLoaded('creator', function () {
                return [
                    'id' => $this->creator->id,
                    'name' => $this->creator->name,
                ];
            }),
            'notes' => $this->notes,
            'attendees' => MeetingAttendeeResource::collection($this->whenLoaded('attendees')),
            'action_items' => MeetingActionItemResource::collection($this->whenLoaded('actionItems')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
