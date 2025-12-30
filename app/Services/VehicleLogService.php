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
    public function createVehicleLog(array $data, User $user, Vehicle $vehicle): VehicleLog
    {
        return DB::transaction(function () use ($data, $user, $vehicle) {
            // Crear log principal
            $log = VehicleLog::create([
                'vehicle_id' => $vehicle->id,
                'user_id' => $user->id,
                'checklist_id' => $data['checklist_id'],
                'type' => $data['type'],
                'odometer' => $data['odometer'],
                'fuel_level' => $data['fuel_level'],
                'notes' => $data['notes'] ?? null,
            ]);

            // Crear items del checklist
            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    VehicleLogItem::create([
                        'vehicle_log_id' => $log->id,
                        'checklist_item_id' => $item['checklist_item_id'],
                        'value' => $item['value'],
                    ]);
                }
            }

            // Guardar fotos
            if (isset($data['photos'])) {
                foreach ($data['photos'] as $photo) {
                    $path = $photo->store('vehicle-logs/' . $log->id, 'public');
                    VehicleLogPhoto::create([
                        'vehicle_log_id' => $log->id,
                        'file_path' => $path,
                    ]);
                }
            }

            // Guardar firma (base64)
            if (isset($data['signature'])) {
                $signaturePath = $this->saveSignature($data['signature'], $log->id);
                Signature::create([
                    'vehicle_log_id' => $log->id,
                    'path' => $signaturePath,
                ]);
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
    private function saveSignature(string $base64, int $logId): string
    {
        // Remover prefijo data:image si existe
        $base64 = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $imageData = base64_decode($base64);
        
        $filename = 'signature_' . $logId . '_' . time() . '.png';
        $path = 'vehicle-logs/' . $logId . '/signatures/' . $filename;
        
        Storage::disk('public')->put($path, $imageData);
        
        return $path;
    }
}
