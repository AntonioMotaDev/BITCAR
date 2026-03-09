<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChecklistRequest;
use App\Http\Requests\UpdateChecklistRequest;
use App\Models\Checklist;
use App\Services\ChecklistService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ChecklistItem;

// logs
use Illuminate\Support\Facades\Log;

class ChecklistController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private ChecklistService $checklistService
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', Checklist::class);
        
        $checklists = Checklist::latest()
        ->with(['checklistItems' => function ($query) {
            $query->select([
                'id',
                'checklist_id', 
                'label',
                'description',
                'type',
                'order',
                'required'
            ]);
        }])
        ->paginate(15);
        
        $typeOptions = [
            'boolean' => ['label' => 'Si/No (Checkbox)'],
            'text' => ['label' => 'Texto'],
            'number' => ['label' => 'Numerico'],
            'photo' => ['label' => 'Foto'],
            'signature' => ['label' => 'Firma'],
        ];

        $typeChecklistOptions = [
                'entry' => ['label' => 'Entrada'],
                'exit' => ['label' => 'Salida'],
                'trip_start' => ['label' => 'Inicio de viaje'],
                'trip_checkpoint' => ['label' => 'Punto en el viaje'],
                'trip_end' => ['label' => 'Fin de viaje'],
                'fuel' => ['label' => 'Combustible'],
                'incident' => ['label' => 'Incidente'],
                'maintenance' => ['label' => 'Mantenimiento'], 
                'other' => ['label' => 'Otro']
        ];
        
        return view('configuration.index', compact('checklists','typeOptions','typeChecklistOptions'));
    }

    public function create(): View
    {
        $this->authorize('create', Checklist::class);
        return view('checklists.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Checklist::class);
        
        // Validar los datos del formulario
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'items' => 'required|string', // Los items como JSON string
            'checklist_type' => 'required|in:entry,exit,trip_start,trip_checkpoint,trip_end,fuel,incident,maintenance,other'
        ], [
            'name.required' => 'El nombre de la bitácora es obligatorio.',
            'name.max' => 'El nombre no puede exceder los 255 caracteres.',
            'description.required' => 'La descripción es obligatoria.',
            'items.required' => 'Debe agregar al menos un item a la bitácora.',
        ]);
        
        try {
            // Decodificar los items del JSON
            $items = json_decode($validated['items'], true);
            
            if (!is_array($items) || count($items) === 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Debe agregar al menos un item a la bitácora.');
            }
            
            // Validar estructura de cada item
            foreach ($items as $index => $item) {
                if (!isset($item['label']) || empty(trim($item['label']))) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "El item en la posición " . ($index + 1) . " no tiene un nombre válido.");
                }
                
                if (!isset($item['type']) || empty($item['type'])) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "El item en la posición " . ($index + 1) . " no tiene un tipo válido.");
                }

                $validTypes = ['boolean', 'text', 'number', 'photo', 'signature'];
                if (!in_array($item['type'], $validTypes)) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "El tipo '" . $item['type'] . "' en el item " . ($index + 1) . " no es válido.");
                }
                
                // Validar que is_required sea un booleano
                if (isset($item['is_required']) && !is_bool($item['is_required']) && !is_numeric($item['is_required'])) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "El campo 'obligatorio' en el item " . ($index + 1) . " debe ser un valor booleano.");
                }
            }
            
            DB::beginTransaction();
            
            try {
                // Crear el checklist
                $checklist = Checklist::create([
                    'name' => $validated['name'],
                    'description' => $validated['description'],
                    'is_active' => true,
                    'type' => $validated['checklist_type'],
                ]);
                
                // Crear los items del checklist
                foreach ($items as $order => $itemData) {
                    ChecklistItem::create([
                        'checklist_id' => $checklist->id,
                        'label' => trim($itemData['label']),
                        'type' => $itemData['type'],
                        'description' => isset($itemData['description']) ? trim($itemData['description']) : null,
                        'order' => isset($itemData['order']) ? intval($itemData['order']) : ($order + 1),
                        'required' => isset($itemData['is_required']) ? 
                            (boolval($itemData['is_required']) || $itemData['is_required'] == 1) : 
                            false,
                    ]);
                }
                
                // Actualizar timestamp del checklist para invalidar caché en app móvil
                $checklist->touch();
                
                DB::commit();
                
                // Redirigir con mensaje de éxito
                return redirect()->route('checklists.index')
                    ->with('success', 'Bitácora creada exitosamente con ' . count($items) . ' items.');
                    
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error al crear checklist: ' . $e->getMessage(), [
                'user_id' => $request->user()->id ?? null,
                'request_data' => $request->except('items'),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la bitácora: ' . $e->getMessage());
        }
    }

    public function show(Checklist $checklist): View
    {
        $this->authorize('view', $checklist);
        $checklist->load('checklistItems');
        return view('checklists.show', compact('checklist'));
    }

    public function edit(Checklist $checklist): View
    {
        $this->authorize('update', $checklist);
        $checklist->load('checklistItems');
        return view('checklists.edit', compact('checklist'));
    }

    public function update(Request $request, Checklist $checklist)
    {
        $this->authorize('update', $checklist);
        
        // Validar los datos del formulario
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'edit_checklist_type' => 'required|in:entry,exit,trip_start,trip_checkpoint,trip_end,fuel,incident,maintenance,other',
            'items' => 'required|string', // Los items como JSON string
        ], [
            'name.required' => 'El nombre de la bitácora es obligatorio.',
            'name.max' => 'El nombre no puede exceder los 255 caracteres.',
            'description.required' => 'La descripción es obligatoria.',
            'items.required' => 'Debe agregar al menos un item a la bitácora.',
        ]);

        try {
            // Decodificar los items del JSON
            $items = json_decode($validated['items'], true);
            
            if (!is_array($items) || count($items) === 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Debe agregar al menos un item a la bitácora.');
            }
            
            // Validar cada item
            foreach ($items as $index => $item) {
                if (!isset($item['label']) || empty(trim($item['label']))) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "El item en la posición " . ($index + 1) . " no tiene un nombre válido.");
                }
                
                if (!isset($item['type']) || empty($item['type'])) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "El item en la posición " . ($index + 1) . " no tiene un tipo válido.");
                }

                $validTypes = ['boolean', 'text', 'number', 'photo', 'signature'];
                if (!in_array($item['type'], $validTypes)) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "El tipo '" . $item['type'] . "' en el item " . ($index + 1) . " no es válido.");
                }
                
                if (isset($item['is_required']) && !is_bool($item['is_required']) && !is_numeric($item['is_required'])) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "El campo 'obligatorio' en el item " . ($index + 1) . " debe ser un valor booleano.");
                }
            }
            
            DB::beginTransaction();
            
            try {
                // Actualizar el checklist
                $checklist->update([
                    'name' => $validated['name'],
                    'description' => $validated['description'],
                    'is_active' => $request->has('is_active') ? 
                        filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN) : 
                        $checklist->is_active,
                    'type' => $validated['edit_checklist_type'],
                ]);
                
                // Obtener los items existentes
                $existingItems = $checklist->checklistItems()->get()->keyBy('id');
                $existingItemIds = $existingItems->keys()->toArray();
                $submittedItemIds = [];
                
                // Actualizar o crear items
                foreach ($items as $order => $itemData) {
                    $itemId = $itemData['id'] ?? null;
                    
                    $itemAttributes = [
                        'checklist_id' => $checklist->id,
                        'label' => trim($itemData['label']),
                        'type' => $itemData['type'],
                        'description' => isset($itemData['description']) ? trim($itemData['description']) : null,
                        'order' => isset($itemData['order']) ? intval($itemData['order']) : ($order + 1),
                        'required' => isset($itemData['is_required']) ? 
                            (boolval($itemData['is_required']) || $itemData['is_required'] == 1) : 
                            false,
                    ];
                    
                    if ($itemId && in_array($itemId, $existingItemIds)) {
                        // Actualizar item existente
                        ChecklistItem::where('id', $itemId)->update($itemAttributes);
                        $submittedItemIds[] = $itemId;
                    } else {
                        // Crear nuevo item
                        $newItem = ChecklistItem::create($itemAttributes);
                        $submittedItemIds[] = $newItem->id;
                    }
                }
                
                // Eliminar items que fueron removidos (solo si no tienen vehicle_log_items)
                $itemsToDelete = array_diff($existingItemIds, $submittedItemIds);
                if (!empty($itemsToDelete)) {
                    // Verificar cuáles items tienen respuestas
                    $itemsWithAnswers = DB::table('vehicle_log_items')
                        ->whereIn('checklist_item_id', $itemsToDelete)
                        ->pluck('checklist_item_id')
                        ->unique()
                        ->toArray();
                    
                    // Solo eliminar items sin respuestas
                    $safeToDelete = array_diff($itemsToDelete, $itemsWithAnswers);
                    
                    if (!empty($safeToDelete)) {
                        ChecklistItem::whereIn('id', $safeToDelete)->delete();
                    }
                    
                    // Si hay items con respuestas que no se pueden eliminar, notificar
                    if (!empty($itemsWithAnswers)) {
                        $itemsWithAnswersNames = $existingItems->whereIn('id', $itemsWithAnswers)->pluck('label')->join(', ');
                        session()->flash('warning', 'Algunos items no se pudieron eliminar porque ya tienen respuestas registradas: ' . $itemsWithAnswersNames);
                    }
                }
                
                DB::commit();
                
                // Redirigir con mensaje de éxito
                return redirect()->route('checklists.index')
                    ->with('success', 'Bitácora actualizada exitosamente con ' . count($items) . ' items.');
                    
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error al actualizar checklist: ' . $e->getMessage(), [
                'user_id' => $request->user()->id ?? null,
                'checklist_id' => $checklist->id,
                'request_data' => $request->except('items'),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la bitácora: ' . $e->getMessage());
        }
    }

    public function destroy(Checklist $checklist): RedirectResponse
    {
        $this->authorize('delete', $checklist);
        $checklist->delete();
        return redirect()->route('checklists.index')->with('success', 'Checklist eliminado');
    }

    public function duplicate(Request $request, Checklist $checklist): RedirectResponse
    {
        $this->authorize('create', Checklist::class);
        
        try {
            DB::beginTransaction();
            
            // Crear el checklist duplicado
            $newChecklist = Checklist::create([
                'name' => $checklist->name . ' (Copia)',
                'description' => $checklist->description,
                'is_active' => false, // Crear inactivo por defecto
                'type' => $checklist->type,
            ]);
            
            // Duplicar los items del checklist
            foreach ($checklist->checklistItems as $item) {
                ChecklistItem::create([
                    'checklist_id' => $newChecklist->id,
                    'label' => $item->label,
                    'type' => $item->type,
                    'description' => $item->description,
                    'order' => $item->order,
                    'required' => $item->required,
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('checklists.index')
                ->with('success', 'Bitácora duplicada exitosamente. Puedes editarla para hacer los ajustes necesarios.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error al duplicar checklist: ' . $e->getMessage(), [
                'user_id' => $request->user()->id ?? null,
                'checklist_id' => $checklist->id,
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->back()
                ->with('error', 'Error al duplicar la bitácora: ' . $e->getMessage());
        }
    }
}
