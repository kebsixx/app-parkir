<?php

namespace Tests\Feature;

use App\Models\ParkingSession;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParkingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_and_logout(): void
    {
        $user = User::factory()->create([
            'email' => 'petugas@parkirku.test',
            'password' => 'password',
            'role' => 'petugas',
        ]);

        $response = $this->post('/login', [
            'email' => 'petugas@parkirku.test',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);

        $logout = $this->post('/logout');
        $logout->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_petugas_cannot_access_admin_vehicle_types(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas']);

        $response = $this->actingAs($petugas)->get('/vehicle-types');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_vehicle_types(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/vehicle-types');
        $response->assertStatus(200);
    }

    public function test_parking_check_in_and_checkout_flow(): void
    {
        $user = User::factory()->create(['role' => 'petugas']);
        $type = VehicleType::factory()->create(['name' => 'Mobil', 'hourly_rate' => 3000]);
        $vehicle = Vehicle::factory()->create([
            'vehicle_type_id' => $type->id,
            'plate_number' => 'B 9999 TEST',
        ]);

        // Check-in
        $this->actingAs($user)->post('/parking/check-in', [
            'vehicle_id' => $vehicle->id,
        ])->assertRedirect();

        $session = ParkingSession::where('vehicle_id', $vehicle->id)->firstOrFail();
        $this->assertEquals(ParkingSession::STATUS_PARKED, $session->status);
        $this->assertEquals(3000, $session->hourly_rate);

        // Checkout
        $this->actingAs($user)->post("/parking/{$session->id}/checkout", [
            'paid_amount' => 10000,
        ])->assertRedirect();

        $session->refresh();
        $this->assertEquals(ParkingSession::STATUS_COMPLETED, $session->status);
        $this->assertNotNull($session->payment);
        $this->assertEquals(10000, $session->payment->paid_amount);
    }

    public function test_api_stats_returns_json(): void
    {
        $response = $this->getJson('/api/stats');
        $response->assertStatus(200)
            ->assertJsonStructure(['parked_count', 'today_transactions', 'today_revenue']);
    }
}
