<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'mileage' => $this->mileage,
            'fuel_level' => (float) $this->fuel_level,
            'latitude' => $this->latitude !== null ? (float) $this->latitude : null,
            'longitude' => $this->longitude !== null ? (float) $this->longitude : null,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            
            // Relaciones
            'vehicle' => new VehicleResource($this->whenLoaded('vehicle')),
            
            'user' => $this->when($this->relationLoaded('user'), [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? null,
                'email' => $this->user->email ?? null,
            ]),
            
            // Trip (si está asociado)
            'trip' => $this->when($this->relationLoaded('trip'), function () {
                return $this->trip ? [
                    'id' => $this->trip->id,
                    'start_time' => $this->trip->start_time?->toIso8601String(),
                    'end_time' => $this->trip->end_time?->toIso8601String(),
                    'start_mileage' => $this->trip->start_mileage,
                    'end_mileage' => $this->trip->end_mileage,
                    'distance_km' => $this->trip->distance_km,
                    'is_active' => $this->trip->is_active,
                ] : null;
            }),
            
            // Items respondidos
            'items' => $this->whenLoaded('vehicleLogItems', function () {
                return $this->vehicleLogItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'checklist_item_id' => $item->checklist_item_id,
                        'checklist_item_label' => $item->checklistItem->label ?? null,
                        'checklist_item_type' => $item->checklistItem->type ?? null,
                        'boolean_answer' => $item->boolean_answer,
                        'text_answer' => $item->text_answer,
                        'numeric_answer' => $item->numeric_answer,
                    ];
                });
            }),
            
            // Todas las fotos (con URLs completas)
            'photos' => $this->whenLoaded('vehicleLogPhotos', function () {
                return $this->vehicleLogPhotos->map(function ($photo) {
                    return [
                        'id' => $photo->id,
                        'checklist_item_id' => $photo->checklist_item_id,
                        'description' => $photo->description,
                        'url' => Storage::url($photo->file_path),
                        'file_path' => $photo->file_path,
                        'created_at' => $photo->created_at?->toIso8601String(),
                    ];
                });
            }),
            
            // Fotos globales (sin checklist_item_id)
            'global_photos' => $this->whenLoaded('vehicleLogPhotos', function () {
                return $this->vehicleLogPhotos
                    ->where('checklist_item_id', null)
                    ->map(function ($photo) {
                        return [
                            'id' => $photo->id,
                            'url' => Storage::url($photo->file_path),
                            'description' => $photo->description,
                        ];
                    })
                    ->values();
            }),
            
            // Fotos agrupadas por item
            'item_photos' => $this->whenLoaded('vehicleLogPhotos', function () {
                return $this->vehicleLogPhotos
                    ->whereNotNull('checklist_item_id')
                    ->groupBy('checklist_item_id')
                    ->map(function ($photos, $itemId) {
                        return [
                            'item_id' => $itemId,
                            'photos' => $photos->map(function ($photo) {
                                return [
                                    'id' => $photo->id,
                                    'url' => Storage::url($photo->file_path),
                                    'description' => $photo->description,
                                ];
                            })->values(),
                        ];
                    })
                    ->values();
            }),
            
            // Firmas por item
            'signatures' => $this->whenLoaded('signatures', function () {
                return $this->signatures->map(function ($signature) {
                    return [
                        'id' => $signature->id,
                        'checklist_item_id' => $signature->checklist_item_id,
                        'signer_name' => $signature->signer_name,
                        'signed_at' => $signature->signed_at?->toIso8601String(),
                        'signature_data' => $signature->signature_data, // Base64
                    ];
                });
            }),
            
            // Resumen
            'summary' => [
                'photos_count' => $this->whenLoaded('vehicleLogPhotos', fn() => $this->vehicleLogPhotos->count(), 0),
                'global_photos_count' => $this->whenLoaded('vehicleLogPhotos', fn() => $this->vehicleLogPhotos->where('checklist_item_id', null)->count(), 0),
                'item_photos_count' => $this->whenLoaded('vehicleLogPhotos', fn() => $this->vehicleLogPhotos->whereNotNull('checklist_item_id')->count(), 0),
                'items_count' => $this->whenLoaded('vehicleLogItems', fn() => $this->vehicleLogItems->count(), 0),
                'signatures_count' => $this->whenLoaded('signatures', fn() => $this->signatures->count(), 0),
                'trip_active' => $this->whenLoaded('trip', fn() => $this->trip?->is_active, false),
            ],
        ];
    }
}
