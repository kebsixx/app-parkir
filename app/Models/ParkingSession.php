<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class ParkingSession extends Model
{
    use HasFactory;

    public const STATUS_PARKED = 'parked';

    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'ticket_code',
        'vehicle_id',
        'check_in_by',
        'check_out_by',
        'hourly_rate',
        'check_in_at',
        'check_out_at',
        'duration_minutes',
        'total_fee',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'check_in_at' => 'datetime',
            'check_out_at' => 'datetime',
            'hourly_rate' => 'integer',
            'duration_minutes' => 'integer',
            'total_fee' => 'integer',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function checkInBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'check_in_by');
    }

    public function checkOutBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'check_out_by');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function isParked(): bool
    {
        return $this->status === self::STATUS_PARKED;
    }

    public function elapsedMinutes(): int
    {
        $end = $this->check_out_at ?? now();

        return max(1, (int) $this->check_in_at->diffInMinutes($end));
    }

    public function calculatedFee(?Carbon $at = null): int
    {
        $minutes = $at
            ? max(1, (int) $this->check_in_at->diffInMinutes($at))
            : $this->elapsedMinutes();

        return (int) ceil($minutes / 60) * $this->hourly_rate;
    }
}
