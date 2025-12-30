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
}
