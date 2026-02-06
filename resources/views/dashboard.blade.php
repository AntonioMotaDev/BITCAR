<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h2 fw-bold text-dark mb-0">
                CONTROL DE VIAJES
            </h2>
        </div>
    </x-slot>

    <div class="container-fluid py-4 px-4">
        <div class="row">
            <div class="col-lg-4">
                <!-- Viajes en Curso -->
                <div class="card card-custom border-0 shadow-sm mb-4 trip-list">
                        <div class="card-body p-4 trip-list">
                        @forelse($activeTrips as $trip)
                            <div class="card mb-3 card-custom">
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                        <div class="position-relative d-inline-block " style="width: 180px; min-width: 180px;">
                                            <!-- Primer círculo -->
                                            <div class="rounded-circle circle-prim p-2 position-relative z-1" 
                                                style="width: 100px; height: 100px;">
                                                <div class="d-flex justify-content-center align-items-center h-100">
                                                    <i class="bi bi-car-front text-white w-100 h-100 d-flex align-items-center justify-content-center" 
                                                        style="font-size: 3.5rem;"></i>
                                                </div>
                                            </div>
                                            
                                            <!-- Segundo círculo superpuesto -->
                                            <div class="rounded-circle circle-sec p-2 position-absolute top-0 start-0 z-2" 
                                                style="width: 100px; height: 100px; transform: translate(70px);">
                                                <div class="d-flex justify-content-center align-items-center h-100">
                                                    <i class="bi bi-person text-white w-100 h-100 d-flex align-items-center justify-content-center" 
                                                        style="font-size: 3.5rem;"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h5 class="fw-bold text-terr mb-1">Viaje Activo</h5>
                                            </div>
                                            <div class="small text-prim">
                                                <p class="mb-1">
                                                    <i class="bi bi-clock me-1"></i>
                                                    Inicio: {{ $trip->start_time->format('H:i') }}
                                                </p>
                                                <p class="mb-1">
                                                    <i class="bi bi-person me-1"></i>
                                                    {{ $trip->user->name }}
                                                </p>
                                                <p class="mb-1">
                                                    <i class="bi bi-truck me-1"></i>
                                                    {{ $trip->vehicle->brand }} {{ $trip->vehicle->model }} {{ $trip->vehicle->color }}
                                                </p>
                                                <p class="mb-1">
                                                    <i class="bi bi-card-text me-1"></i>
                                                    {{ $trip->vehicle->license_plate}}
                                                </p>
                                                <p class="mb-1">
                                                      <i class="bi bi-geo-alt me-1"></i>
                                                      Ver Ubicación
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bi bi-truck text-muted fs-2 mb-3"></i>
                                <p class="text-muted mb-0">No hay viajes en curso</p>
                            </div>
                        @endforelse
                    </div>    
                </div>

                <!-- Viajes Finalizados -->
                <div class="card card-custom border-0 shadow-sm mb-4 trip-list">
                        <div class="card-body p-4 trip-list">
                        @forelse($recentLogs->take(5) as $log)
                            <div class="card mb-3 card-custom">
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                        <div class="position-relative d-inline-block " style="width: 180px; min-width: 180px;">
                                            <!-- Primer círculo -->
                                            <div class="rounded-circle circle-prim p-2 position-relative z-1" 
                                                style="width: 100px; height: 100px;">
                                                <div class="d-flex justify-content-center align-items-center h-100">
                                                    <i class="bi bi-car-front text-white w-100 h-100 d-flex align-items-center justify-content-center" 
                                                        style="font-size: 3.5rem;"></i>
                                                </div>
                                            </div>
                                            
                                            <!-- Segundo círculo superpuesto -->
                                            <div class="rounded-circle circle-sec p-2 position-absolute top-0 start-0 z-2" 
                                                style="width: 100px; height: 100px; transform: translate(70px);">
                                                <div class="d-flex justify-content-center align-items-center h-100">
                                                    <i class="bi bi-person text-white w-100 h-100 d-flex align-items-center justify-content-center" 
                                                        style="font-size: 3.5rem;"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h5 class="fw-bold text-gr-dark mb-1">Viaje Finalizado</h5>
                                            </div>
                                            <div class="small text-prim">
                                                <p class="mb-1">
                                                    <i class="bi bi-clock me-1"></i>
                                                    Inicio: {{ $trip->start_time->format('H:i') }}
                                                </p>
                                                <p class="mb-1">
                                                    <i class="bi bi-person me-1"></i>
                                                    {{ $trip->user->name }}
                                                </p>
                                                <p class="mb-1">
                                                    <i class="bi bi-truck me-1"></i>
                                                    {{ $trip->vehicle->brand }} {{ $trip->vehicle->model }} {{ $trip->vehicle->color }}
                                                </p>
                                                <p class="mb-1">
                                                    <i class="bi bi-card-text me-1"></i>
                                                    {{ $trip->vehicle->license_plate}}
                                                </p>
                                                <p class="mb-1">
                                                      <i class="bi bi-geo-alt me-1"></i>
                                                      Ver Ubicación
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bi bi-truck text-muted fs-2 mb-3"></i>
                                <p class="text-muted mb-0">No hay viajes finalizados</p>
                            </div>
                        @endforelse
                    </div>    
                </div>
            </div>

            <div class="col-lg-8">
                <!-- Confirmación de Viaje (si hay viaje activo) -->
                @if($activeTrips->count() > 0)
                    @php $trip = $activeTrips->first(); @endphp
                    <div class="card card-custom border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-start">
                                        <div class="position-relative d-inline-block " style="width: 180px; min-width: 180px;">
                                            <!-- Primer círculo -->
                                            <div class="rounded-circle circle-prim p-2 position-relative z-1" 
                                                style="width: 100px; height: 100px;">
                                                <div class="d-flex justify-content-center align-items-center h-100">
                                                    <i class="bi bi-car-front text-white w-100 h-100 d-flex align-items-center justify-content-center" 
                                                        style="font-size: 3.5rem;"></i>
                                                </div>
                                            </div>
                                            
                                            <!-- Segundo círculo superpuesto -->
                                            <div class="rounded-circle circle-sec p-2 position-absolute top-0 start-0 z-2" 
                                                style="width: 100px; height: 100px; transform: translate(70px);">
                                                <div class="d-flex justify-content-center align-items-center h-100">
                                                    <i class="bi bi-person text-white w-100 h-100 d-flex align-items-center justify-content-center" 
                                                        style="font-size: 3.5rem;"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="fw-bold mb-2" style="color:#AEC44C;">¿Confirma Inicio de Viaje?</h4>
                                            <div class="row text-muted">
                                                <div class="col-12">
                                                    <p class="mb-2">
                                                        <i class="bi bi-person me-2"></i>
                                                        <strong>Operador:</strong>
                                                        {{ $trip->user->name }}
                                                    </p>
                                                    <p class="mb-2">
                                                        <i class="bi bi-truck me-2"></i>
                                                        <strong>Unidad:</strong>
                                                        {{ $trip->vehicle->license_plate }} {{$trip->vehicle->brand }}  {{ $trip->vehicle->model }} {{$trip->vehicle->color }}
                                                    </p>
                                                    <div class="d-flex">
                                                        <p class="mb-2">
                                                            <i class="bi bi-journal-text me-2"></i>
                                                            <strong>Ver Bitácora</strong>
                                                            <i class="bi bi-eye me-2"></i>
                                                        </p>
                                                        <small>Fecha Inicio: {{ $trip->start_time->format('d/m/Y') }}</small> 
                                                        <small>Hora Inicio: {{ $trip->start_time->format('H:i') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div>
                                        <button class="btn btn-succ">
                                            <i class="bi bi-check me-2"></i>Aceptar
                                        </button>
                                        <button class="btn btn-error">
                                            <i class="bi bi-x me-2"></i>Rechazar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Gráfico de Comportamiento del Diesel -->
                <div class="card card-custom border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="bg-light rounded p-4" style="height: 250px;">
                                    <div class="d-flex justify-content-center align-items-center h-100">
                                        <div class="text-center">
                                            <i class="bi bi-bar-chart-line fs-1 text-muted mb-3"></i>
                                            <p class="text-muted mb-1">Gráfico de líneas - Consumo mensual</p>
                                            <p class="text-muted small">2019, 2020, 2021</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <h6 class="fw-bold text-dark mb-3">Estadísticas</h6>
                                    <div class="mb-3">
                                        <p class="text-muted small mb-1">Consumo promedio</p>
                                        <h4 class="fw-bold text-prim">15.2 L/100km</h4>
                                    </div>
                                    <div class="mb-3">
                                        <p class="text-muted small mb-1">Mejor rendimiento</p>
                                        <h4 class="fw-bold text-success">12.8 L/100km</h4>
                                    </div>
                                    <div class="mb-3">
                                        <p class="text-muted small mb-1">Peor rendimiento</p>
                                        <h4 class="fw-bold text-danger">18.5 L/100km</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-12 py-4">

                <!-- Registros -->
                <div class="card card-custom border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-2">Registros</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="bg-light rounded p-4 shadow-sm mb-4">
                                    <div class="row g-4">
                                        <!-- Usuario -->
                                        <div class="col-xl-3 col-lg-6">
                                            <div class="form-group">
                                                <label for="userSelect" class="form-label fw-semibold mb-2">
                                                    <i class="bi bi-person me-1"></i>Usuario
                                                </label>
                                                <select class="form-select" id="userSelect" name="user_id">
                                                    <option value="">Todos los usuarios</option>
                                                    @foreach($users as $user)
                                                        <option value="{{ $user->id }}">
                                                            {{ $user->name }} ({{ $user->role }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <!-- Vehículo -->
                                        <div class="col-xl-3 col-lg-6">
                                            <div class="form-group">
                                                <label for="vehicleSelect" class="form-label fw-semibold mb-2">
                                                    <i class="bi bi-truck me-1"></i>Vehículo
                                                </label>
                                                <select class="form-select" id="vehicleSelect" name="vehicle_id">
                                                    <option value="">Todos los vehículos</option>
                                                    @foreach($vehicles as $vehicle)
                                                        <option value="{{ $vehicle->id }}">
                                                            {{ $vehicle->brand }} {{ $vehicle->model }} 
                                                            <small class="text-muted">({{ $vehicle->license_plate }})</small>
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <!-- Fecha -->
                                        <div class="col-xl-2 col-lg-6">
                                            <div class="form-group">
                                                <label for="dateFilter" class="form-label fw-semibold mb-2">
                                                    <i class="bi bi-calendar me-1"></i>Fecha
                                                </label>
                                                <div class="input-group">
                                                    <input type="date" class="form-control" 
                                                        id="dateFilter" name="date"
                                                        placeholder="Seleccionar fecha">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Tipo de Bitácora -->
                                        <div class="col-xl-2 col-lg-6">
                                            <div class="form-group">
                                                <label for="logTypeSelect" class="form-label fw-semibold mb-2">
                                                    <i class="bi bi-journal-text me-1"></i>Tipo de Bitácora
                                                </label>
                                                <select class="form-select" id="logTypeSelect" name="log_type">
                                                    <option value="">Todos los tipos</option>
                                                    <option value="mantenimiento">Mantenimiento</option>
                                                    <option value="reparacion">Reparación</option>
                                                    <option value="inspeccion">Inspección</option>
                                                    <option value="combustible">Combustible</option>
                                                    <option value="incidente">Incidente</option>
                                                    <option value="viaje">Viaje</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <!-- Botones de Acción -->
                                        <div class="col-xl-2 col-lg-12">
                                            <div class="d-flex flex-column h-100">
                                                <label class="form-label fw-semibold mb-2 invisible">Acciones</label>
                                                <div class="d-flex gap-2 mt-auto">
                                                    <button type="button" class="btn btn-create" id="filterBtn">
                                                        <i class="bi bi-funnel me-2"></i>Filtrar
                                                    </button>
                                                    <button type="button" class="btn btn-cancel" id="clearBtn" title="Limpiar filtros">
                                                        <i class="bi bi-x me-2"></i> Limpiar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 py-2">
                                <div class="bg-light rounded p-4" style="height: 250px;">
                                    <div class="d-flex justify-content-center align-items-center h-100">
                                        <div class="text-center">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>   
        </div>
    </div>
</x-app-layout>