<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-neutral-800 leading-tight">
                Bitácoras de Vehículos
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filters -->
        <div class="bg-white rounded-lg border border-neutral-200 p-4 mb-6">
            <form method="GET" action="{{ route('vehicle-logs.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="vehicle_id" class="block text-sm font-medium text-neutral-700 mb-1">Vehículo</label>
                    <select name="vehicle_id" id="vehicle_id" class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-neutral-500 focus:border-neutral-500 text-sm">
                        <option value="">Todos</option>
                        @foreach(\App\Models\Vehicle::all() as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ request('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                {{ $vehicle->license_plate }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-neutral-700 mb-1">Tipo</label>
                    <select name="type" id="type" class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-neutral-500 focus:border-neutral-500 text-sm">
                        <option value="">Todos</option>
                        <option value="entrada" {{ request('type') == 'entrada' ? 'selected' : '' }}>Entrada</option>
                        <option value="salida" {{ request('type') == 'salida' ? 'selected' : '' }}>Salida</option>
                    </select>
                </div>

                <div>
                    <label for="date_from" class="block text-sm font-medium text-neutral-700 mb-1">Desde</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-neutral-500 focus:border-neutral-500 text-sm">
                </div>

                <div>
                    <label for="date_to" class="block text-sm font-medium text-neutral-700 mb-1">Hasta</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-neutral-500 focus:border-neutral-500 text-sm">
                </div>

                <div class="md:col-span-4 flex justify-end space-x-2">
                    <a href="{{ route('vehicle-logs.index') }}" class="px-4 py-2 bg-white border border-neutral-300 rounded-lg font-medium text-sm text-neutral-700 hover:bg-neutral-50">
                        Limpiar
                    </a>
                    <button type="submit" class="px-4 py-2 bg-neutral-800 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-neutral-700">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg border border-neutral-200 p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-500">Total</p>
                        <p class="text-2xl font-semibold text-neutral-900">{{ $logs->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-neutral-200 p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-500">Entradas</p>
                        <p class="text-2xl font-semibold text-blue-600">{{ $logs->where('type', 'entrada')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-neutral-200 p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-500">Salidas</p>
                        <p class="text-2xl font-semibold text-purple-600">{{ $logs->where('type', 'salida')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-neutral-200 p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-500">Con Incidentes</p>
                        <p class="text-2xl font-semibold text-red-600">{{ $logs->filter(fn($log) => $log->incidents->count() > 0)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logs Table -->
        <div class="bg-white rounded-lg border border-neutral-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Vehículo
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Operador
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Fecha/Hora
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Kilometraje
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Incidentes
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                        @forelse($logs as $log)
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-neutral-100 rounded-lg flex items-center justify-center">
                                            <svg class="h-6 w-6 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-neutral-900">{{ $log->vehicle->license_plate }}</div>
                                            <div class="text-sm text-neutral-500">{{ $log->vehicle->brand }} {{ $log->vehicle->model }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-neutral-900">{{ $log->user->name }}</div>
                                    <div class="text-sm text-neutral-500">{{ ucfirst($log->user->role) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($log->type === 'entrada')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                            </svg>
                                            Entrada
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                            Salida
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900">
                                    <div>{{ $log->created_at->format('d/m/Y') }}</div>
                                    <div class="text-neutral-500">{{ $log->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900">
                                    {{ number_format($log->mileage) }} km
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($log->incidents->count() > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            {{ $log->incidents->count() }}
                                        </span>
                                    @else
                                        <span class="text-neutral-400 text-sm">Ninguno</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('vehicle-logs.show', $log) }}" class="text-neutral-600 hover:text-neutral-900" title="Ver detalles">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-neutral-900">No hay bitácoras</h3>
                                    <p class="mt-1 text-sm text-neutral-500">No se encontraron registros con los filtros aplicados.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-neutral-200">
                    {{ $logs->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
