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
}
