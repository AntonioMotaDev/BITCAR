<?php

namespace App\Services;

use App\Models\Incident;
use App\Models\Signature;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleLog;
use App\Models\VehicleLogItem;
use App\Models\VehicleLogPhoto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VehicleLogService
{
    /**
     * Crear vehicle log con items, fotos y firma
     */
    public function createVehicleLog(array $data, User $user, Vehicle $vehicle, $request = null): VehicleLog
    {
        return DB::transaction(function () use ($data, $user, $vehicle, $request) {
            // Crear log principal
            $log = VehicleLog::create([
                'vehicle_id' => $vehicle->id,
                'user_id' => $user->id,
                'checklist_id' => $data['checklist_id'],
                'type' => $data['type'],
                'mileage' => $data['mileage'],
                'fuel_level' => $data['fuel_level'],
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            // Crear items del checklist
            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    VehicleLogItem::create([
                        'vehicle_log_id' => $log->id,
                        'checklist_item_id' => $item['checklist_item_id'],
                        'boolean_answer' => $item['boolean_answer'] ?? null,
                        'text_answer' => $item['text_answer'] ?? null,
                        'numeric_answer' => $item['numeric_answer'] ?? null,
                    ]);
                }
            }

            // Guardar fotos globales
            if (isset($data['photos'])) {
                foreach ($data['photos'] as $photo) {
                    $path = $photo->store('vehicle-logs/' . $log->id, 'public');
                    VehicleLogPhoto::create([
                        'vehicle_log_id' => $log->id,
                        'checklist_item_id' => null,
                        'file_path' => $path,
                        'description' => null,
                    ]);
                }
            }

            // Guardar fotos por item (item_photos_X)
            if ($request) {
                foreach ($request->allFiles() as $key => $files) {
                    if (preg_match('/^item_photos_(\\d+)$/', $key, $matches)) {
                        $checklistItemId = $matches[1];
                        $photoFiles = is_array($files) ? $files : [$files];
                        
                        foreach ($photoFiles as $photo) {
                            $path = $photo->store('vehicle-logs/' . $log->id . '/items', 'public');
                            VehicleLogPhoto::create([
                                'vehicle_log_id' => $log->id,
                                'checklist_item_id' => $checklistItemId,
                                'file_path' => $path,
                                'description' => null,
                            ]);
                        }
                    }
                }
            }

            // Guardar firma global (deprecada)
            if (isset($data['signature'])) {
                $signaturePath = $this->saveSignature($data['signature'], $log->id, null);
                Signature::create([
                    'vehicle_log_id' => $log->id,
                    'checklist_item_id' => null,
                    'signature_data' => $signaturePath,
                    'signer_name' => $user->name,
                    'signed_at' => now(),
                ]);
            }

            // Guardar firmas por item (item_signature_X)
            if ($request) {
                foreach ($request->all() as $key => $value) {
                    if (preg_match('/^item_signature_(\\d+)$/', $key, $matches)) {
                        $checklistItemId = $matches[1];
                        if (!empty($value)) {
                            $signaturePath = $this->saveSignature($value, $log->id, $checklistItemId);
                            Signature::create([
                                'vehicle_log_id' => $log->id,
                                'checklist_item_id' => $checklistItemId,
                                'signature_data' => $signaturePath,
                                'signer_name' => $user->name,
                                'signed_at' => now(),
                            ]);
                        }
                    }
                }
            }

            return $log->load(['items', 'photos', 'signature']);
        });
    }

    /**
     * Agregar incidencia a un log
     */
    public function addIncident(VehicleLog $log, string $description, string $severity): Incident
    {
        return Incident::create([
            'vehicle_log_id' => $log->id,
            'description' => $description,
            'severity' => $severity,
            'is_resolved' => false,
        ]);
    }

    /**
     * Guardar firma base64 como imagen
     */
    private function saveSignature(string $base64, int $logId, ?int $checklistItemId = null): string
    {
        // Remover prefijo data:image si existe
        $base64 = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $imageData = base64_decode($base64);
        
        $itemSuffix = $checklistItemId ? '_item_' . $checklistItemId : '';
        $filename = 'signature_' . $logId . $itemSuffix . '_' . time() . '.png';
        $path = 'vehicle-logs/' . $logId . '/signatures/' . $filename;
        
        Storage::disk('public')->put($path, $imageData);
        
        return $path;
    }
}
