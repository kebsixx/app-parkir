<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleTypeRequest;
use App\Http\Requests\UpdateVehicleTypeRequest;
use App\Models\VehicleType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VehicleTypeController extends Controller
{
    public function index(): View
    {
        $vehicleTypes = VehicleType::query()
            ->withCount('vehicles')
            ->orderBy('name')
            ->paginate(10);

        return view('vehicle-types.index', compact('vehicleTypes'));
    }

    public function create(): View
    {
        return view('vehicle-types.create');
    }

    public function store(StoreVehicleTypeRequest $request): RedirectResponse
    {
        VehicleType::create($request->validated());

        return redirect()->route('vehicle-types.index')
            ->with('success', 'Jenis kendaraan berhasil ditambahkan.');
    }

    public function edit(VehicleType $vehicleType): View
    {
        return view('vehicle-types.edit', compact('vehicleType'));
    }

    public function update(UpdateVehicleTypeRequest $request, VehicleType $vehicleType): RedirectResponse
    {
        $vehicleType->update($request->validated());

        return redirect()->route('vehicle-types.index')
            ->with('success', 'Jenis kendaraan berhasil diperbarui.');
    }

    public function destroy(VehicleType $vehicleType): RedirectResponse
    {
        if ($vehicleType->vehicles()->exists()) {
            return back()->with('error', 'Jenis kendaraan masih dipakai, tidak bisa dihapus.');
        }

        $vehicleType->delete();

        return redirect()->route('vehicle-types.index')
            ->with('success', 'Jenis kendaraan berhasil dihapus.');
    }
}
