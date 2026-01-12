<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIncidentRequest;
use App\Http\Requests\StoreVehicleLogRequest;
use App\Http\Resources\VehicleLogResource;
use App\Services\TripService;
use App\Services\VehicleAssignmentService;
use App\Services\VehicleLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleLogController extends Controller
{
    public function __construct(
        private VehicleLogService $logService,
        private TripService $tripService,
        private VehicleAssignmentService $assignmentService
    ) {}

    /**
     * Registrar log de salida (entry) - Inicia viaje
     */
    public function storeExit(StoreVehicleLogRequest $request): JsonResponse
    {
        $user = $request->user();
        $vehicle = $this->assignmentService->getActiveVehicle($user);

        if (! $vehicle) {
            return response()->json([
                'message' => 'No tienes vehículo asignado',
            ], 403);
        }

        // Verificar que no haya viaje activo
        if ($this->tripService->hasActiveTrip($vehicle)) {
            return response()->json([
                'message' => 'Ya existe un viaje activo para este vehículo',
            ], 422);
        }

        $data = array_merge($request->validated(), ['type' => 'exit']);
        $log = $this->logService->createVehicleLog($data, $user, $vehicle);

        // Iniciar viaje
        $trip = $this->tripService->startTrip($log, $vehicle, $user);

        return response()->json([
            'message' => 'Checklist de salida registrado',
            'data' => [
                'log' => new VehicleLogResource($log),
                'trip_id' => $trip->id,
            ],
        ], 201);
    }

    /**
     * Registrar log de entrada - Finaliza viaje
     */
    public function storeEntry(StoreVehicleLogRequest $request): JsonResponse
    {
        $user = $request->user();
        $vehicle = $this->assignmentService->getActiveVehicle($user);

        if (! $vehicle) {
            return response()->json([
                'message' => 'No tienes vehículo asignado',
            ], 403);
        }

        // Obtener viaje activo
        $trip = $this->tripService->getActiveTrip($user);

        if (! $trip) {
            return response()->json([
                'message' => 'No hay viaje activo para finalizar',
            ], 422);
        }

        $data = array_merge($request->validated(), ['type' => 'entry']);
        $log = $this->logService->createVehicleLog($data, $user, $vehicle);

        // Finalizar viaje
        $trip = $this->tripService->endTrip($trip, $log);

        return response()->json([
            'message' => 'Checklist de entrada registrado',
            'data' => [
                'log' => new VehicleLogResource($log),
                'trip' => [
                    'id' => $trip->id,
                    'total_distance_km' => $trip->total_distance_km,
                    'estimated_fuel_consumption' => $trip->estimated_fuel_consumption,
                ],
            ],
        ], 201);
    }

    /**
     * Agregar incidencia a un log
     */
    public function addIncident(StoreIncidentRequest $request, int $logId): JsonResponse
    {
        $log = $request->user()->vehicleLogs()->findOrFail($logId);

        $incident = $this->logService->addIncident(
            $log,
            $request->description,
            $request->severity
        );

        return response()->json([
            'message' => 'Incidencia registrada',
            'data' => $incident,
        ], 201);
    }

    /**
     * Subir fotos a un vehicle log
     */
    public function storePhotos(\Illuminate\Http\Request $request, int $logId): JsonResponse
    {
        $log = $request->user()->vehicleLogs()->findOrFail($logId);

        if (! $request->hasFile('file')) {
            return response()->json([
                'message' => 'No se envió archivo',
            ], 422);
        }

        $file = $request->file('file');
        
        // Validar que es una imagen
        if (! in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
            return response()->json([
                'message' => 'El archivo debe ser una imagen válida',
            ], 422);
        }

        // Guardar la imagen
        $path = $file->store('vehicle-logs/' . $log->id, 'public');

        $photo = \App\Models\VehicleLogPhoto::create([
            'vehicle_log_id' => $log->id,
            'file_path' => $path,
            'description' => $request->input('description'),
        ]);

        return response()->json([
            'message' => 'Foto guardada exitosamente',
            'data' => [
                'id' => $photo->id,
                'file_path' => $path,
                'url' => \Illuminate\Support\Facades\Storage::url($path),
            ],
        ], 201);
    }

    /**
     * Registrar carga de combustible
     */
    public function storeFuelLoad(\Illuminate\Http\Request $request, int $logId): JsonResponse
    {
        $log = $request->user()->vehicleLogs()->findOrFail($logId);

        $request->validate([
            'amount_liters' => 'required|numeric|min:0.1',
            'cost' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'notes' => 'nullable|string',
        ]);

        $fuelLoad = \App\Models\FuelLoad::create([
            'vehicle_log_id' => $log->id,
            'vehicle_id' => $log->vehicle_id,
            'user_id' => $request->user()->id,
            'amount_liters' => $request->input('amount_liters'),
            'cost' => $request->input('cost'),
            'currency' => $request->input('currency'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'notes' => $request->input('notes'),
        ]);

        return response()->json([
            'message' => 'Carga de combustible registrada',
            'data' => [
                'id' => $fuelLoad->id,
                'amount_liters' => $fuelLoad->amount_liters,
                'cost' => $fuelLoad->cost,
                'created_at' => $fuelLoad->created_at,
            ],
        ], 201);
    }
