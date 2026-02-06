<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use App\Models\Document;
use App\Models\User;
use App\Models\VehicleAssignment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    use AuthorizesRequests;
    public function index(): View
    {
        $this->authorize('viewAny', Vehicle::class);

        $users = User:: all();

        $vehicles = Vehicle::latest()
        ->with(['vehicleDocuments' => function ($query) {
            $query->select([
                'id',
                'user_id', 
                'vehicle_id',
                'file_name',
                'path',
                'expiration_date',
                'status'
            ]);
        }])
        ->paginate(15);
        
        $typeOptions = [
            'pickup' => ['label' => 'PickUp'],
            'sedan' => ['label' => 'Sedan'],
            'suv' => ['label' => 'Suv'],
            'van' => ['label' => 'Van'],
            'camión' => ['label' => 'Camión']
        ];

        return view('vehicles.index', compact('vehicles','typeOptions','users'));
    }

    public function create(): View
    {
        $this->authorize('create', Vehicle::class);
        
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Vehicle::class);

        try{
            // Validación
            $validated = $request->validate([
                'brand' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'year' => 'required|string|max:255',
                'license_plate' => 'required|string|max:255',
                'vin' => 'required|string|max:255',
                'color' => 'required|string|max:255',
                'type' => 'required|in:pickup,sedan,suv,van,camión',
                'mileage' => 'required|decimal:2',
                'fuel_capacity' => 'required|decimal:2',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ],
            [
                'mileage.decimal' => 'El campo kilometraje debe tener un formato numérico válido con hasta dos decimales.',
                'fuel_capacity.decimal' => 'El campo capacidad de combustible debe tener un formato numérico válido con hasta dos decimales.',
            ]);

            // Crear unidad
            $vehicle = new Vehicle();
            $vehicle->brand = $validated['brand'];
            $vehicle->model = $validated['model'];
            $vehicle->year = $validated['year'];
            $vehicle->license_plate = $validated['license_plate'];
            $vehicle->vin = $validated['vin'];
            $vehicle->color = $validated['color'];
            $vehicle->type = $validated['type'];
            $vehicle->mileage = $validated['mileage'];
            $vehicle->fuel_capacity = $validated['fuel_capacity'];
            $vehicle->status = 'activo';
            $vehicle->save();

            // Manejar imagen si se subió
            if ($request->hasFile('image')) {
                // Obtener el archivo
                $image = $request->file('image');
                
                // Generar un nombre único para la imagen
                $imageName = $vehicle->id . '.' . $image->getClientOriginalExtension();
                
                $path = $image->storeAs('vehicles', $imageName, 'public');
                
                $vehicle->image = $path; 
                $vehicle->save();
            }

            return redirect()->route('vehicles.index')
                ->with('success', 'Unidad creada exitosamente');
        }
        catch(\Exception $e)
        {
            return redirect()->route('vehicles.index')
                ->with('error', 'Error al crear la unidad: ' . $e->getMessage());
        }
    }

    public function show(Vehicle $vehicle): View
    {
        $this->authorize('view', $vehicle);
        
        $vehicle->load(['assignments.user', 'logs.user', 'trips']);

        return view('vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle): View
    {
        $this->authorize('update', $vehicle);
        
        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        
        // Validar los datos
        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'required|string|max:100',
            'fuel_capacity' => 'required|numeric|min:0',
            'license_plate' => 'required|string|max:20|unique:vehicles,license_plate,' . $id,
            'vin' => 'required|string|max:50|unique:vehicles,vin,' . $id,
            'mileage' => 'required|numeric|min:0',
            'type' => 'required|string',
            'status' => 'required|in:activo,mantenimiento,inactivo',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        
        // Eliminar la imagen si delete_image=1
        if ($request->has('delete_image') && $request->input('delete_image') == '1' && $vehicle->image) {
            if (Storage::disk('public')->exists($vehicle->image)) {
                Storage::disk('public')->delete($vehicle->image);
            }
            $vehicle->image = null;
        }
        
        // Si se subió una nueva imagen
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($vehicle->image && Storage::disk('public')->exists($vehicle->image)) {
                Storage::disk('public')->delete($vehicle->image);
            }
            
            // Obtener extensión del archivo
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = "vehicles/{$id}.{$extension}";
            
            // Guardar la imagen
            $request->file('image')->storeAs('vehicles', "{$id}.{$extension}", 'public');
            
            $vehicle->image = $fileName;
        }
        
        // Actualizar los demás campos
        $vehicle->brand = $validated['brand'];
        $vehicle->model = $validated['model'];
        $vehicle->year = $validated['year'];
        $vehicle->color = $validated['color'];
        $vehicle->fuel_capacity = $validated['fuel_capacity'];
        $vehicle->license_plate = $validated['license_plate'];
        $vehicle->vin = $validated['vin'];
        $vehicle->mileage = $validated['mileage'];
        $vehicle->type = $validated['type'];
        $vehicle->status = $validated['status'];
   
        $vehicle->save();
        
        return redirect()->route('vehicles.index')
            ->with('success', 'Vehículo actualizado exitosamente');
    }

    public function uploadDocument(Request $request)
    {
        // Validar los datos
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'file_name' => 'required|string|max:255',
            'document_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,txt|max:5120',
            'expiration_date' => 'nullable|date',
        ]);

        $file = $request->file('document_file');
        $vehicleId = $validated['vehicle_id'];
        
        // Crear nombre único
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        
        // Limpiar nombre original (remover caracteres especiales)
        $cleanName = preg_replace('/[^A-Za-z0-9\-]/', '', $originalName);
        $cleanName = substr($cleanName, 0, 50);
        
        $timestamp = time();
        $fileName = 'vehicle_'. $vehicleId . '_' . $timestamp . '_' . $cleanName . '.' . $extension;
        $filePath = 'documents/' . $fileName;
        
        try {
            // Guardar archivo
            $file->storeAs('documents', $fileName, 'public');

           if (isset($validated['expiration_date']) && $validated['expiration_date']) {
                $expirationDate = \Carbon\Carbon::parse($validated['expiration_date']);
                
                if ($expirationDate->isPast()) {
                    $status = 'vencido';
                }
            }
            
            $document = new Document();
            $document->vehicle_id = $vehicleId;
            $document->file_name = $validated['file_name'];
            $document->path = $filePath;
            $document->expiration_date = $validated['expiration_date'] ?? null;
            $document->status = $status ?? 'activo';
            $document->save();
            
            return redirect()->route('vehicles.index')
                ->with('success', 'Documento "' . $validated['file_name'] . '" subido con éxito');
                
        } catch (\Exception $e) {
            return redirect()->route('vehicles.index')
                ->with('error', 'Error al subir el documento: ' . $e->getMessage());
        }
    }

    public function storeAssignment(Request $request){
        
        try{
            $validated = $request->validate([
                'vehicle_id' => 'required|exists:vehicles,id',
                'user_id' => 'required|exists:users,id',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]); 

            VehicleAssignment::create([
                'vehicle_id' => $validated['vehicle_id'],
                'user_id' => $validated['user_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'] ?? null,
            ]);

            return redirect()->back()
                ->with('success', 'Unidad asignada correctamente.');
        
        } catch(\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al asignar la unidad: ' . $e->getMessage());
        }   
    }

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        $this->authorize('delete', $vehicle);
        
        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehículo eliminado exitosamente');
    }
}
