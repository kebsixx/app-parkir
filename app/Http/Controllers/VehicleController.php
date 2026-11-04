<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));

        $vehicles = Vehicle::query()
            ->with('type')
            ->when($search !== '', function ($query) use ($search) {
                $plate = strtoupper($search);

                $query->where(function ($query) use ($plate, $search) {
                    $query->where('plate_number', 'like', "%{$plate}%")
                        ->orWhereRaw('LOWER(owner_name) LIKE ?', ['%'.strtolower($search).'%']);
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('vehicles.index', compact('vehicles', 'search'));
    }

    public function create(): View
    {
        $vehicleTypes = VehicleType::orderBy('name')->get();

        return view('vehicles.create', compact('vehicleTypes'));
    }

    public function store(StoreVehicleRequest $request): RedirectResponse
    {
        Vehicle::create($request->validated());

        return redirect()->route('vehicles.index')
            ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function show(Vehicle $vehicle): View
    {
        $vehicle->load(['type', 'parkingSessions' => fn ($query) => $query->with('payment')->latest('check_in_at')]);

        return view('vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle): View
    {
        $vehicleTypes = VehicleType::orderBy('name')->get();

        return view('vehicles.edit', compact('vehicle', 'vehicleTypes'));
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): RedirectResponse
    {
        $vehicle->update($request->validated());

        return redirect()->route('vehicles.index')
            ->with('success', 'Kendaraan berhasil diperbarui.');
    }

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        if ($vehicle->parkingSessions()->exists()) {
            return back()->with('error', 'Kendaraan punya riwayat parkir, tidak bisa dihapus.');
        }

        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Kendaraan berhasil dihapus.');
    }
}
