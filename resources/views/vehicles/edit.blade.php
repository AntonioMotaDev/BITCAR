<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-neutral-800 leading-tight">
                Editar Vehículo
            </h2>
            <a href="{{ route('vehicles.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-neutral-300 rounded-lg font-medium text-sm text-neutral-700 hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg border border-neutral-200 overflow-hidden">
            <form action="{{ route('vehicles.update', $vehicle) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="p-6 space-y-6">
                    <!-- Brand, Model, Year -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="brand" class="block text-sm font-medium text-neutral-700 mb-2">
                                Marca <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="brand" id="brand" value="{{ old('brand', $vehicle->brand) }}" 
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-neutral-500 focus:border-neutral-500 @error('brand') border-red-500 @enderror"
                                   required>
                            @error('brand')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="model" class="block text-sm font-medium text-neutral-700 mb-2">
                                Modelo <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="model" id="model" value="{{ old('model', $vehicle->model) }}"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-neutral-500 focus:border-neutral-500 @error('model') border-red-500 @enderror"
                                   required>
                            @error('model')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="year" class="block text-sm font-medium text-neutral-700 mb-2">
                                Año <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="year" id="year" value="{{ old('year', $vehicle->year) }}" min="1900" max="{{ date('Y') + 1 }}"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-neutral-500 focus:border-neutral-500 @error('year') border-red-500 @enderror"
                                   required>
                            @error('year')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- License Plate & VIN -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="license_plate" class="block text-sm font-medium text-neutral-700 mb-2">
                                Placas <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="license_plate" id="license_plate" value="{{ old('license_plate', $vehicle->license_plate) }}"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg font-mono uppercase focus:ring-2 focus:ring-neutral-500 focus:border-neutral-500 @error('license_plate') border-red-500 @enderror"
                                   required>
                            @error('license_plate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="vin" class="block text-sm font-medium text-neutral-700 mb-2">
                                VIN
                            </label>
                            <input type="text" name="vin" id="vin" value="{{ old('vin', $vehicle->vin) }}"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg font-mono uppercase focus:ring-2 focus:ring-neutral-500 focus:border-neutral-500 @error('vin') border-red-500 @enderror">
                            @error('vin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Color & Type -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="color" class="block text-sm font-medium text-neutral-700 mb-2">
                                Color <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="color" id="color" value="{{ old('color', $vehicle->color) }}"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-neutral-500 focus:border-neutral-500 @error('color') border-red-500 @enderror"
                                   required>
                            @error('color')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-neutral-700 mb-2">
                                Tipo <span class="text-red-500">*</span>
                            </label>
                            <select name="type" id="type" 
                                    class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-neutral-500 focus:border-neutral-500 @error('type') border-red-500 @enderror"
                                    required>
                                <option value="">Seleccionar...</option>
                                <option value="pickup" {{ old('type', $vehicle->type) == 'pickup' ? 'selected' : '' }}>Pickup</option>
                                <option value="sedan" {{ old('type', $vehicle->type) == 'sedan' ? 'selected' : '' }}>Sedán</option>
                                <option value="suv" {{ old('type', $vehicle->type) == 'suv' ? 'selected' : '' }}>SUV</option>
                                <option value="van" {{ old('type', $vehicle->type) == 'van' ? 'selected' : '' }}>Van</option>
                                <option value="camion" {{ old('type', $vehicle->type) == 'camion' ? 'selected' : '' }}>Camión</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Mileage & Fuel Capacity -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="mileage" class="block text-sm font-medium text-neutral-700 mb-2">
                                Kilometraje Actual <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="mileage" id="mileage" value="{{ old('mileage', $vehicle->mileage) }}" min="0" step="0.01"
                                       class="w-full px-3 py-2 pr-12 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-neutral-500 focus:border-neutral-500 @error('mileage') border-red-500 @enderror"
                                       required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-neutral-500 sm:text-sm">km</span>
                                </div>
                            </div>
                            @error('mileage')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="fuel_capacity" class="block text-sm font-medium text-neutral-700 mb-2">
                                Capacidad de Combustible <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="fuel_capacity" id="fuel_capacity" value="{{ old('fuel_capacity', $vehicle->fuel_capacity) }}" min="0" step="0.01"
                                       class="w-full px-3 py-2 pr-12 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-neutral-500 focus:border-neutral-500 @error('fuel_capacity') border-red-500 @enderror"
                                       required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-neutral-500 sm:text-sm">L</span>
                                </div>
                            </div>
                            @error('fuel_capacity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-neutral-700 mb-2">
                            Estado <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status"
                                class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-neutral-500 focus:border-neutral-500 @error('status') border-red-500 @enderror"
                                required>
                            <option value="activo" {{ old('status', $vehicle->status) == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="mantenimiento" {{ old('status', $vehicle->status) == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                            <option value="inactivo" {{ old('status', $vehicle->status) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="px-6 py-4 bg-neutral-50 border-t border-neutral-200 flex justify-end space-x-3">
                    <a href="{{ route('vehicles.index') }}" class="px-4 py-2 bg-white border border-neutral-300 rounded-lg font-medium text-sm text-neutral-700 hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-2">
                        Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 bg-neutral-800 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-2">
                        Actualizar Vehículo
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
