<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-neutral-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Message -->
        <div class="bg-white rounded-lg border border-neutral-200 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-neutral-900">Bienvenido, {{ auth()->user()->name }}</h3>
                    <p class="text-sm text-neutral-500 mt-1">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
                </div>
                <div class="px-4 py-2 bg-neutral-100 rounded-lg">
                    <span class="text-sm font-medium text-neutral-700">{{ ucfirst(auth()->user()->role) }}</span>
                </div>
            </div>
        </div>

        <!-- Main Stats -->
        {{-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6"> --}}
        <div class="">
            <div class="row flex mb-6 gap-6 justify-between">
                <!--  Hay que crear clases de estilos para reusar y hacer mas delgados los html  -->

                <div class="stats-card">
                    <p class="stat-title">Vehículos</p>
                    <p class="text-3xl font-bold text-neutral-900">{{ $stats['total_vehicles'] }}</p>
                </div>

                <div class="bg-white rounded-lg border border-neutral-200 p-6">
                    <div class="flex items-center">
                        <div class="ml-4">
                            <p class="text-xs font-medium text-neutral-500 uppercase">Activos</p>
                            <p class="text-3xl font-bold text-green-600">{{ $stats['active_vehicles'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-neutral-200 p-6">
                    <div class="flex items-center">
                        <div class="ml-4">
                            <p class="text-xs font-medium text-neutral-500 uppercase">Operadores</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $stats['total_operators'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-neutral-200 p-6">
                    <div class="flex items-center">
                        <div class="ml-4">
                            <p class="text-xs font-medium text-neutral-500 uppercase">Viajes Activos</p>
                            <p class="text-3xl font-bold text-purple-600">{{ $stats['active_trips'] }}</p>
                        </div>
                    </div>
                </div>

            </div>

            

            <div class="bg-white rounded-lg border border-neutral-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-12 w-12 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-neutral-500 uppercase">Registros Hoy</p>
                        <p class="text-3xl font-bold text-amber-600">{{ $stats['today_logs'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Active Trips -->
            <div class="bg-white rounded-lg border border-neutral-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-lg font-semibold text-neutral-900">Viajes Activos</h3>
                </div>
                <div class="divide-y divide-neutral-200">
                    @forelse($activeTrips as $trip)
                        <div class="p-4 hover:bg-neutral-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-neutral-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                                        </svg>
                                        <span class="font-medium text-neutral-900">{{ $trip->vehicle->license_plate }}</span>
                                    </div>
                                    <div class="flex items-center mt-1">
                                        <svg class="w-4 h-4 text-neutral-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span class="text-sm text-neutral-500">{{ $trip->user->name }}</span>
                                    </div>
                                    <div class="flex items-center mt-1">
                                        <svg class="w-4 h-4 text-neutral-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-xs text-neutral-500">{{ $trip->start_time->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"/>
                                    </svg>
                                    En curso
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <svg class="mx-auto h-6 w-6 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <p class="mt-2 text-sm text-neutral-500">No hay viajes activos</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Logs -->
            <div class="bg-white rounded-lg border border-neutral-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-lg font-semibold text-neutral-900">Bitácoras Recientes</h3>
                </div>
                <div class="divide-y divide-neutral-200">
                    @forelse($recentLogs as $log)
                        <div class="p-4 hover:bg-neutral-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-neutral-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                                        </svg>
                                        <span class="font-medium text-neutral-900">{{ $log->vehicle->license_plate }}</span>
                                    </div>
                                    <div class="flex items-center mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $log->type === 'entrada' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                            {{ ucfirst($log->type) }}
                                        </span>
                                        <span class="ml-2 text-xs text-neutral-500">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                                @if($log->incidents->count() > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ $log->incidents->count() }} incidentes
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <svg class="mx-auto h-6 w-6 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="mt-2 text-sm text-neutral-500">No hay registros recientes</p>
                        </div>
                    @endforelse
                </div>
                @if($recentLogs->count() > 0)
                    <div class="px-6 py-3 bg-neutral-50 border-t border-neutral-200">
                        <a href="{{ route('vehicle-logs.index') }}" class="text-sm font-medium text-neutral-700 hover:text-neutral-900">
                            Ver todas las bitácoras →
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
