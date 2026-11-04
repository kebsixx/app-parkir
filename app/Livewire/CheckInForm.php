<?php

namespace App\Livewire;

use App\Models\ParkingSession;
use App\Models\Vehicle;
use App\Services\ParkingService;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class CheckInForm extends Component
{
    public string $search = '';

    public ?int $vehicleId = null;

    public function selectVehicle(int $vehicleId): void
    {
        $this->vehicleId = $vehicleId;
        $this->resetErrorBag('vehicleId');
    }

    public function checkIn(ParkingService $service)
    {
        $this->validate([
            'vehicleId' => ['required', 'integer', 'exists:vehicles,id'],
        ], [
            'vehicleId.required' => 'Pilih kendaraan terlebih dahulu.',
        ]);

        $vehicle = Vehicle::with('type')->findOrFail($this->vehicleId);

        try {
            $session = $service->checkIn($vehicle, auth()->user());
        } catch (ValidationException $exception) {
            $this->addError('vehicleId', $exception->errors()['vehicle_id'][0] ?? 'Kendaraan tidak bisa dicatat masuk.');

            return null;
        }

        session()->flash('success', 'Kendaraan berhasil dicatat masuk.');

        return redirect()->route('parking.success', $session->id);
    }

    public function render()
    {
        $vehicles = Vehicle::query()
            ->with('type')
            ->when(strlen($this->search) >= 2, function ($query) {
                $needle = strtoupper($this->search);

                $query->where(function ($query) use ($needle) {
                    $query->where('plate_number', 'like', "%{$needle}%")
                        ->orWhereRaw('LOWER(owner_name) LIKE ?', ['%'.strtolower($this->search).'%']);
                });
            })
            ->orderBy('plate_number')
            ->limit(8)
            ->get();

        $selectedVehicle = $this->vehicleId
            ? Vehicle::with('type')->find($this->vehicleId)
            : null;

        return view('livewire.check-in-form', compact('vehicles', 'selectedVehicle'));
    }
}
