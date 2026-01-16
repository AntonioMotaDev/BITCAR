<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use App\Models\Document;
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
        
        $vehicles = Vehicle::with('vehicleAssignments.user')
            ->latest()
            ->paginate(15);
        $documents = $documents = Document::all();
        $typeOptions = [
            'pickup' => ['label' => 'PickUp'],
            'sedan' => ['label' => 'Sedan'],
            'suv' => ['label' => 'Suv'],
            'van' => ['label' => 'Van'],
            'camión' => ['label' => 'Camión']
        ];

        return view('vehicles.index', compact('vehicles','documents','typeOptions'));
    }

    public function create(): View
    {
        $this->authorize('create', Vehicle::class);
        
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Vehicle::class);

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
            
            // Guardar la imagen en storage/app/public/users
            $path = $image->storeAs('vehicles', $imageName, 'public');
            
            $vehicle->image = $path; 
            $vehicle->save();
        }

        return redirect()->route('vehicles.index')
            ->with('success', 'Unidad creada exitosamente');
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

    public function update(Request $request, Vehicle $vehicle)
{
    $this->authorize('update', $vehicle);
    
    // Validar los datos
    $validated = $request->validate([
        'brand' => 'required|string|max:255',
        'model' => 'required|string|max:255',
        'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
        'color' => 'required|string|max:100',
        'fuel_capacity' => 'required|numeric|min:0',
        'license_plate' => 'required|string|max:20|unique:vehicles,license_plate,' . $vehicle->id,
        'vin' => 'required|string|max:50|unique:vehicles,vin,' . $vehicle->id,
        'mileage' => 'required|numeric|min:0',
        'type' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    ]);
    
    // Manejar la imagen si se subió
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        
        // Eliminar imagen anterior si existe
        if ($vehicle->image && Storage::disk('public')->exists($vehicle->image)) {
            Storage::disk('public')->delete($vehicle->image);
        }
        
        $imageName = $vehicle->id . '.' . $image->getClientOriginalExtension();
        
        $imagePath = $image->storeAs('vehicles', $imageName, 'public');
        $validated['image'] = $imagePath;
    }
    
    // Actualizar el vehículo
    $vehicle->update($validated);
    
    return redirect()->route('vehicles.index')
        ->with('success', 'Vehículo actualizado exitosamente');
}

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        $this->authorize('delete', $vehicle);
        
        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehículo eliminado exitosamente');
    }
}
