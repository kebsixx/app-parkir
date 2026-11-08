<?php

namespace App\Http\Controllers;

use App\Models\ParkingSession;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = $this->stats();
        $activeSessions = ParkingSession::query()
            ->with(['vehicle.type'])
            ->where('status', ParkingSession::STATUS_PARKED)
            ->latest('check_in_at')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'activeSessions'));
    }

    private function stats(): array
    {
        $fallback = [
            'parked_count' => 0,
            'today_transactions' => 0,
            'today_revenue' => 0,
        ];

        try {
            $response = Http::timeout(3)->get(rtrim(config('services.internal_api.base_url'), '/').'/api/stats');

            return $response->successful()
                ? array_merge($fallback, $response->json())
                : $fallback;
        } catch (ConnectionException) {
            return $fallback;
        }
    }
}
