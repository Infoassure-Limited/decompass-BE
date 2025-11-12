<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'module' => $this->module,
            'priority' => $this->priority,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'completed_at' => $this->completed_at,
            'comments' => $this->comments,
            'status' => $this->status
        ];
    }

    /**
     * Get the additional data that should be returned with the resource.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'assignee' => new UserResource($this->whenLoaded('assignee')),
            'assigner' => new UserResource($this->whenLoaded('assigner')),
        ];
    }
}