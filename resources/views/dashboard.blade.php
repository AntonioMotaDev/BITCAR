<x-app-layout>
    <div class="min-h-screen bg-neutral-50">
        <!-- Header con tarjeta de confirmación de viaje -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            @if($activeTrips->count() > 0)
                @php $trip = $activeTrips->first(); @endphp
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6 mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="bg-blue-100 rounded-full p-3">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="flex items-center space-x-3">
                                <svg class="w-10 h-10 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-amber-600">¿Confirma Inicio de Viaje?</h3>
                                <p class="text-sm text-neutral-600 mt-1">Inicio: HH:MM</p>
                                <p class="text-sm text-neutral-600">Operador: {{ $trip->user->name }}</p>
                                <p class="text-sm text-neutral-600">Unidad: {{ $trip->vehicle->license_plate }}</p>
                                <div class="flex items-center mt-1 text-xs text-neutral-500">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Ver Bitácora
                                </div>
                                <p class="text-xs text-neutral-500 mt-1">Inicio: HH:MM</p>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <button class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Aceptar</span>
                            </button>
                            <button class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span>Rechazar</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Lista de viajes -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Columna izquierda: Viajes en curso -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-neutral-800 mb-4">Viajes en Curso</h3>
                    
                    @forelse($activeTrips as $trip)
                        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                            <div class="flex items-start space-x-4">
                                <div class="bg-blue-100 rounded-full p-3">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div class="flex items-center space-x-3">
                                    {{-- <svg class="w-10 h-10 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                                    </svg> --}}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-base font-bold text-amber-600">Viaje en curso</h4>
                                    </div>
                                    <p class="text-sm text-neutral-600 mt-1">Inicio: {{ $trip->start_time->format('H:i') }}</p>
                                    <p class="text-sm text-neutral-600">Operador: {{ $trip->user->name }}</p>
                                    <p class="text-sm text-neutral-600">Unidad: {{ $trip->vehicle->license_plate }}</p>
                                    <div class="flex items-center mt-2 text-xs text-neutral-500">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        ÚLTIMA LOCACIÓN, CP: 78999
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-8 text-center">
                            {{-- <svg class="mx-auto h-12 w-12 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg> --}}
                            <p class="mt-2 text-sm text-neutral-500">No hay viajes en curso</p>
                        </div>
                    @endforelse
                </div>

                <!-- Columna derecha: Viajes finalizados -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-neutral-800 mb-4">Viajes Finalizados</h3>
                    
                    @forelse($recentLogs->take(3) as $log)
                        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                            <div class="flex items-start space-x-4">
                                <div class="bg-green-100 rounded-full p-3">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <svg class="w-10 h-10 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-base font-bold text-green-600">Viaje Finalizado</h4>
                                    </div>
                                    <p class="text-sm text-neutral-600 mt-1">Fin: {{ $log->created_at->format('H:i') }}</p>
                                    <p class="text-sm text-neutral-600">Operador: {{ $log->user->name }}</p>
                                    <p class="text-sm text-neutral-600">Unidad: {{ $log->vehicle->license_plate }}</p>
                                    <div class="flex items-center mt-2 text-xs text-neutral-500">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        ÚLTIMA LOCACIÓN, CP: 78999
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-8 text-center">
                            <p class="mt-2 text-sm text-neutral-500">No hay viajes finalizados recientes</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Gráficos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Gráfico de comportamiento del diesel -->
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
                    <div class="mb-4">
                        <h3 class="text-base font-bold text-white bg-green-700 px-4 py-2 rounded-t-lg -mx-6 -mt-6 mb-6">
                            COMPORTAMIENTO DEL DIESEL
                        </h3>
                    </div>
                    <div class="h-64 flex items-center justify-center text-neutral-400">
                        <div class="text-center">
                            
                            <p class="mt-2 text-sm">Gráfico de líneas - Consumo mensual</p>
                            <p class="text-xs text-neutral-400">2019, 2020, 2021</p>
                        </div>
                    </div>
                </div>

               
            </div>
        </div>
    </div>
</x-app-layout>
