<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChecklistResource;
use App\Services\ChecklistService;
use Illuminate\Http\JsonResponse;

class ChecklistController extends Controller
{
    public function __construct(
        private ChecklistService $checklistService
    ) {}

    /**
     * Obtener checklist activo
     */
    public function active(): JsonResponse
    {
        $checklist = $this->checklistService->getActiveChecklist();

        if (! $checklist) {
            return response()->json([
                'message' => 'No hay checklist activo',
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
