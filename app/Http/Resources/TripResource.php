<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vehicle_id' => $this->vehicle_id,
            'user_id' => $this->user_id,
            'started_at' => $this->started_at?->toIso8601String(),
            'ended_at' => $this->ended_at?->toIso8601String(),
            'total_distance_km' => $this->total_distance_km ? (float) $this->total_distance_km : null,
            'estimated_fuel_consumption' => $this->estimated_fuel_consumption ? (float) $this->estimated_fuel_consumption : null,
            'is_active' => $this->isActive(),
            'vehicle' => new VehicleResource($this->whenLoaded('vehicle')),
            'locations' => TripLocationResource::collection($this->whenLoaded('locations')),
        ];
    }
}
