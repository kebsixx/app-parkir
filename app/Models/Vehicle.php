<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_type_id',
        'plate_number',
        'owner_name',
        'brand',
        'color',
    ];

    public function setPlateNumberAttribute(string $value): void
    {
        $this->attributes['plate_number'] = strtoupper(preg_replace('/\s+/', ' ', trim($value)));
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    public function parkingSessions(): HasMany
    {
        return $this->hasMany(ParkingSession::class);
    }

    public function activeSession(): ?ParkingSession
    {
        return $this->parkingSessions()->where('status', ParkingSession::STATUS_PARKED)->first();
    }
}
