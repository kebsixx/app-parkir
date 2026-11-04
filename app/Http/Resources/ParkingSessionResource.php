<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParkingSessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ticket_code' => $this->ticket_code,
            'status' => $this->status,
            'hourly_rate' => $this->hourly_rate,
            'check_in_at' => $this->check_in_at?->toIso8601String(),
            'check_out_at' => $this->check_out_at?->toIso8601String(),
            'duration_minutes' => $this->duration_minutes,
            'total_fee' => $this->total_fee,
            'vehicle' => new VehicleResource($this->whenLoaded('vehicle')),
            'payment' => new PaymentResource($this->whenLoaded('payment')),
            'check_in_by' => $this->whenLoaded('checkInBy', fn () => $this->checkInBy?->name),
            'check_out_by' => $this->whenLoaded('checkOutBy', fn () => $this->checkOutBy?->name),
        ];
    }
}
