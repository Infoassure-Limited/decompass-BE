<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeasureResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'info_need' => new InfoNeedResource($this->whenLoaded('infoNeed')),
            'title' => $this->title,
            'measurement_need' => $this->measurement_need,
            'metadata' => $this->metadata,
        ];
    }
}
