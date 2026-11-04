<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckInRequest;
use App\Http\Requests\CheckoutRequest;
use App\Http\Resources\ParkingSessionResource;
use App\Models\ParkingSession;
use App\Models\Vehicle;
use App\Services\ParkingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ParkingSessionController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');
        $dateColumn = $status === ParkingSession::STATUS_COMPLETED ? 'check_out_at' : 'check_in_at';

        $query = ParkingSession::query()
            ->with(['vehicle.type', 'payment', 'checkInBy', 'checkOutBy'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($search !== '', function ($query) use ($search) {
                $needle = strtoupper($search);

                $query->where(function ($query) use ($needle, $search) {
                    $query->where('ticket_code', 'like', "%{$needle}%")
                        ->orWhereHas('vehicle', function ($query) use ($needle, $search) {
                            $query->where('plate_number', 'like', "%{$needle}%")
                                ->orWhereRaw('LOWER(owner_name) LIKE ?', ['%'.strtolower($search).'%']);
                        });
                });
            })
            ->when($dateFrom, fn ($query) => $query->whereDate($dateColumn, '>=', $dateFrom))
            ->when($dateTo, fn ($query) => $query->whereDate($dateColumn, '<=', $dateTo));

        $summary = [
            'total_transactions' => (clone $query)->where('status', ParkingSession::STATUS_COMPLETED)->count(),
            'total_revenue' => (int) (clone $query)->where('status', ParkingSession::STATUS_COMPLETED)->sum('total_fee'),
        ];

        $sessions = $query->latest('check_in_at')
            ->paginate(min(100, max(1, $request->integer('per_page', 15))));

        return ParkingSessionResource::collection($sessions)->additional([
            'summary' => $summary,
        ]);
    }

    public function store(CheckInRequest $request, ParkingService $service): JsonResponse
    {
        $vehicle = Vehicle::findOrFail($request->integer('vehicle_id'));
        $session = $service->checkIn($vehicle, $request->user() ?? \App\Models\User::firstOrFail());

        return (new ParkingSessionResource($session->load('vehicle.type')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(ParkingSession $session): ParkingSessionResource
    {
        return new ParkingSessionResource(
            $session->load(['vehicle.type', 'payment', 'checkInBy', 'checkOutBy'])
        );
    }

    public function checkout(CheckoutRequest $request, ParkingSession $session, ParkingService $service): ParkingSessionResource
    {
        $service->checkout($session, $request->user() ?? \App\Models\User::firstOrFail(), $request->integer('paid_amount'));

        return new ParkingSessionResource(
            $session->fresh()->load(['vehicle.type', 'payment', 'checkInBy', 'checkOutBy'])
        );
    }
}
