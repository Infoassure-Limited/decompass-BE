<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PerformanceMonitoringResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'info_need' => new InfoNeedResource($this->whenLoaded('infoNeed')),
            'measure' => new MeasureResource($this->whenLoaded('measure')),
            'client_id' => $this->client_id,
            'settings' => $this->settings,
        ];
    }
}