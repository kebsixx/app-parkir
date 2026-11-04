<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class VehicleController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
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
            ->paginate(min(100, max(1, $request->integer('per_page', 15))));

        return VehicleResource::collection($vehicles);
    }

    public function store(StoreVehicleRequest $request): JsonResponse
    {
        $vehicle = Vehicle::create($request->validated());

        return (new VehicleResource($vehicle->load('type')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Vehicle $vehicle): VehicleResource
    {
        return new VehicleResource($vehicle->load('type'));
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): VehicleResource
    {
        $vehicle->update($request->validated());

        return new VehicleResource($vehicle->load('type'));
    }

    public function destroy(Vehicle $vehicle): JsonResponse
    {
        if ($vehicle->parkingSessions()->exists()) {
            return response()->json([
                'message' => 'Kendaraan punya riwayat parkir, tidak bisa dihapus.',
            ], 409);
        }

        $vehicle->delete();

        return response()->json(null, 204);
    }
}
