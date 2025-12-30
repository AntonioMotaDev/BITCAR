<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vehicle_id' => $this->vehicle_id,
            'user_id' => $this->user_id,
            'checklist_id' => $this->checklist_id,
            'type' => $this->type,
            'odometer' => $this->odometer,
            'fuel_level' => (float) $this->fuel_level,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->toIso8601String(),
            'vehicle' => new VehicleResource($this->whenLoaded('vehicle')),
            'items' => $this->whenLoaded('items'),
            'photos' => $this->whenLoaded('photos', function () {
                return $this->photos->map(function ($photo) {
                    return [
                        'id' => $photo->id,
                        'url' => asset('storage/' . $photo->file_path),
                    ];
                });
            }),
            'signature' => $this->when($this->relationLoaded('signature') && $this->signature, [
                'url' => asset('storage/' . $this->signature->path),
            ]),
        ];
    }
}
