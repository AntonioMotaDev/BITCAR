<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h2 fw-bold text-dark mb-0">
                GESTIONAR UNIDADES VEHICULARES
            </h2>
            <div>
                <button class="btn btn-add">
                    <i class="bi bi-plus-lg"></i> Crear Nueva Unidad
                </button>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4 px-4">
        <!-- Contenedor principal con espacio entre columnas -->
        <div class="row"> 
            <!-- Información de la unidad -->
            <div class="col-lg-4"> 
                <!-- Tarjeta Principal de la Unidad -->
                <div class="card card-custom border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <!-- Encabezado con avatar -->
                        <div class="d-flex align-items-start mb-4">
                            <div class="avatar-large me-4 flex-shrink-0">
                                <i class="bi bi-car-front fs-1 text-white"></i>
                            </div>
                            
                            <div class="flex-grow-1">
                                <h1 class="h4 fw-bold text-dark mb-1 vehicle-brand">
                                    {{ $vehicles->first()->brand ?? 'Seleccione una unidad' }}
                                </h1>
                                <p class="h6 text-secondary mb-0 vehicle-model">
                                    {{ $vehicles->first()->model ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <!-- Información de la Unidad -->
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <p class="info-label">Año</p>
                                        <p class="info-value vehicle-year">{{ $vehicles->first()->year ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <p class="info-label">Color</p>
                                        <p class="info-value vehicle-color">{{ $vehicles->first()->color ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <p class="info-label">Tipo</p>
                                        <p class="info-value vehicle-type">{{ $vehicles->first()->type ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <p class="info-label">Placa</p>
                                        <p class="info-value vehicle-license_plate">{{ $vehicles->first()->license_plate ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <p class="info-label">VIN</p>
                                        <p class="info-value vehicle-vin">{{ $vehicles->first()->vin ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <p class="info-label">Kilometraje</p>
                                        <p class="info-value vehicle-mileage">{{ $vehicles->first()->mileage ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <p class="info-label">Combustible</p>
                                        <p class="info-value vehicle-fuel_capacity">{{ $vehicles->first()->fuel_capacity ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <p class="info-label">Estado</p>
                                        <p class="info-value vehicle-status">{{ $vehicles->first()->status ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botón Editar -->
                        <div class="position-relative" style="min-height: 60px;" >
                            <button class="btn btn-edit position-absolute bottom-0 end-0 d-flex align-items-center px-4 py-2">
                                <i class="bi bi-pencil-square me-2"></i>
                                Editar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Documentos -->
                <div class="card card-custom border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h5 fw-bold text-dark mb-3">Documentos de la Unidad</h3>
                        <div class="document-list vehicle-documents">
                            @if($vehicles->first()->documents && $vehicles->first()->documents->count() > 0)
                                @foreach($vehicles->first()->documents as $document)
                                <div class="document-item mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="document-icon-small bg-primary bg-opacity-10 me-3">
                                            <i class="bi bi-file-text-fill text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="fw-medium text-dark mb-0 small">{{ $document->file_name }}</p>
                                            <p class="text-muted small mb-0">
                                                {{ $document->expiration_date ? 'Vence: ' . $document->expiration_date->format('d/m/Y') : 'Sin fecha' }}
                                            </p>
                                        </div>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-download"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="text-center py-3">
                                    <i class="bi bi-file-earmark-x fs-1 text-muted"></i>
                                    <p class="text-muted small mt-2">No hay documentos disponibles</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usuarios Registrados -->
            <div class="col-lg-8">
                <div>
                    <div class="card-body">
                        <!-- Barra de búsqueda -->
                        <div class="row mb-4">
                            <div class="search-container">
                                    <div class="input-group search-box  rounded-pill overflow-hidden">
                                        <span class="input-group-text bg-white border-end-0">
                                        </span>
                                        <input type="text" class="form-control border-start-0" placeholder="Buscar usuario..." aria-label="Buscar usuario">
                                        <button class="btn  bg-white"  type="button">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                            </div>
                            <div class="col-12 py-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h2 class="h3 fw-bold text-dark mb-0">Usuarios Registrados</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Lista de Usuarios -->
                        <div class="table-responsive card card-custom">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr class="table-light">
                                        <th scope="col" class="ps-3">Marca</th>
                                        <th scope="col">Modelo</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Año</th>
                                        <th scope="col">Placa</th>
                                        <th scope="col" class="text-end pe-3">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vehicles as $vehicle)
                                    <tr class="vehicle-row {{ $loop->first ? 'active' : '' }}"
                                        data-vehicle-id="{{ $vehicle->id }}"
                                        data-vehicle-brand="{{ $vehicle->brand }}"
                                        data-vehicle-model="{{ $vehicle->model }}"
                                        data-vehicle-color="{{ $vehicle->color }}"
                                        data-vehicle-type="{{ $vehicle->type }}"
                                        data-vehicle-year="{{ $vehicle->year }}"
                                        data-vehicle-mileage="{{ $vehicle->mileage }}"
                                        data-vehicle-fuel_capacity="{{ $vehicle->fuel_capacity }}"
                                        data-vehicle-license_plate="{{ $vehicle->license_plate }}"
                                        data-vehicle-vin="{{ $vehicle->vin }}"
                                        data-vehicle-status="{{ $vehicle->status }}"
                                        data-vehicle-documents='@json($vehicle->documents ?? [])'>
                                        
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-small bg-primary bg-opacity-10 me-3">
                                                    <i class="bi bi-car-front text-primary"></i>
                                                </div>
                                                <div>
                                                    <h3 class="h6 fw-bold text-dark mb-0">{{ $vehicle->brand }}</h3>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $vehicle->model }}</td>
                                        <td>{{ $vehicle->type }}</td>
                                        <td>{{ $vehicle->year }}</td>
                                        <td>{{ $vehicle->license_plate }}                                  </td>
                                        <td class="text-end pe-3">
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button class="btn btn-eye btn-view-vehicle" 
                                                        data-vehicle-id="{{ $vehicle->id }}"
                                                        title="Ver detalles">
                                                    <i class="bi bi-eye"></i> Ver
                                                </button>
                                                <button class="btn btn-edit" 
                                                        data-vehicle-id="{{ $vehicle->id }}"
                                                        title="Editar unidad">
                                                    <i class="bi bi-pencil-square"></i> Editar
                                                </button>
                                                <button class="btn btn-delete" 
                                                        data-vehicle-id="{{ $vehicle->id }}"
                                                        title="Eliminar unidad">
                                                    <i class="bi bi-trash"></i> Eliminar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <div class="text-muted small">
                                Mostrando {{ $vehicles->count() }} de {{ $vehicles->total() ?? 24 }} unidades
                            </div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Siguiente</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Script de gestión de unidades vehiculares cargado');
            
            // 1. FUNCIÓN PARA CARGAR INFORMACIÓN DE LA UNIDAD
            function loadVehicleInfo(vehicleRow) {
                if (!vehicleRow) {
                    console.error('La fila de la unidad no existe');
                    return;
                }
                
                // Obtener datos de los atributos data-*
                const vehicleId = vehicleRow.getAttribute('data-vehicle-id');
                const vehicleBrand = vehicleRow.getAttribute('data-vehicle-brand');
                const vehicleModel = vehicleRow.getAttribute('data-vehicle-model');
                const vehicleColor = vehicleRow.getAttribute('data-vehicle-color');
                const vehicleType = vehicleRow.getAttribute('data-vehicle-type');
                const vehicleYear = vehicleRow.getAttribute('data-vehicle-year');
                const vehicleMileage = vehicleRow.getAttribute('data-vehicle-mileage');
                const vehicleFuelCapacity = vehicleRow.getAttribute('data-vehicle-fuel_capacity');
                const vehicleLicensePlate = vehicleRow.getAttribute('data-vehicle-license_plate');
                const vehicleVin = vehicleRow.getAttribute('data-vehicle-vin');
                const vehicleStatus = vehicleRow.getAttribute('data-vehicle-status');
                
                console.log('Cargando unidad:', vehicleBrand, vehicleModel);
                
                // Actualizar información principal
                updateElementText('.vehicle-brand', vehicleBrand || 'N/A');
                updateElementText('.vehicle-model', vehicleModel || 'N/A');
                updateElementText('.vehicle-color', vehicleColor || 'N/A');
                updateElementText('.vehicle-type', vehicleType || 'N/A');
                updateElementText('.vehicle-year', vehicleYear || 'N/A');
                updateElementText('.vehicle-mileage', vehicleMileage || 'N/A');
                updateElementText('.vehicle-fuel_capacity', vehicleFuelCapacity || 'N/A');   
                updateElementText('.vehicle-license_plate', vehicleLicensePlate || 'N/A');
                updateElementText('.vehicle-vin', vehicleVin || 'N/A');
                updateElementText('.vehicle-status', vehicleStatus || 'N/A');

                // Actualizar documentos
                try {
                    const vehicleDocuments = JSON.parse(vehicleRow.getAttribute('data-vehicle-documents') || '[]');
                    updateVehicleDocuments(vehicleDocuments);
                } catch (e) {
                    console.error('Error al parsear documentos:', e);
                    updateVehicleDocuments([]);
                }
                
                // Resaltar fila activa
                highlightActiveRow(vehicleId);
                
                // Actualizar URL (opcional)
                history.pushState(null, '', `?vehicle=${vehicleId}`);
            }
            
            // 2. FUNCIÓN AUXILIAR PARA ACTUALIZAR TEXTO
            function updateElementText(selector, text) {
                const element = document.querySelector(selector);
                if (element) {
                    element.textContent = text;
                    return true;
                } else {
                    console.warn(`Elemento no encontrado: ${selector}`);
                    return false;
                }
            }
            
            // 3. ACTUALIZAR DOCUMENTOS
            function updateVehicleDocuments(documents) {
                const container = document.querySelector('.vehicle-documents');
                if (!container) {
                    console.warn('Contenedor de documentos no encontrado');
                    return;
                }
                
                if (documents && documents.length > 0) {
                    container.innerHTML = documents.map(doc => {
                        const fileName = doc.file_name || doc.name || 'Documento';
                        let expirationDate = 'Sin fecha';
                        
                        if (doc.expiration_date) {
                            if (typeof doc.expiration_date === 'string') {
                                expirationDate = doc.expiration_date;
                            } else if (doc.expiration_date && typeof doc.expiration_date === 'object') {
                                // Intentar formatear la fecha
                                try {
                                    const date = new Date(doc.expiration_date);
                                    if (!isNaN(date.getTime())) {
                                        expirationDate = date.toLocaleDateString('es-ES');
                                    }
                                } catch (e) {
                                    console.warn('No se pudo formatear la fecha:', e);
                                }
                            }
                        }
                        
                        return `
                            <div class="document-item mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="document-icon-small bg-primary bg-opacity-10 me-3">
                                        <i class="bi bi-file-text-fill text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="fw-medium text-dark mb-0 small">${fileName}</p>
                                        <p class="text-muted small mb-0">
                                            ${expirationDate !== 'Sin fecha' ? 'Vence: ' + expirationDate : 'Sin fecha'}
                                        </p>
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary" title="Descargar">
                                        <i class="bi bi-download"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                    }).join('');
                } else {
                    container.innerHTML = `
                        <div class="text-center py-3">
                            <i class="bi bi-file-earmark-x fs-1 text-muted"></i>
                            <p class="text-muted small mt-2">No hay documentos disponibles</p>
                        </div>
                    `;
                }
            }
            
            // 4. RESALTAR FILA ACTIVA
            function highlightActiveRow(vehicleId) {
                document.querySelectorAll('.vehicle-row').forEach(row => {
                    row.classList.remove('active');
                });
                
                const activeRow = document.querySelector(`.vehicle-row[data-vehicle-id="${vehicleId}"]`);
                if (activeRow) {
                    activeRow.classList.add('active');
                }
            }
            
            // 5. FILTRAR UNIDADES
            function filterVehicles() {
                const searchTerm = document.getElementById('vehicleSearch').value.toLowerCase();
                const typeFilter = document.getElementById('typeFilter').value;
                const rows = document.querySelectorAll('.vehicle-row');
                let visibleCount = 0;
                
                rows.forEach(row => {
                    const vehicleBrand = row.getAttribute('data-vehicle-brand').toLowerCase();
                    const vehicleModel = row.getAttribute('data-vehicle-model').toLowerCase();
                    const vehicleType = row.getAttribute('data-vehicle-type').toLowerCase();
                    const vehicleLicensePlate = row.getAttribute('data-vehicle-license_plate').toLowerCase();
                    
                    const matchesSearch = searchTerm === '' || 
                        vehicleBrand.includes(searchTerm) || 
                        vehicleModel.includes(searchTerm) ||
                        vehicleLicensePlate.includes(searchTerm);
                    
                    const matchesType = typeFilter === '' || vehicleType === typeFilter;
                    
                    if (matchesSearch && matchesType) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Actualizar contadores
                document.getElementById('vehicleCount').textContent = visibleCount + ' unidades';
                document.getElementById('showingCount').textContent = visibleCount;
            }
            
            // 6. ASIGNAR EVENTOS
            function setupEventListeners() {
                // Eventos para filas de unidades
                document.querySelectorAll('.vehicle-row').forEach(row => {
                    row.addEventListener('click', function(e) {
                        if (!e.target.closest('button')) {
                            loadVehicleInfo(this);
                        }
                    });
                });
                
                // Eventos para botones "Ver"
                document.querySelectorAll('.btn-view-vehicle').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const vehicleRow = this.closest('.vehicle-row');
                        if (vehicleRow) {
                            loadVehicleInfo(vehicleRow);
                        } else {
                            console.error('No se encontró la fila de la unidad');
                        }
                    });
                });
                
                // Eventos para botones de acción
                document.querySelectorAll('.btn-edit').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const vehicleId = this.getAttribute('data-vehicle-id');
                        console.log('Editar unidad:', vehicleId);
                        // Aquí iría la lógica para abrir modal de edición
                    });
                });
                
                document.querySelectorAll('.btn-delete').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const vehicleId = this.getAttribute('data-vehicle-id');
                        if (confirm(`¿Está seguro de eliminar la unidad ${vehicleId}?`)) {
                            console.log('Eliminar unidad:', vehicleId);
                            // Aquí iría la lógica para eliminar
                        }
                    });
                });
                
                // Eventos para búsqueda y filtros
                const vehicleSearch = document.getElementById('vehicleSearch');
                const typeFilter = document.getElementById('typeFilter');
                
                if (vehicleSearch) {
                    vehicleSearch.addEventListener('input', filterVehicles);
                }
                
                if (typeFilter) {
                    typeFilter.addEventListener('change', filterVehicles);
                }
                
                // Evento para botón Crear Nueva Unidad
                document.querySelector('.btn-add').addEventListener('click', function() {
                    console.log('Crear nueva unidad');
                    // Aquí iría la lógica para abrir modal de creación
                });
                
                // Evento para botón Editar en la tarjeta
                document.querySelector('.card-body .btn-edit').addEventListener('click', function(e) {
                    e.preventDefault();
                    const activeRow = document.querySelector('.vehicle-row.active');
                    if (activeRow) {
                        const vehicleId = activeRow.getAttribute('data-vehicle-id');
                        console.log('Editar unidad activa:', vehicleId);
                        // Aquí iría la lógica para abrir modal de edición
                    }
                });
            }
            
            // 7. DEPURACIÓN - Verificar que todo esté configurado correctamente
            function debugSetup() {
                console.log('=== DEPURACIÓN ===');
                console.log('Filas .vehicle-row encontradas:', document.querySelectorAll('.vehicle-row').length);
                console.log('Botones .btn-view-vehicle encontrados:', document.querySelectorAll('.btn-view-vehicle').length);
                
                // Verificar elementos de la tarjeta
                const requiredElements = [
                    '.vehicle-brand',
                    '.vehicle-model', 
                    '.vehicle-color',
                    '.vehicle-type',
                    '.vehicle-year',
                    '.vehicle-mileage',
                    '.vehicle-fuel_capacity',
                    '.vehicle-license_plate',
                    '.vehicle-vin',
                    '.vehicle-status',
                    '.vehicle-documents'
                ];
                
                requiredElements.forEach(selector => {
                    const element = document.querySelector(selector);
                    console.log(`${selector}:`, element ? '✓ ENCONTRADO' : '✗ NO ENCONTRADO');
                });
            }
            
            // 8. INICIALIZACIÓN
            function initialize() {
                console.log('Inicializando gestión de unidades...');
                
                // Depuración
                debugSetup();
                
                // Configurar event listeners
                setupEventListeners();
                
                // Cargar primera unidad si existe
                const firstVehicleRow = document.querySelector('.vehicle-row');
                if (firstVehicleRow) {
                    console.log('Cargando primera unidad...');
                    loadVehicleInfo(firstVehicleRow);
                } else {
                    console.warn('No se encontraron unidades');
                }
                
                // Cargar unidad desde URL si existe
                const urlParams = new URLSearchParams(window.location.search);
                const vehicleIdFromUrl = urlParams.get('vehicle');
                if (vehicleIdFromUrl) {
                    const vehicleRow = document.querySelector(`.vehicle-row[data-vehicle-id="${vehicleIdFromUrl}"]`);
                    if (vehicleRow) {
                        loadVehicleInfo(vehicleRow);
                    }
                }
            }
            
            // Iniciar todo
            initialize();
        });
    </script>
</x-app-layout>