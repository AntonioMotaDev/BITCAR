<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChecklistResource;
use App\Services\ChecklistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
    public function submit(\Illuminate\Http\Request $request, int $checklistId): JsonResponse
    {
        $checklist = \App\Models\Checklist::with('items')->findOrFail($checklistId);

        // Validar que todos los items requeridos fueron respondidos
        $requiredItems = $checklist->items()->where('required', true)->get();
        $submittedItems = collect($request->input('items', []));

        foreach ($requiredItems as $required) {
            $answered = $submittedItems->firstWhere('checklist_item_id', $required->id);
            if (! $answered) {
                return response()->json([
                    'message' => 'Faltan respuestas en campos requeridos',
                    'missing_fields' => [$required->label],
                ], 422);
            }
        }

        // Crear vehicle log con las respuestas
        $user = $request->user();
        $vehicleId = $request->input('vehicle_id');
        $type = $request->input('type'); // 'entrada' o 'salida'
        $mileage = $request->input('mileage');
        $fuelLevel = $request->input('fuel_level');

        $log = \App\Models\VehicleLog::create([
            'vehicle_id' => $vehicleId,
            'user_id' => $user->id,
            'checklist_id' => $checklistId,
            'type' => $type,
            'mileage' => $mileage,
            'fuel_level' => $fuelLevel,
            'notes' => $request->input('notes'),
        ]);

        // Guardar respuestas de items
        foreach ($request->input('items', []) as $item) {
            \App\Models\VehicleLogItem::create([
                'vehicle_log_id' => $log->id,
                'checklist_item_id' => $item['checklist_item_id'],
                'boolean_answer' => $item['boolean_answer'] ?? null,
                'text_answer' => $item['text_answer'] ?? null,
                'numeric_answer' => $item['numeric_answer'] ?? null,
            ]);
        }

        return response()->json([
            'message' => 'Checklist enviado exitosamente',
            'data' => [
                'log_id' => $log->id,
                'type' => $log->type,
                'created_at' => $log->created_at,
            ],
        ], 201);
    }
}
