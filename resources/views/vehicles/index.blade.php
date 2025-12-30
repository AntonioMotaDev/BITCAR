<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-neutral-800 leading-tight">
                Vehículos
            </h2>
            @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
                <a href="{{ route('vehicles.create') }}" class="inline-flex items-center px-4 py-2 bg-neutral-800 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nuevo Vehículo
                </a>
            @endif
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg border border-neutral-200 p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-500">Total</p>
                        <p class="text-2xl font-semibold text-neutral-900">{{ $vehicles->total() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg border border-neutral-200 p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-500">Activos</p>
                        <p class="text-2xl font-semibold text-green-600">{{ $vehicles->where('status', 'activo')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg border border-neutral-200 p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-500">Mantenimiento</p>
                        <p class="text-2xl font-semibold text-amber-600">{{ $vehicles->where('status', 'mantenimiento')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg border border-neutral-200 p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-500">Inactivos</p>
                        <p class="text-2xl font-semibold text-red-600">{{ $vehicles->where('status', 'inactivo')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vehicles Table -->
        <div class="bg-white rounded-lg border border-neutral-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Vehículo
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Placas
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Asignado a
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Kilometraje
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                        @forelse($vehicles as $vehicle)
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-neutral-100 rounded-lg flex items-center justify-center">
                                            <svg class="h-6 w-6 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-neutral-900">{{ $vehicle->brand }} {{ $vehicle->model }}</div>
                                            <div class="text-sm text-neutral-500">{{ $vehicle->year }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-mono font-medium text-neutral-900">{{ $vehicle->license_plate }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($vehicle->status)
                                        @case('activo')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3"/>
                                                </svg>
                                                Activo
                                            </span>
                                            @break
                                        @case('mantenimiento')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3"/>
                                                </svg>
                                                Mantenimiento
                                            </span>
                                            @break
                                        @case('inactivo')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3"/>
                                                </svg>
                                                Inactivo
                                            </span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                    @if($vehicle->currentAssignment)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            {{ $vehicle->currentAssignment->user->name }}
                                        </div>
                                    @else
                                        <span class="text-neutral-400">Sin asignar</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900">
                                    {{ number_format($vehicle->mileage) }} km
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('vehicles.show', $vehicle) }}" class="text-neutral-600 hover:text-neutral-900" title="Ver detalles">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
                                            <a href="{{ route('vehicles.edit', $vehicle) }}" class="text-neutral-600 hover:text-neutral-900" title="Editar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de eliminar este vehículo?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-neutral-900">No hay vehículos</h3>
                                    <p class="mt-1 text-sm text-neutral-500">Comience agregando un nuevo vehículo.</p>
                                    @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
                                        <div class="mt-6">
                                            <a href="{{ route('vehicles.create') }}" class="inline-flex items-center px-4 py-2 bg-neutral-800 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-neutral-700">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                                Nuevo Vehículo
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($vehicles->hasPages())
                <div class="px-6 py-4 border-t border-neutral-200">
                    {{ $vehicles->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
