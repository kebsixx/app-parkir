<?php

namespace App\Models;

use App\Models\ParkingSession;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hourly_rate',
    ];

    protected function casts(): array
    {
        return [
            'hourly_rate' => 'integer',
        ];
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function activeSessionCount(): int
    {
        return (int) $this->vehicles()
            ->whereHas('parkingSessions', fn ($query) => $query->where('status', ParkingSession::STATUS_PARKED))
            ->count();
    }
}
