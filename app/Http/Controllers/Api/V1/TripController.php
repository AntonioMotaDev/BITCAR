<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTripLocationRequest;
use App\Http\Resources\TripResource;
use App\Models\Trip;
use App\Services\TripService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function __construct(
        private TripService $tripService
    ) {}

    /**
     * Obtener viaje activo del usuario
     */
    public function active(Request $request): JsonResponse
    {
        $trip = $this->tripService->getActiveTrip($request->user());

        if (! $trip) {
            return response()->json([
                'message' => 'No hay viaje activo',
            ], 404);
        }

        return response()->json([
            'data' => new TripResource($trip->load('vehicle')),
        ]);
    }

    /**
     * Registrar ubicaciones GPS del viaje
     */
    public function storeLocations(StoreTripLocationRequest $request, Trip $trip): JsonResponse
    {
        // Verificar que el viaje pertenece al usuario autenticado
        if ($trip->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'No autorizado',
            ], 403);
        }

        // Verificar que el viaje está activo
        if (! $trip->isActive()) {
            return response()->json([
                'message' => 'El viaje ya ha finalizado',
            ], 422);
        }

        $this->tripService->recordLocations($trip, $request->locations);

        return response()->json([
            'message' => 'Ubicaciones registradas',
            'data' => [
                'locations_count' => count($request->locations),
            ],
        ], 201);
    }

    /**
     * Obtener historial de viajes del usuario
     */
    public function index(Request $request): JsonResponse
    {
        $trips = $request->user()
            ->trips()
            ->with(['vehicle', 'startLog', 'endLog'])
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => TripResource::collection($trips),
            'meta' => [
                'current_page' => $trips->currentPage(),
                'last_page' => $trips->lastPage(),
                'per_page' => $trips->perPage(),
                'total' => $trips->total(),
            ],
        ]);
    }

    /**
     * Crear un nuevo viaje
     */
    public function store(\Illuminate\Http\Request $request): JsonResponse
    {
        $request->validate([
            'vehicle_id' => 'required|integer|exists:vehicles,id',
            'start_mileage' => 'required|numeric|min:0',
            'start_fuel_level' => 'nullable|numeric|between:0,100',
        ]);

        $user = $request->user();

        // Verificar que el vehículo está asignado al usuario
        $assignment = \App\Models\VehicleAssignment::where('user_id', $user->id)
            ->where('vehicle_id', $request->input('vehicle_id'))
            ->where('is_active', true)
            ->first();

        if (! $assignment) {
            return response()->json([
                'message' => 'El vehículo no está asignado a ti',
            ], 403);
        }

        // Verificar que no haya viaje activo en este vehículo
        $activeTrip = Trip::where('vehicle_id', $request->input('vehicle_id'))
            ->whereNull('end_time')
            ->first();

        if ($activeTrip) {
            return response()->json([
                'message' => 'Ya existe un viaje activo para este vehículo',
            ], 422);
        }

        $trip = Trip::create([
            'vehicle_id' => $request->input('vehicle_id'),
            'user_id' => $user->id,
            'start_time' => now(),
            'start_mileage' => $request->input('start_mileage'),
            'start_fuel_level' => $request->input('start_fuel_level'),
            'is_active' => true,
        ]);

        return response()->json([
            'message' => 'Viaje iniciado',
            'data' => new TripResource($trip),
        ], 201);
    }

    /**
     * Finalizar un viaje
     */
    public function endTrip(\Illuminate\Http\Request $request, Trip $trip): JsonResponse
    {
        // Verificar que el viaje pertenece al usuario
        if ($trip->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'No autorizado',
            ], 403);
        }

        // Verificar que el viaje está activo
        if ($trip->end_time) {
            return response()->json([
                'message' => 'El viaje ya ha sido finalizado',
            ], 422);
        }

        $request->validate([
            'end_mileage' => 'required|numeric|min:' . $trip->start_mileage,
            'end_fuel_level' => 'nullable|numeric|between:0,100',
            'notes' => 'nullable|string',
        ]);

        // Calcular distancia
        $distance = $request->input('end_mileage') - $trip->start_mileage;

        // Estimar consumo de combustible
        $estimatedConsumption = 0;
        if ($trip->start_fuel_level && $request->input('end_fuel_level')) {
            $estimatedConsumption = $trip->start_fuel_level - $request->input('end_fuel_level');
        }

        $trip->update([
            'end_time' => now(),
            'end_mileage' => $request->input('end_mileage'),
            'end_fuel_level' => $request->input('end_fuel_level'),
            'distance_km' => round($distance, 2),
            'estimated_fuel_consumption' => round($estimatedConsumption, 2),
            'notes' => $request->input('notes'),
            'is_active' => false,
        ]);

        return response()->json([
            'message' => 'Viaje finalizado',
            'data' => new TripResource($trip),
        ], 200);
    }
}
