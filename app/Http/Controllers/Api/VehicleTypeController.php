<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleTypeResource;
use App\Models\VehicleType;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class VehicleTypeController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return VehicleTypeResource::collection(
            VehicleType::withCount('vehicles')->orderBy('name')->get()
        );
    }
}
