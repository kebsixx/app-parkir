<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckInRequest;
use App\Http\Requests\CheckoutRequest;
use App\Models\ParkingSession;
use App\Models\Vehicle;
use App\Services\ParkingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParkingSessionController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));

        $sessions = ParkingSession::query()
            ->with(['vehicle.type'])
            ->where('status', ParkingSession::STATUS_PARKED)
            ->when($search !== '', function ($query) use ($search) {
                $plate = strtoupper($search);

                $query->where(function ($query) use ($plate, $search) {
                    $query->where('ticket_code', 'like', "%{$plate}%")
                        ->orWhereHas('vehicle', function ($query) use ($plate, $search) {
                            $query->where('plate_number', 'like', "%{$plate}%")
                                ->orWhereRaw('LOWER(owner_name) LIKE ?', ['%'.strtolower($search).'%']);
                        });
                });
            })
            ->oldest('check_in_at')
            ->paginate(10)
            ->withQueryString();

        return view('parking.active', compact('sessions', 'search'));
    }

    public function create(): View
    {
        $vehicles = Vehicle::query()
            ->with('type')
            ->orderBy('plate_number')
            ->get();

        return view('parking.check-in', compact('vehicles'));
    }

    public function store(CheckInRequest $request, ParkingService $service): RedirectResponse
    {
        $vehicle = Vehicle::findOrFail($request->integer('vehicle_id'));
        $session = $service->checkIn($vehicle, $request->user());

        return redirect()->route('parking.success', $session)
            ->with('success', 'Kendaraan berhasil dicatat masuk.');
    }

    public function checkout(ParkingSession $session): View|RedirectResponse
    {
        if (! $session->isParked()) {
            return redirect()->route('parking.active')
                ->with('error', 'Sesi ini sudah selesai.');
        }

        $session->load('vehicle.type');

        return view('parking.checkout', compact('session'));
    }

    public function processCheckout(CheckoutRequest $request, ParkingSession $session, ParkingService $service): RedirectResponse
    {
        $payment = $service->checkout($session, $request->user(), $request->integer('paid_amount'));

        return redirect()->route('parking.success', $payment->parking_session_id)
            ->with('success', 'Pembayaran berhasil. Kendaraan boleh keluar.');
    }

    public function success(ParkingSession $session): View
    {
        $session->load(['vehicle.type', 'payment.receivedBy']);

        return view('parking.success', compact('session'));
    }
}
