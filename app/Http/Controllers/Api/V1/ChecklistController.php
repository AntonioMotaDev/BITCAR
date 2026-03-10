<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitChecklistRequest;
use App\Http\Resources\ChecklistResource;
use App\Http\Resources\VehicleLogResource;
use App\Models\Signature;
use App\Models\Trip;
use App\Models\TripLocation;
use App\Models\VehicleLog;
use App\Models\VehicleLogItem;
use App\Models\VehicleLogPhoto;
use App\Services\ChecklistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChecklistController extends Controller
{
    public function __construct(
        private ChecklistService $checklistService
    ) {}

    /**
     * Obtener checklist activo
     */
    public function active(Request $request): JsonResponse
    {
        $type = $request->query('type');

        if($type) {
            $checklist = $this->checklistService->getChecklistByType($type);
            if (! $checklist) {
                return response()->json([
                    'message' => 'No se encontró un checklist activo para el tipo especificado',
                ], 404);
            }
            return response()->json([
                'data' => new ChecklistResource($checklist),
            ]);
        } else {
            return response()->json([
                'message' => 'El parámetro type es requerido',
            ], 422);
        }
    }

    /**
     * Obtener checklist por tipo
     */
    public function showByType(string $checklist_type): JsonResponse
    {
        $checklist = $this->checklistService->getChecklistByType($checklist_type);
        if (! $checklist) {
            return response()->json([
                'message' => 'No se encontró un checklist para el tipo especificado',
            ], 404);
        }
        return response()->json([
            'data' => new ChecklistResource($checklist),
        ]);
    }

    /**
     * Enviar respuestas completadas de un checklist
     */
    public function submit(SubmitChecklistRequest $request, int $checklistId): JsonResponse
    {
        DB::beginTransaction();
        
        try {
            // 1. Verificar que el checklist existe
            $checklist = \App\Models\Checklist::with('checklistItems')->findOrFail($checklistId);
            $type = $request->input('type');
            $vehicleId = $request->input('vehicle_id');
            $userId = $request->user()->id;

            // 1.1 Validar lógica de viajes
            $trip = null;
            if ($type === 'trip_start') {
                // Verificar que no hay un viaje activo para este vehículo+usuario
                $activeTrip = Trip::where('vehicle_id', $vehicleId)
                    ->where('user_id', $userId)
                    ->whereNull('end_time')
                    ->where('is_active', true)
                    ->first();

                if ($activeTrip) {
                    return response()->json([
                        'message' => 'Ya hay un viaje activo. Debe finalizar el viaje anterior antes de iniciar uno nuevo.',
                        'active_trip_id' => $activeTrip->id,
                    ], 422);
                }

                // Verificar que no haya una solicitud pendiente
                $pendingRequest = VehicleLog::where('vehicle_id', $vehicleId)
                    ->where('user_id', $userId)
                    ->where('type', 'trip_start')
                    ->whereNull('trip_id')
                    ->first();

                if ($pendingRequest) {
                    return response()->json([
                        'message' => 'Ya hay una solicitud de inicio de viaje pendiente de aprobación.',
                        'pending_log_id' => $pendingRequest->id,
                    ], 422);
                }
            } elseif ($type === 'trip_end') {
                // Verificar que hay un viaje activo
                $trip = Trip::where('vehicle_id', $vehicleId)
                    ->where('user_id', $userId)
                    ->whereNull('end_time')
                    ->where('is_active', true)
                    ->first();
                
                if (!$trip) {
                    return response()->json([
                        'message' => 'No hay un viaje activo. No puedes finalizar un viaje que no existe.',
                    ], 422);
                }
            } elseif ($type === 'trip_checkpoint') {
                // Verificar que hay un viaje activo
                $trip = Trip::where('vehicle_id', $vehicleId)
                    ->where('user_id', $userId)
                    ->whereNull('end_time')
                    ->where('is_active', true)
                    ->first();
                
                if (!$trip) {
                    return response()->json([
                        'message' => 'No hay un viaje activo. No puedes hacer un checkpoint sin un viaje iniciado.',
                    ], 422);
                }
            }

            // 2. Validar items requeridos
            $requiredItems = $checklist->checklistItems()->where('required', true)->pluck('id');
            $submittedItemIds = collect($request->input('items'))->pluck('checklist_item_id');
            $checklistItemIds = $checklist->checklistItems->pluck('id')->map(fn ($id) => (int) $id);

            $missingItems = $requiredItems->diff($submittedItemIds);
            if ($missingItems->isNotEmpty()) {
                $missingLabels = $checklist->checklistItems()
                    ->whereIn('id', $missingItems)
                    ->pluck('label');
                
                return response()->json([
                    'message' => 'Faltan respuestas en campos requeridos',
                    'missing_fields' => $missingLabels,
                ], 422);
            }

            // 3. Crear VehicleLog
            $log = VehicleLog::create([
                'vehicle_id' => $vehicleId,
                'user_id' => $userId,
                'checklist_id' => $checklistId,
                'trip_id' => $trip?->id,
                'type' => $type,
                'mileage' => $request->input('mileage'),
                'fuel_level' => $request->input('fuel_level'),
                'notes' => $request->input('notes'),
            ]);

            // 3.1 Si es trip_end, cerrar el Trip
            if ($type === 'trip_end' && $trip) {
                $trip->update([
                    'end_time' => now(),
                    'end_mileage' => $request->input('mileage'),
                    'end_fuel_level' => $request->input('fuel_level'),
                    'is_active' => false,
                    'distance_km' => $request->input('mileage') 
                        ? ($request->input('mileage') - ($trip->start_mileage ?? 0))
                        : null,
                ]);
            }

            // 4. Guardar respuestas de items
            foreach ($request->input('items') as $item) {
                $checklistItem = \App\Models\ChecklistItem::find($item['checklist_item_id']);
                
                // Si el item es tipo signature, guardar en tabla signatures
                if ($checklistItem && $checklistItem->type === 'signature') {
                    // Guardar en tabla signatures
                    if (isset($item['text_answer']) && !empty($item['text_answer'])) {
                        $signatureRaw = trim((string) $item['text_answer']);
                        $signatureData = str_starts_with($signatureRaw, 'data:image')
                            ? $signatureRaw
                            : 'data:image/png;base64,' . preg_replace('/\s+/', '', $signatureRaw);

                        Signature::updateOrCreate(
                            [
                                'vehicle_log_id' => $log->id,
                                'checklist_item_id' => (int) $item['checklist_item_id'],
                            ],
                            [
                                'signature_data' => $signatureData,
                                'signer_name' => $request->input('signer_name') ?? $request->user()->name,
                                'signed_at' => now(),
                            ]
                        );
                    }
                } else {
                    // Guardar respuesta normal en vehicle_log_items
                    VehicleLogItem::create([
                        'vehicle_log_id' => $log->id,
                        'checklist_item_id' => $item['checklist_item_id'],
                        'boolean_answer' => $item['boolean_answer'] ?? null,
                        'text_answer' => $item['text_answer'] ?? null,
                        'numeric_answer' => $item['numeric_answer'] ?? null,
                    ]);
                }
            }

            // 5. Guardar fotos globales
            if ($request->hasFile('photos')) {
                $photos = $request->file('photos');
                // Normalizar: asegurar que siempre sea un array
                $photosArray = is_array($photos) ? $photos : [$photos];
                
                foreach ($photosArray as $index => $photo) {
                    $filename = "log_{$log->id}_photo_" . time() . "_{$index}." . $photo->extension();
                    $path = $photo->storeAs('vehicle_logs/photos', $filename, 'public');
                    
                    VehicleLogPhoto::create([
                        'vehicle_log_id' => $log->id,
                        'checklist_item_id' => null, // Foto global
                        'file_path' => $path,
                        'description' => "Foto global del log",
                    ]);
                }
            }

            // 6. Guardar fotos por item
            foreach ($request->allFiles() as $key => $files) {
                if (strpos($key, 'item_photos_') === 0) {
                    $itemId = (int) str_replace('item_photos_', '', $key);
                    
                    // Verificar que el item pertenece al checklist enviado
                    if (!$checklistItemIds->contains($itemId)) {
                        continue;
                    }
                    
                    // Normalizar: asegurar que $files sea siempre un array
                    $filesArray = is_array($files) ? $files : [$files];
                    
                    foreach ($filesArray as $index => $photo) {
                        $filename = "log_{$log->id}_item_{$itemId}_" . time() . "_{$index}." . $photo->extension();
                        $path = $photo->storeAs('vehicle_logs/items', $filename, 'public');
                        
                        VehicleLogPhoto::create([
                            'vehicle_log_id' => $log->id,
                            'checklist_item_id' => $itemId,
                            'file_path' => $path,
                            'description' => "Foto del item {$itemId}",
                        ]);
                    }
                }
            }

            // 7. Guardar firmas por item enviadas como item_signature_{id}
            foreach ($request->all() as $key => $value) {
                if (!preg_match('/^item_signature_(\d+)$/', $key, $matches)) {
                    continue;
                }

                $itemId = (int) $matches[1];
                if (!$checklistItemIds->contains($itemId)) {
                    continue;
                }

                if (!is_string($value) || trim($value) === '') {
                    continue;
                }

                $signatureRaw = trim($value);
                $signatureData = str_starts_with($signatureRaw, 'data:image')
                    ? $signatureRaw
                    : 'data:image/png;base64,' . preg_replace('/\s+/', '', $signatureRaw);

                Signature::updateOrCreate(
                    [
                        'vehicle_log_id' => $log->id,
                        'checklist_item_id' => $itemId,
                    ],
                    [
                        'signature_data' => $signatureData,
                        'signer_name' => $request->input('signer_name') ?? $request->user()->name,
                        'signed_at' => now(),
                    ]
                );
            }

            // 8. Commit transaction
            DB::commit();

            // 9. Cargar relaciones para la respuesta
            $log->load([
                'vehicle',
                'user',
                'vehicleLogItems.checklistItem',
                'vehicleLogPhotos',
                'signatures',
                'trip'
            ]);

            return response()->json([
                'message' => 'Checklist enviado exitosamente',
                'data' => new VehicleLogResource($log),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error al enviar checklist', [
                'checklist_id' => $checklistId,
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Error al procesar el checklist',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno del servidor',
            ], 500);
        }
    }
}
