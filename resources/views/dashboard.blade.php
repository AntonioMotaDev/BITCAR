<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h2 fw-bold text-dark mb-0">
                CONTROL DE VIAJES
            </h2>
        </div>
    </x-slot>

    <div class="container-fluid py-0 px-4">
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
                                                    Inicio: {{ $trip->start_time?->format('H:i') ?? 'N/A' }}
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
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative d-inline-block" style="width: 180px; min-width: 180px;">
                                            <!-- Primer círculo -->
                                            <div class="rounded-circle circle-prim p-2 position-relative z-1" 
                                                style="width: 80px; height: 80px;">
                                                <div class="d-flex justify-content-center align-items-center h-100">
                                                    <i class="bi bi-car-front text-white w-100 h-100 d-flex align-items-center justify-content-center" 
                                                        style="font-size: 3.5rem;"></i>
                                                </div>
                                            </div>
                                            
                                            <!-- Segundo círculo superpuesto -->
                                            <div class="rounded-circle circle-sec p-2 position-absolute top-0 start-0 z-2" 
                                                style="width: 80px; height: 80px; transform: translate(70px);">
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
                                                    Inicio: {{ $log->trip?->start_time?->format('d/M') ?? 'N/A' }} {{ $log->trip?->start_time?->format('H:i') ?? $log->created_at->format('H:i') }},
                                                    Fin: {{ $log->trip?->start_time?->format('d/M') ?? 'N/A' }} {{ $log->trip?->end_time?->format('H:i') ?? 'N/A' }}                
                                                </p>
                                                <p class="mb-1">
                                                    <i class="bi bi-person me-1"></i>
                                                    {{ $log->user->name }}
                                                </p>
                                                <p class="mb-1">
                                                    <i class="bi bi-truck me-1"></i>
                                                    {{ $log->vehicle->brand }} {{ $log->vehicle->model }} {{ $log->vehicle->color }}
                                                </p>
                                                {{-- <p class="mb-1">
                                                      <i class="bi bi-geo-alt me-1"></i>
                                                      Ver Ubicación
                                                </p> --}}
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

                <!-- Confirmación de Viaje (si hay solicitud pendiente) -->
                @if($pendingTripLogs->count() > 0)
                    @php $pendingTripLog = $pendingTripLogs->first(); @endphp
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
                                                        {{ $pendingTripLog->user->name }}
                                                    </p>
                                                    <p class="mb-2">
                                                        <i class="bi bi-truck me-2"></i>
                                                        <strong>Unidad:</strong>
                                                        {{ $pendingTripLog->vehicle->license_plate }} {{ $pendingTripLog->vehicle->brand }} {{ $pendingTripLog->vehicle->model }} {{ $pendingTripLog->vehicle->color }}
                                                    </p>
                                                    <div class="d-flex">
                                                        <p class="mb-2">
                                                            <i class="bi bi-journal-text me-2"></i>
                                                            <strong>Ver Bitácora</strong>
                                                            <i class="bi bi-eye me-2"></i>
                                                        </p>
                                                        <small>Fecha Solicitud: {{ $pendingTripLog->created_at->format('d/m/Y') }}</small> 
                                                        <small>Hora Solicitud: {{ $pendingTripLog->created_at->format('H:i') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div>
                                        @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
                                            <form method="POST" action="{{ route('trips.approve', $pendingTripLog) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-succ">
                                                    <i class="bi bi-check me-2"></i>Aceptar
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('trips.reject', $pendingTripLog) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-cancel">
                                                    <i class="bi bi-x me-2"></i>Rechazar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif


                <!-- Mapa de Ubicaciones  -->
                <div class="card card-custom border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="fw-bold mb-0">
                                <i class="bi bi-map me-2"></i>Ubicacion de Unidades 
                            </h4>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="refreshMapBtn">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Actualizar
                                </button>
                                <span class="badge bg-success" id="activeTripsCount">0 activos</span>
                            </div>
                        </div>
                        <div id="map" style="width: 100%; height: 400px; border-radius: 8px; background-color: #f0f0f0;">
                            <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                <i class="bi bi-map fs-3 me-2"></i>
                                <span>Cargando mapa...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Registros -->
                <div class="card card-custom border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-2">Registros</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <form method="GET" action="{{ route('dashboard') }}" class="bg-light rounded p-4 shadow-sm mb-4">
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
                                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
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
                                                        <option value="{{ $vehicle->id }}" {{ request('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                                            {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->license_plate }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <!-- Fecha -->
                                        <div class="col-xl-3 col-lg-6">
                                            <div class="form-group">
                                                <label for="dateFilter" class="form-label fw-semibold mb-2">
                                                    <i class="bi bi-calendar me-1"></i>Fecha
                                                </label>    
                                                <div class="input-group">
                                                    <input type="date" class="form-control" 
                                                        id="dateFilter" name="date"
                                                        value="{{ request('date') }}"
                                                        placeholder="Seleccionar fecha">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Tipo de Bitácora -->
                                        <div class="col-xl-3 col-lg-6">
                                            <div class="form-group">
                                                <label for="logTypeSelect" class="form-label fw-semibold mb-2">
                                                    <i class="bi bi-journal-text me-1"></i>Tipo de Bitácora
                                                </label>
                                                <select class="form-select" id="logTypeSelect" name="log_type">
                                                    <option value="">Todos los tipos</option>
                                                    <option value="entry" {{ request('log_type') == 'entry' ? 'selected' : '' }}>Entrada</option>
                                                    <option value="exit" {{ request('log_type') == 'exit' ? 'selected' : '' }}>Salida</option>
                                                    <option value="trip_start" {{ request('log_type') == 'trip_start' ? 'selected' : '' }}>Inicio de viaje</option>
                                                    <option value="trip_checkpoint" {{ request('log_type') == 'trip_checkpoint' ? 'selected' : '' }}>Checkpoint de viaje</option>
                                                    <option value="trip_end" {{ request('log_type') == 'trip_end' ? 'selected' : '' }}>Fin de viaje</option>
                                                    <option value="fuel" {{ request('log_type') == 'fuel' ? 'selected' : '' }}>Carga de combustible</option>
                                                    <option value="incident" {{ request('log_type') == 'incident' ? 'selected' : '' }}>Incidencia</option>
                                                    <option value="maintenance" {{ request('log_type') == 'maintenance' ? 'selected' : '' }}>Mantenimiento</option>
                                                    <option value="other" {{ request('log_type') == 'other' ? 'selected' : '' }}>Otro</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-4 justify-content-end">
                                        <!-- Botones de Acción -->
                                        <div class="col-xl-3 col-lg-12">
                                            <div class="d-flex flex-column h-100">
                                                <label class="form-label fw-semibold mb-2 invisible">Acciones</label>
                                                <div class="d-flex gap-2 mt-auto">
                                                    <button type="submit" class="btn btn-create" id="filterBtn">
                                                        <i class="bi bi-funnel me-2"></i>Filtrar
                                                    </button>
                                                    <a href="{{ route('dashboard') }}" class="btn btn-cancel" id="clearBtn" title="Limpiar filtros">
                                                        <i class="bi bi-x me-2"></i> Limpiar
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Tabla de Registros -->
                            <div class="col-md-12 py-2">
                                <div class="table-responsive bg-light rounded p-3">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr class="table-light">
                                                <th scope="col">Fecha</th>
                                                <th scope="col">Usuario</th>
                                                <th scope="col">Vehículo</th>
                                                <th scope="col">Tipo</th>
                                                <th scope="col">Detalle</th>
                                                <th scope="col" class="text-end">Respuestas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $logTypeLabels = [
                                                    'entrada' => 'Entrada',
                                                    'salida' => 'Salida',
                                                    'trip_start' => 'Inicio de viaje',
                                                    'trip_end' => 'Fin de viaje',
                                                    'trip_checkpoint' => 'Checkpoint de viaje',
                                                    'fuel' => 'Carga de combustible',
                                                    'incident' => 'Incidencia',
                                                    'maintenance' => 'Mantenimiento',
                                                    'exit' => 'Salida',
                                                    'entry' => 'Entrada',
                                                ];
                                            @endphp
                                            @forelse($recentLogs as $log)
                                                @php
                                                    // Agrupar fotos por checklist_item_id
                                                    $photosByItem = $log->vehicleLogPhotos->groupBy('checklist_item_id');
                                                    
                                                    $logItems = $log->vehicleLogItems->map(function ($item) use ($photosByItem) {
                                                        $itemPhotos = $photosByItem->get($item->checklist_item_id, collect())->map(function ($photo) {
                                                            return [
                                                                'file_path' => $photo->file_path,
                                                                'description' => $photo->description,
                                                            ];
                                                        })->values();
                                                        
                                                        return [
                                                            'question' => $item->checklistItem?->label ?? 'Pregunta',
                                                            'boolean_answer' => $item->boolean_answer,
                                                            'text_answer' => $item->text_answer,
                                                            'numeric_answer' => $item->numeric_answer,
                                                            'photos' => $itemPhotos,
                                                        ];
                                                    })->values();

                                                    // Fotos generales (sin checklist_item_id)
                                                    $logPhotos = $log->vehicleLogPhotos->filter(function ($photo) {
                                                        return $photo->checklist_item_id === null;
                                                    })->map(function ($photo) {
                                                        return [
                                                            'file_path' => $photo->file_path,
                                                            'description' => $photo->description,
                                                        ];
                                                    })->values();

                                                    $logSignatures = $log->signatures->map(function ($signature) {
                                                        return [
                                                            'checklist_item_id' => $signature->checklist_item_id,
                                                            'checklist_item' => $signature->checklistItem?->label,
                                                            'signature_data' => $signature->signature_data,
                                                            'signer_name' => $signature->signer_name,
                                                            'signed_at' => optional($signature->signed_at)->format('d/m/Y H:i'),
                                                        ];
                                                    })->values();
                                                @endphp
                                                <tr>
                                                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>{{ $log->user->name }}</td>
                                                    <td>{{ $log->vehicle->brand }} {{ $log->vehicle->model }} ({{ $log->vehicle->license_plate }})</td>
                                                    <td>{{ $logTypeLabels[$log->type] ?? \Illuminate\Support\Str::headline((string) $log->type) }}</td>
                                                    <td>{{ $log->notes ?? $log->description ?? '-' }}</td>
                                                    <td class="text-end">
                                                        <button type="button" class="btn btn-eye btn-sm view-log-answers"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#logAnswersModal"
                                                            data-log-id="{{ $log->id }}"
                                                            data-log-endpoint="{{ route('dashboard.logs.modal-data', $log) }}"
                                                            data-log-date="{{ $log->created_at->format('d/m/Y H:i') }}"
                                                            data-log-user="{{ $log->user->name }}"
                                                            data-log-vehicle="{{ $log->vehicle->brand }} {{ $log->vehicle->model }} ({{ $log->vehicle->license_plate }})"
                                                            data-log-detail="{{ $log->notes ?? $log->description ?? '-' }}"
                                                            data-log-items='@json($logItems)'
                                                            data-log-photos='@json($logPhotos)'
                                                            data-log-signatures='@json($logSignatures)'>
                                                            <i class="bi bi-eye"></i> Ver
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted py-4">No hay bitácoras registradas</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
        </div>
    </div>
</x-app-layout>

<!-- Modal Respuestas de Bitácora -->
<div class="modal fade" id="logAnswersModal" tabindex="-1" aria-labelledby="logAnswersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logAnswersModalLabel">
                    <i class="bi bi-journal-text me-2"></i>Respuestas de Bitácora
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card card-custom border-0 shadow-sm mb-3">
                    <div class="card-body py-3">
                        <div class="row g-3 small">
                            <div class="col-md-4">
                                <div class="text-muted text-uppercase fw-semibold">Fecha</div>
                                <div class="fw-semibold text-prim" id="logAnswersDate">-</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted text-uppercase fw-semibold">Usuario</div>
                                <div class="fw-semibold text-prim" id="logAnswersUser">-</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted text-uppercase fw-semibold">Vehiculo</div>
                                <div class="fw-semibold text-prim" id="logAnswersVehicle">-</div>
                            </div>
                            <div class="col-md-12">
                                <div class="text-muted text-uppercase fw-semibold">Detalle</div>
                                <div class="fw-semibold text-prim" id="logAnswersDetail">-</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-custom border-0 shadow-sm mb-3">
                    <div class="card-body p-0">
                        <div class="px-3 pt-3">
                            <h6 class="fw-bold text-prim mb-3"><i class="bi bi-list-check me-2"></i>Respuestas</h6>
                        </div>
                        <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="small fw-bold text-prim ps-3">Pregunta</th>
                                <th class="small fw-bold text-prim pe-3">Respuesta</th>
                            </tr>
                        </thead>
                        <tbody id="logAnswersBody">
                            <tr>
                                <td colspan="2" class="text-center text-muted py-4">Selecciona una bitácora</td>
                            </tr>
                        </tbody>
                    </table>
                        </div>
                    </div>
                </div>

                {{-- <div class="card card-custom border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold text-prim mb-3"><i class="bi bi-camera me-2"></i>Fotos</h6>
                        <div id="logPhotos" class="small text-muted">Sin fotos</div>
                    </div>
                </div> --}}

                <div class="card card-custom border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold text-prim mb-3"><i class="bi bi-pen me-2"></i>Firmas</h6>
                        <div id="logSignatures" class="small text-muted">Sin firmas</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                    <i class="bi bi-x me-1"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="logMediaPreviewModal" tabindex="-1" aria-labelledby="logMediaPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logMediaPreviewModalLabel">Vista previa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="logMediaPreviewImage" src="" alt="Vista previa" style="max-width: 100%; max-height: 70vh; border-radius: 10px;" />
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const logAnswersBody = document.getElementById('logAnswersBody');
    const logAnswersDate = document.getElementById('logAnswersDate');
    const logAnswersUser = document.getElementById('logAnswersUser');
    const logAnswersVehicle = document.getElementById('logAnswersVehicle');
    const logAnswersDetail = document.getElementById('logAnswersDetail');
    const logPhotos = document.getElementById('logPhotos');
    const logSignatures = document.getElementById('logSignatures');
    const logMediaPreviewModalEl = document.getElementById('logMediaPreviewModal');
    const logMediaPreviewImage = document.getElementById('logMediaPreviewImage');
    const logMediaPreviewModal = logMediaPreviewModalEl ? new bootstrap.Modal(logMediaPreviewModalEl) : null;

    function safeParseJson(value, fallback = []) {
        try {
            return JSON.parse(value || '[]');
        } catch (error) {
            return fallback;
        }
    }

    function resolveStorageUrl(path) {
        if (!path) return '';
        const rawValue = String(path).trim();
        if (!rawValue) return '';

        if (rawValue.startsWith('http://') || rawValue.startsWith('https://') || rawValue.startsWith('data:image')) {
            return rawValue;
        }

        // Soporta firmas guardadas como base64 puro (sin prefijo data:image/...)
        const normalizedBase64 = rawValue.replace(/\s+/g, '');
        if (/^[A-Za-z0-9+/=]+$/.test(normalizedBase64) && normalizedBase64.length > 120) {
            return `data:image/png;base64,${normalizedBase64}`;
        }

        const normalizedPath = rawValue.replace(/^\/+/, '');
        return `/storage/${normalizedPath}`;
    }

    function renderThumbnailCard(url, title) {
        return `
            <button type="button" class="btn p-0 border-0 bg-transparent text-start log-media-preview" data-media-src="${url}" title="${title}">
                <img src="${url}" alt="${title}" loading="lazy" decoding="async" class="img-thumbnail" style="width: 110px; height: 85px; object-fit: cover; border-radius: 10px;" />
            </button>
        `;
    }

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value ?? '';
        return div.innerHTML;
    }

    function bindPreviewClicks() {
        document.querySelectorAll('.log-media-preview').forEach(button => {
            button.addEventListener('click', function () {
                const mediaSrc = this.getAttribute('data-media-src');
                if (!mediaSrc || !logMediaPreviewImage || !logMediaPreviewModal) {
                    return;
                }

                logMediaPreviewImage.src = mediaSrc;
                logMediaPreviewModal.show();
            });
        });
    }

    document.querySelectorAll('.view-log-answers').forEach(button => {
        button.addEventListener('click', async function () {
            const endpoint = this.getAttribute('data-log-endpoint') || '';
            let items = safeParseJson(this.getAttribute('data-log-items'));
            let photos = safeParseJson(this.getAttribute('data-log-photos'));
            let signatures = safeParseJson(this.getAttribute('data-log-signatures'));
            logAnswersDate.textContent = this.getAttribute('data-log-date') || '-';
            logAnswersUser.textContent = this.getAttribute('data-log-user') || '-';
            logAnswersVehicle.textContent = this.getAttribute('data-log-vehicle') || '-';
            if (logAnswersDetail) {
                logAnswersDetail.textContent = this.getAttribute('data-log-detail') || '-';
            }

            if (endpoint) {
                try {
                    const response = await fetch(endpoint, {
                        headers: {
                            'Accept': 'application/json',
                        }
                    });

                    if (response.ok) {
                        const result = await response.json();
                        const data = result?.data || {};

                        if (Array.isArray(data.items)) items = data.items;
                        if (Array.isArray(data.photos)) photos = data.photos;
                        if (Array.isArray(data.signatures)) signatures = data.signatures;
                        if (logAnswersDetail && typeof data.detail === 'string') {
                            logAnswersDetail.textContent = data.detail || '-';
                        }
                    }
                } catch (error) {
                    console.error('No se pudieron cargar los detalles de la bitacora:', error);
                }
            }

            if (!items.length) {
                logAnswersBody.innerHTML = '<tr><td colspan="2" class="text-center text-muted py-4">Sin respuestas registradas</td></tr>';
            } else {
                logAnswersBody.innerHTML = items.map(item => {
                const answer = item.text_answer ?? (item.numeric_answer ?? (item.boolean_answer === null ? '-' : (item.boolean_answer ? 'Sí' : 'No')));
                
                // Renderizar fotos del item si existen
                let photosHtml = '';
                if (item.photos && item.photos.length > 0) {
                    photosHtml = '<div class="mt-2 d-flex flex-wrap gap-2">' + item.photos.map(photo => {
                        const desc = photo.description ? ` - ${photo.description}` : '';
                        const url = resolveStorageUrl(photo.file_path);
                        if (!url) return '';

                        return `
                            <div>
                                ${renderThumbnailCard(url, '')}
                                <div class="small text-muted mt-1">${escapeHtml(desc ? desc.replace(/^ - /, '') : '')}</div>
                            </div>
                        `;
                    }).join('') + '</div>';
                }
                
                return `
                    <tr>
                        <td class="small ps-3">${escapeHtml(item.question ?? 'Pregunta')}</td>
                        <td class="small pe-3">${escapeHtml(answer ?? '-')} ${photosHtml}</td>
                    </tr>
                `;
            }).join('');
            }

            if (logPhotos) {
                if (!photos.length) {
                    logPhotos.textContent = 'Sin fotos';
                } else {
                    logPhotos.innerHTML = `<div class="d-flex flex-wrap gap-3">${photos.map(photo => {
                        const desc = photo.description ? ` - ${photo.description}` : '';
                        const url = resolveStorageUrl(photo.file_path);
                        if (!url) return '';

                        return `
                            <div>
                                ${renderThumbnailCard(url, 'Foto de bitacora')}
                                <div class="small text-muted mt-1">${escapeHtml(desc ? desc.replace(/^ - /, '') : 'Foto')}</div>
                            </div>
                        `;
                    }).join('')}</div>`;
                }
            }

            if (!signatures.length) {
                logSignatures.textContent = 'Sin firmas';
            } else {
                logSignatures.innerHTML = `<div class="d-flex flex-wrap gap-3">${signatures.map(signature => {
                    const signedAt = signature.signed_at ? ` (${signature.signed_at})` : '';
                    const signerName = signature.signer_name ?? 'Firmante';
                    const itemLabel = signature.checklist_item ? `${signature.checklist_item}` : 'Firma';
                    const signatureUrl = resolveStorageUrl(signature.signature_data || '');
                    if (!signatureUrl) {
                        return `<div class="small text-muted">${escapeHtml(signerName)}${escapeHtml(signedAt)} - ${escapeHtml(itemLabel)}</div>`;
                    }

                    return `
                        <div>
                            ${renderThumbnailCard(signatureUrl, 'Firma')}
                            <div class="small text-muted mt-1">${escapeHtml(itemLabel)}</div>
                            <div class="small text-muted">${escapeHtml(signerName)}${escapeHtml(signedAt)}</div>
                        </div>
                    `;
                }).join('')}</div>`;
            }

            bindPreviewClicks();
        });
    });
});

// Google Maps - Tracking de Viajes Activos (Última ubicación)
let map;
const markers = new Map();
const DEFAULT_CENTER = { lat: 22.1565, lng: -100.9855 }; // San Luis Potosi, Mexico
const DEFAULT_ZOOM = 12;
const MAX_AUTO_ZOOM = 16;

async function initMap() {
    const mapElement = document.getElementById('map');
    
    map = new google.maps.Map(mapElement, {
        zoom: DEFAULT_ZOOM,
        center: DEFAULT_CENTER,
        // mapTypeId: google.maps.MapTypeId.SATELLITE.
        mapTypeControl: true,
        fullscreenControl: true,
        streetViewControl: false,
        styles: [
            {
                featureType: "poi",
                elementType: "labels",
                stylers: [{ visibility: "on" }]
            }
        ]
    });

    // Carga inicial con ajuste de vista.
    loadActiveTrips(true);
}

async function loadActiveTrips(shouldFitBounds = false) {
    try {
        const response = await fetch('/api/v1/tracking/active-trips', {
            headers: {
                'Accept': 'application/json',
            }
        });

        if (!response.ok) {
            console.error('Error fetching active trips:', response.status, response.statusText);
            return;
        }

        const result = await response.json();
        const trips = result.data || [];

        // Actualizar contador
        document.getElementById('activeTripsCount').textContent = `${trips.length} activo${trips.length !== 1 ? 's' : ''}`;

        // Limpiar marcadores antiguos
        const currentTripIds = new Set(trips.map(t => t.id));
        for (const [tripId, marker] of markers.entries()) {
            if (!currentTripIds.has(tripId)) {
                marker.setMap(null);
                markers.delete(tripId);
            }
        }

        // Agregar/actualizar marcadores
        const bounds = new google.maps.LatLngBounds();
        
        trips.forEach(trip => {
            if (!trip.last_location) return;

            const position = {
                lat: trip.last_location.latitude,
                lng: trip.last_location.longitude
            };

            if (markers.has(trip.id)) {
                // Actualizar marcador existente
                markers.get(trip.id).setPosition(position);
            } else {
                // Crear nuevo marcador
                const marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    title: `${trip.user.name} - ${trip.vehicle.license_plate}`,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 10,
                        fillColor: '#AEC44C',
                        fillOpacity: 1,
                        strokeColor: '#fff',
                        strokeWeight: 2
                    }
                });

                // Info window
                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="padding: 10px; font-size: 12px;">
                            <strong>${trip.user.name}</strong><br>
                            <i class="bi bi-truck"></i> ${trip.vehicle.brand} ${trip.vehicle.model} (${trip.vehicle.license_plate})<br>
                            <i class="bi bi-clock"></i> Inicio: ${trip.start_time}<br>
                            <i class="bi bi-geo-alt"></i> ${trip.last_location.latitude.toFixed(6)}, ${trip.last_location.longitude.toFixed(6)}<br>
                            <small>Actualizado: ${trip.last_location.recorded_at_human}</small>
                        </div>
                    `,
                    maxWidth: 300
                });

                marker.addListener('click', () => {
                    // Cerrar otros info windows
                    document.querySelectorAll('.gm-ui-hover-effect').forEach(el => {
                        if (el !== event?.target) {
                            el.style.display = 'none';
                        }
                    });
                    infoWindow.open(map, marker);
                });

                markers.set(trip.id, marker);
            }

            bounds.extend(position);
        });

        // Ajustar vista solo cuando se requiere (carga inicial).
        if (trips.length > 0 && shouldFitBounds) {
            map.fitBounds(bounds, 80);

            // Evita que el ajuste automático deje el mapa demasiado cerca.
            google.maps.event.addListenerOnce(map, 'bounds_changed', () => {
                if (map.getZoom() > MAX_AUTO_ZOOM) {
                    map.setZoom(MAX_AUTO_ZOOM);
                }
            });
        } else if (trips.length === 0) {
            map.setCenter(DEFAULT_CENTER);
            map.setZoom(DEFAULT_ZOOM);
        }

    } catch (error) {
        console.error('Error loading active trips:', error);
    }
}

// Inicializar mapa cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function() {
    const refreshMapBtn = document.getElementById('refreshMapBtn');

    refreshMapBtn?.addEventListener('click', async function() {
        refreshMapBtn.disabled = true;
        refreshMapBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Actualizando';

        try {
            await loadActiveTrips(false);
        } finally {
            refreshMapBtn.disabled = false;
            refreshMapBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i>Actualizar';
        }
    });

    // Esperar a que Google Maps esté cargado
    if (typeof google !== 'undefined' && google.maps) {
        initMap();
    } else {
        console.error('Google Maps API no está cargada');
    }
});
</script>

<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=marker"></script>
