<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncidentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'incident_no' => $this->incident_no,
            'title' => $this->title,
            'description' => $this->description,
            'incident_type' => $this->whenLoaded('incidentType', function () {
                return new IncidentTypeResource($this->incidentType);
            }),
            'incident_type_id' => $this->incident_type_id,
            'reported_by' => $this->reported_by,
            'reporter' => $this->whenLoaded('reporter', function () {
                return [
                    'id' => $this->reporter->id,
                    'name' => $this->reporter->name,
                ];
            }),
            'assigned_to' => $this->assigned_to,
            'assignee' => $this->whenLoaded('assignee', function () {
                return $this->assignee ? [
                    'id' => $this->assignee->id,
                    'name' => $this->assignee->name,
                ] : null;
            }),
            'status' => $this->status,
            'severity' => $this->severity,
            'occurred_at' => $this->occurred_at,
            'closure_date' => $this->closure_date,
            'location' => $this->location,
            'evidence_link' => $this->evidence_link,
            'affected_assets' => $this->affected_assets,
            'reviewer_comment' => $this->reviewer_comment,
            'reviewer_id' => $this->reviewer_id,
            'reviewer' => $this->whenLoaded('reviewer', function () {
                return $this->reviewer ? [
                    'id' => $this->reviewer->id,
                    'name' => $this->reviewer->name,
                ] : null;
            }),
            'reviewed_at' => $this->reviewed_at,
            'review_status' => $this->review_status,

            // 'evidences' => $this->whenLoaded('evidences', function ($q) {
            //     return AttachmentResource::collection($this->attachments);
            // }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
