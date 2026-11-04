<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'plate_number' => $this->plate_number,
            'owner_name' => $this->owner_name,
            'brand' => $this->brand,
            'color' => $this->color,
            'type' => new VehicleTypeResource($this->whenLoaded('type')),
        ];
    }
}
