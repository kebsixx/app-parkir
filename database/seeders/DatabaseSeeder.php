<?php

namespace Database\Seeders;

use App\Models\ParkingSession;
use App\Models\Payment;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@parkirku.test'],
            ['name' => 'Admin Parkir', 'password' => 'password', 'role' => 'admin'],
        );

        $petugas = User::updateOrCreate(
            ['email' => 'petugas@parkirku.test'],
            ['name' => 'Petugas Gerbang', 'password' => 'password', 'role' => 'petugas'],
        );

        $types = collect([
            ['name' => 'Motor', 'hourly_rate' => 2000],
            ['name' => 'Mobil', 'hourly_rate' => 3000],
            ['name' => 'Bus', 'hourly_rate' => 10000],
        ])->mapWithKeys(fn (array $type) => [
            $type['name'] => VehicleType::updateOrCreate(['name' => $type['name']], $type),
        ]);

        $vehicles = collect([
            ['B 1234 XYZ', 'Mobil', 'Budi Santoso', 'Toyota', 'Hitam'],
            ['B 5678 ABC', 'Mobil', 'Siti Aminah', 'Honda', 'Putih'],
            ['D 9012 DEF', 'Motor', 'Rizky Pratama', 'Yamaha', 'Merah'],
            ['F 3456 GHI', 'Motor', 'Dewi Lestari', 'Honda', 'Biru'],
            ['B 7890 JKL', 'Mobil', 'Agus Setiawan', 'Suzuki', 'Silver'],
            ['AB 1122 CD', 'Motor', 'Nur Aisyah', 'Yamaha', 'Hitam'],
            ['B 3344 EF', 'Mobil', 'Fajar Nugroho', 'Daihatsu', 'Putih'],
            ['D 5566 GH', 'Motor', 'Maya Sari', 'Honda', 'Merah'],
            ['B 7788 IJ', 'Bus', 'PT Trans Jaya', 'Hino', 'Kuning'],
            ['F 9900 KL', 'Mobil', 'Andi Wijaya', 'Toyota', 'Hitam'],
            ['B 2468 MN', 'Motor', 'Lina Marlina', 'Suzuki', 'Biru'],
            ['D 1357 OP', 'Mobil', 'Hendra Gunawan', 'Honda', 'Silver'],
        ])->map(function (array $row) use ($types, $petugas) {
            [$plate, $type, $owner, $brand, $color] = $row;

            return Vehicle::updateOrCreate(
                ['plate_number' => $plate],
                [
                    'vehicle_type_id' => $types[$type]->id,
                    'owner_name' => $owner,
                    'brand' => $brand,
                    'color' => $color,
                ],
            );
        });

        foreach ($vehicles->take(5) as $index => $vehicle) {
            $checkIn = now()->subMinutes(20 + ($index * 25));

            ParkingSession::updateOrCreate(
                ['ticket_code' => 'PKR-'.now()->format('Ymd').'-A'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'vehicle_id' => $vehicle->id,
                    'check_in_by' => $petugas->id,
                    'hourly_rate' => $vehicle->type->hourly_rate,
                    'check_in_at' => $checkIn,
                    'status' => ParkingSession::STATUS_PARKED,
                ],
            );
        }

        foreach ($vehicles->skip(5) as $index => $vehicle) {
            $checkIn = now()->subHours(3)->subMinutes($index * 7);
            $checkOut = now()->subMinutes(30 + $index * 5);
            $minutes = max(1, (int) $checkIn->diffInMinutes($checkOut));
            $fee = (int) ceil($minutes / 60) * $vehicle->type->hourly_rate;
            $paid = (int) (ceil($fee / 5000) * 5000);

            $session = ParkingSession::updateOrCreate(
                ['ticket_code' => 'PKR-'.now()->format('Ymd').'-B'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'vehicle_id' => $vehicle->id,
                    'check_in_by' => $petugas->id,
                    'check_out_by' => $admin->id,
                    'hourly_rate' => $vehicle->type->hourly_rate,
                    'check_in_at' => $checkIn,
                    'check_out_at' => $checkOut,
                    'duration_minutes' => $minutes,
                    'total_fee' => $fee,
                    'status' => ParkingSession::STATUS_COMPLETED,
                ],
            );

            Payment::updateOrCreate(
                ['parking_session_id' => $session->id],
                [
                    'received_by' => $petugas->id,
                    'method' => 'cash',
                    'amount' => $fee,
                    'paid_amount' => $paid,
                    'change_amount' => $paid - $fee,
                    'paid_at' => $checkOut,
                ],
            );
        }
    }
}
