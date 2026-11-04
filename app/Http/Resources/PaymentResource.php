<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'method' => $this->method,
            'amount' => $this->amount,
            'paid_amount' => $this->paid_amount,
            'change_amount' => $this->change_amount,
            'paid_at' => $this->paid_at?->toIso8601String(),
            'received_by' => $this->whenLoaded('receivedBy', fn () => $this->receivedBy?->name),
        ];
    }
}
