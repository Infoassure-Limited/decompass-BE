<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicableObjectiveResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'client_id'=>$this->client_id,
            'weight' => (float)$this->weight,
            'metadata' => $this->metadata,
            'monitorings' => PerformanceMonitoringResource::collection($this->whenLoaded('monitorings')),
        ];
    }
}
