<?php

namespace App\Services;

use App\Models\Checklist;
use App\Models\ChecklistItem;
use Illuminate\Support\Facades\DB;

class ChecklistService
{
    /**
     * Crear checklist con sus items
     */
    public function createChecklist(array $data): Checklist
    {
        return DB::transaction(function () use ($data) {
            $checklist = Checklist::create([
                'name' => $data['name'],
                'version' => $data['version'] ?? '1.0',
                'is_active' => $data['is_active'] ?? true,
            ]);

            if (isset($data['items'])) {
                foreach ($data['items'] as $index => $itemData) {
                    ChecklistItem::create([
                        'checklist_id' => $checklist->id,
                        'label' => $itemData['label'],
                        'type' => $itemData['type'],
                        'required' => $itemData['required'] ?? false,
                        'order' => $itemData['order'] ?? $index,
                    ]);
                }
            }

            return $checklist->load('items');
        });
    }

    /**
     * Actualizar checklist
     */
    public function updateChecklist(Checklist $checklist, array $data): Checklist
    {
        return DB::transaction(function () use ($checklist, $data) {
            $checklist->update([
                'name' => $data['name'] ?? $checklist->name,
                'version' => $data['version'] ?? $checklist->version,
                'is_active' => $data['is_active'] ?? $checklist->is_active,
            ]);

            if (isset($data['items'])) {
                // Eliminar items existentes
                $checklist->items()->delete();

                // Crear nuevos items
                foreach ($data['items'] as $index => $itemData) {
                    ChecklistItem::create([
                        'checklist_id' => $checklist->id,
                        'label' => $itemData['label'],
                        'type' => $itemData['type'],
                        'required' => $itemData['required'] ?? false,
                        'order' => $itemData['order'] ?? $index,
                    ]);
                }
            }

            return $checklist->load('items');
        });
    }

    /**
     * Obtener checklist activo
     */
    public function getActiveChecklist(): ?Checklist
    {
        return Checklist::where('is_active', true)
            ->with('items')
            ->first();
    }
}
