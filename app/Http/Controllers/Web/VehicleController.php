<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VehicleController extends Controller
{
    use AuthorizesRequests;
    public function index(): View
    {
        $this->authorize('viewAny', Vehicle::class);
        
        $vehicles = Vehicle::with('activeAssignment.user')
            ->latest()
            ->paginate(15);

        return view('vehicles.index', compact('vehicles'));
    }

    public function create(): View
    {
        $this->authorize('create', Vehicle::class);
        
        return view('vehicles.create');
    }

    public function store(StoreVehicleRequest $request): RedirectResponse
    {
        $this->authorize('create', Vehicle::class);
        
        Vehicle::create($request->validated());

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehículo creado exitosamente');
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

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): RedirectResponse
    {
        $this->authorize('update', $vehicle);
        
        $vehicle->update($request->validated());

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
