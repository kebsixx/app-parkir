<?php

namespace App\Services;

use App\Models\ParkingSession;
use App\Models\Payment;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ParkingService
{
    public function checkIn(Vehicle $vehicle, User $user): ParkingSession
    {
        return DB::transaction(function () use ($vehicle, $user) {
            $vehicle = Vehicle::with('type')->lockForUpdate()->findOrFail($vehicle->id);

            $active = ParkingSession::query()
                ->where('vehicle_id', $vehicle->id)
                ->where('status', ParkingSession::STATUS_PARKED)
                ->lockForUpdate()
                ->first();

            if ($active) {
                throw ValidationException::withMessages([
                    'vehicle_id' => 'Kendaraan ini masih parkir. Selesaikan checkout terlebih dahulu.',
                ]);
            }

            return ParkingSession::create([
                'ticket_code' => $this->ticketCode(),
                'vehicle_id' => $vehicle->id,
                'check_in_by' => $user->id,
                'hourly_rate' => $vehicle->type->hourly_rate,
                'check_in_at' => now(),
                'status' => ParkingSession::STATUS_PARKED,
            ]);
        });
    }

    public function checkout(ParkingSession $session, User $user, int $paidAmount): Payment
    {
        return DB::transaction(function () use ($session, $user, $paidAmount) {
            $session = ParkingSession::query()
                ->with('payment')
                ->lockForUpdate()
                ->findOrFail($session->id);

            if (! $session->isParked()) {
                throw ValidationException::withMessages([
                    'paid_amount' => 'Sesi ini sudah selesai atau tidak bisa di-checkout.',
                ]);
            }

            if ($session->payment) {
                throw ValidationException::withMessages([
                    'paid_amount' => 'Pembayaran untuk sesi ini sudah tercatat.',
                ]);
            }

            $checkOutAt = now();
            $minutes = max(1, (int) $session->check_in_at->diffInMinutes($checkOutAt));
            $totalFee = (int) ceil($minutes / 60) * $session->hourly_rate;

            if ($paidAmount < $totalFee) {
                throw ValidationException::withMessages([
                    'paid_amount' => 'Uang diterima kurang dari total tagihan.',
                ]);
            }

            $session->update([
                'check_out_by' => $user->id,
                'check_out_at' => $checkOutAt,
                'duration_minutes' => $minutes,
                'total_fee' => $totalFee,
                'status' => ParkingSession::STATUS_COMPLETED,
            ]);

            return Payment::create([
                'parking_session_id' => $session->id,
                'received_by' => $user->id,
                'method' => 'cash',
                'amount' => $totalFee,
                'paid_amount' => $paidAmount,
                'change_amount' => $paidAmount - $totalFee,
                'paid_at' => $checkOutAt,
            ]);
        });
    }

    private function ticketCode(): string
    {
        do {
            $code = 'PKR-'.now()->format('Ymd').'-'.Str::upper(Str::random(4));
        } while (ParkingSession::where('ticket_code', $code)->exists());

        return $code;
    }
}
