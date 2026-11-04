<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ParkingSession;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'parked_count' => ParkingSession::where('status', ParkingSession::STATUS_PARKED)->count(),
            'today_transactions' => ParkingSession::where('status', ParkingSession::STATUS_COMPLETED)
                ->whereDate('check_out_at', today())
                ->count(),
            'today_revenue' => (int) Payment::whereDate('paid_at', today())->sum('amount'),
        ]);
    }
}
