<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h2 fw-bold text-dark mb-0">
                GESTIONAR BITÁCORAS
            </h2>
            <div>
                <button type="button" class="btn btn-add" data-bs-toggle="modal" data-bs-target="#createChecklistModal">
                    <i class="bi bi-plus-lg"></i> Crear Nueva Bitácora
                </button>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4 px-4">
        <!-- Contenedor principal -->
        <div class="row g-4">
            <!-- Información de la Bitácora -->
            <div class="col-lg-4">
                <div class="card card-custom border-0 shadow-sm h-100">
                    <div class="card-body p-4 d-flex flex-column">
                        <!-- Encabezado con icono y nombre -->
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar-large me-3 bg-opacity-10 flex-shrink-0">
                                <i class="bi bi-clipboard-check-fill fs-1 "></i>
                            </div>
                            <div class="flex-grow-1">
                                <h1 class="h4 fw-bold text-prim mb-1 checklist-name">
                                    {{ $checklists->first()->name ?? 'Seleccione una Bitácora' }}
                                </h1>
                                <p class="text-muted small mb-0 checklist-id">
                                    ID: {{ $checklists->first()->id ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <!-- Información básica -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <p class="info-label mb-1">Estado</p>
                                @if($checklists->isNotEmpty())
                                    <span class="checklist-status-badge badge 
                                        @if($checklists->first()->is_active == 1) 
                                            bg-success bg-opacity-10 text-success border border-success border-opacity-25
                                        @else
                                            bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25
                                        @endif">
                                        {{ $checklists->first()->is_active == 1 ? 'Activo' : 'Inactivo' }}
                                    </span>
                                @else
                                    <span class="checklist-status-badge badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                        N/A
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <p class="info-label mb-1">Fecha Creación</p>
                                <p class="info-value mb-0 checklist-created-at">
                                    @if($checklists->isNotEmpty())
                                        {{ $checklists->first()->created_at->format('d/m/Y') }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="info-label mb-1">Tipo</p>
                                <p class="info-value mb-0 checklist-type">
                                    @if($checklists->isNotEmpty())
                                        @php
                                            $typeLabels = [
                                                'entry' => 'Entrada',
                                                'exit' => 'Salida',
                                                'trip_start' => 'Inicio de viaje',
                                                'trip_checkpoint' => 'Punto en el viaje',
                                                'trip_end' => 'Fin de viaje',
                                                'fuel' => 'Combustible',
                                                'incident' => 'Incidente',
                                                'maintenance' => 'Mantenimiento', 
                                                'other' => 'Otro'
                                            ];
                                            
                                            echo $typeLabels[$checklists->first()->type] ?? $checklists->first()->type;
                                        @endphp
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <p class="info-label mb-2">Descripción</p>
                            <div class="p-3 bg-light rounded">
                                <p class="mb-0 text-prim checklist-description">
                                    {{ $checklists->first()->description ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <!-- Separador -->
                        <hr class="my-4">

                        <!-- Items de la Bitácora -->
                        <div class="flex-grow-1">
                            <h3 class="h5 fw-bold text-prim mb-3">Items de la Bitácora</h3>
                            <div class="checklist-items-container" style="max-height: 300px; overflow-y: auto;">
                                <!-- Los items se cargarán dinámicamente aquí -->
                                <div class="text-center py-4">
                                    <i class="bi bi-clipboard-data fs-1 text-muted"></i>
                                    <p class="text-muted small mt-2">
                                        @if($checklists->isNotEmpty() && $checklists->first()->checklistItems->isNotEmpty())
                                            No hay items configurados en esta bitácora
                                        @else
                                            Seleccione una bitácora para ver sus items
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Botón Editar al final -->
                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                             @php
                                // Crear itemsData directamente para el botón
                                $firstChecklist = $checklists->first();
                                $itemsForButton = '[]';
                                
                                if ($firstChecklist) {
                                    $itemsArray = $firstChecklist->checklistItems->map(function($item) {
                                        return [
                                            'id' => $item->id,
                                            'label' => $item->label ?? $item->name,
                                            'type' => $item->type,
                                            'description' => $item->description,
                                            'required' => $item->required,
                                            'order' => $item->order,
                                        ];
                                    })->toArray();
                                    
                                    $itemsForButton = json_encode($itemsArray);
                                }
                            @endphp
                            @if($checklists->isNotEmpty())
                                <div class="d-flex gap-2">
                                    <button class="btn btn-edit flex-grow-1 d-flex justify-content-center px-4 py-2 edit-checklist-btn"
                                        id="editChecklistBtn"
                                        data-checklist-id="{{ $firstChecklist->id ?? '' }}"
                                        data-checklist-name="{{ $firstChecklist->name ?? '' }}"
                                        data-checklist-description="{{ $firstChecklist->description ?? '' }}"
                                        data-checklist-status="{{ $firstChecklist->is_active ?? '' }}"
                                        data-checklist-type="{{ $firstChecklist->type ?? ''}}"
                                        data-checklist-items="{{ $itemsForButton }}"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editChecklistModal">
                                        <i class="bi bi-pencil-square me-2"></i>
                                        Editar
                                    </button>
                                    <form action="{{ route('checklists.duplicate', $firstChecklist->id ?? 0) }}" method="POST" class="flex-shrink-0" onsubmit="return confirm('¿Desea duplicar esta bitácora?');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-edit d-flex align-items-center px-3 py-2" title="Duplicar bitácora">
                                            <i class="bi bi-files me-2"></i>
                                            Duplicar
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Bitácoras Registradas -->
            <div class="col-lg-8">
                <!-- Filtros -->
                <div class="card card-custom border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <form method="GET" action="{{ route('checklists.index') }}" class="row g-3">
                            <div class="col-md-4">
                                <label for="filterType" class="form-label small fw-medium text-prim">Tipo</label>
                                <select name="type" id="filterType" class="form-select form-select-sm">
                                    <option value="">Todos</option>
                                    <option value="entry" {{ request('type') == 'entry' ? 'selected' : '' }}>Entrada</option>
                                    <option value="exit" {{ request('type') == 'exit' ? 'selected' : '' }}>Salida</option>
                                    <option value="trip_start" {{ request('type') == 'trip_start' ? 'selected' : '' }}>Inicio de viaje</option>
                                    <option value="trip_checkpoint" {{ request('type') == 'trip_checkpoint' ? 'selected' : '' }}>Punto en el viaje</option>
                                    <option value="trip_end" {{ request('type') == 'trip_end' ? 'selected' : '' }}>Fin de viaje</option>
                                    <option value="fuel" {{ request('type') == 'fuel' ? 'selected' : '' }}>Combustible</option>
                                    <option value="incident" {{ request('type') == 'incident' ? 'selected' : '' }}>Incidente</option>
                                    <option value="maintenance" {{ request('type') == 'maintenance' ? 'selected' : '' }}>Mantenimiento</option>
                                    <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Otro</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="filterStatus" class="form-label small fw-medium text-prim">Estado</label>
                                <select name="status" id="filterStatus" class="form-select form-select-sm">
                                    <option value="">Todos</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Activo</option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="searchChecklist" class="form-label small fw-medium text-prim">Búsqueda</label>
                                <input type="text" name="search" id="searchChecklist" class="form-control form-control-sm" 
                                       placeholder="Nombre o descripción..." 
                                       value="{{ request('search') }}">
                            </div>

                            <div class="col-12 d-flex gap-2 justify-content-end">
                                <a href="{{ route('checklists.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Limpiar
                                </a>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="bi bi-search me-1"></i> Filtrar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabla de Bitácoras -->
                <div class="card card-custom border-0 shadow-sm">
                    <div class="card-body p-4">
                        @if($checklists->isEmpty())
                            <div class="text-center py-5">
                                <i class="bi bi-clipboard-x fs-1 text-muted mb-3"></i>
                                <p class="text-muted mb-0">No hay bitácoras creadas aún</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="fw-medium text-prim">Nombre</th>
                                            <th class="fw-medium text-prim">Tipo</th>
                                            <th class="fw-medium text-prim">Estado</th>
                                            <th class="fw-medium text-prim">Items</th>
                                            <th class="fw-medium text-prim">Creada</th>
                                            <th class="fw-medium text-prim text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($checklists as $checklist)
                                            @php
                                                $itemsData = $checklist->checklistItems->map(function($item) {
                                                    return [
                                                        'id' => $item->id,
                                                        'label' => $item->label ?? $item->name,
                                                        'name' => $item->name,
                                                        'type' => $item->type,
                                                        'description' => $item->description,
                                                        'options' => $item->options,
                                                        'order' => $item->order,
                                                        'required' => $item->required,
                                                        'created_at' => $item->created_at,
                                                        'updated_at' => $item->updated_at,
                                                    ];
                                                });

                                                $typeLabels = [
                                                    'entry' => 'Entrada',
                                                    'exit' => 'Salida',
                                                    'trip_start' => 'Inicio de viaje',
                                                    'trip_checkpoint' => 'Punto en el viaje',
                                                    'trip_end' => 'Fin de viaje',
                                                    'fuel' => 'Combustible',
                                                    'incident' => 'Incidente',
                                                    'maintenance' => 'Mantenimiento', 
                                                    'other' => 'Otro'
                                                ];
                                            @endphp
                                            <tr class="checklist-row cursor-pointer" 
                                                data-checklist-id="{{ $checklist->id }}"
                                                data-checklist-name="{{ $checklist->name }}"
                                                data-checklist-description="{{ $checklist->description }}"
                                                data-checklist-status="{{ $checklist->is_active }}"
                                                data-checklist-type="{{ $checklist->type }}"
                                                data-checklist-created-at="{{ $checklist->created_at->format('d/m/Y') }}"
                                                data-checklist-items="{{ $itemsData->toJson() }}"
                                                style="cursor: pointer;">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-clipboard-check text-primary me-2"></i>
                                                        <div>
                                                            <div class="fw-medium text-prim">{{ $checklist->name }}</div>
                                                            <small class="text-muted">{{ Str::limit($checklist->description, 50) }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <small class="text-prim">{{ $typeLabels[$checklist->type] ?? $checklist->type }}</small>
                                                </td>
                                                <td>
                                                    @if($checklist->is_active == 1)
                                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 small">Activo</span>
                                                    @else
                                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 small">Inactivo</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small class="text-prim fw-medium">
                                                        <i class="bi bi-list-check text-primary me-1"></i>
                                                        {{ $checklist->checklistItems->count() }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ $checklist->created_at->format('d/m/Y') }}</small>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        <button class="btn btn-eye btn-view-checklist btn-sm" 
                                                                data-checklist-id="{{ $checklist->id }}"
                                                                title="Ver">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                        <button class="btn btn-edit edit-checklist-btn btn-sm"
                                                                data-checklist-id="{{ $checklist->id }}"
                                                                data-checklist-name="{{ $checklist->name }}"
                                                                data-checklist-description="{{ $checklist->description }}"
                                                                data-checklist-status="{{ $checklist->is_active }}"
                                                                data-checklist-type="{{ $checklist->type }}"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#editChecklistModal"
                                                                title="Editar">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                        <form action="{{ route('checklists.duplicate', $checklist->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Desea duplicar esta bitácora?');">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-edit btn-sm" title="Duplicar">
                                                                <i class="bi bi-files"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Paginación -->
                            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                                {{ $checklists->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('configuration.checklist-create-modal')
    @if($checklists->isNotEmpty())
        @include('configuration.checklist-edit-modal')
    @endif
  

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cargar la información
        function loadChecklistInfo(checklistElement) {
            const checklistId = checklistElement.getAttribute('data-checklist-id');
            const checklistName = checklistElement.getAttribute('data-checklist-name');
            const checklistDescription = checklistElement.getAttribute('data-checklist-description');
            const checklistStatus = checklistElement.getAttribute('data-checklist-status');
            const checklistType = checklistElement.getAttribute('data-checklist-type');
            const checklistCreatedAt = checklistElement.getAttribute('data-checklist-created-at');
            const checklistItems = JSON.parse(checklistElement.getAttribute('data-checklist-items') || '[]');
            const type = getTypeLabel(checklistType);
            
            // Actualizar información principal en el panel izquierdo
            document.querySelector('.checklist-name').textContent = checklistName;
            document.querySelector('.checklist-id').textContent = `ID: ${checklistId}`;
            document.querySelector('.checklist-description').textContent = checklistDescription || 'Sin descripción';
            document.querySelector('.checklist-created-at').textContent = checklistCreatedAt;
            document.querySelector('.checklist-type').textContent = type;
            
            // Actualizar estado con badge
            const statusBadge = document.querySelector('.checklist-status-badge');
            if (checklistStatus === '1') {
                statusBadge.textContent = 'Activo';
                statusBadge.className = 'checklist-status-badge badge bg-success bg-opacity-10 text-success border border-success border-opacity-25';
            } else {
                statusBadge.textContent = 'Inactivo';
                statusBadge.className = 'checklist-status-badge badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25';
            }
            
            // Actualizar items en el panel izquierdo
            updateChecklistItems(checklistItems);
            
            // Actualizar botón de editar
            const editBtn = document.getElementById('editChecklistBtn');
            editBtn.setAttribute('data-checklist-id', checklistId);
            editBtn.setAttribute('data-checklist-name', checklistName);
            editBtn.setAttribute('data-checklist-description', checklistDescription);
            editBtn.setAttribute('data-checklist-status', checklistStatus);
            editBtn.setAttribute('data-checklist-type', checklistType);
            
            // Resaltar fila activa
            highlightActiveRow(checklistId);
        }

        function getTypeLabel(type) {
            const typeLabels = {
                'entry': 'Entrada',
                'exit': 'Salida',
                'trip_start': 'Inicio de viaje',
                'trip_checkpoint': 'Punto en el viaje',
                'trip_end': 'Fin de viaje',
                'fuel': 'Combustible',
                'incident': 'Incidente',
                'maintenance': 'Mantenimiento', 
                'other': 'Otro'
            };
             return typeLabels[type] || type; 
        }

        // Actualizar la información en la card de la izquierda
        function updateChecklistItems(items) {
            const container = document.querySelector('.checklist-items-container');
            
            if (items && items.length > 0) {
                let itemsHTML = `
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless">
                            <tbody>
                `;
                
                items.forEach(item => {
                    // Parsear opciones si existen
                    let optionsText = '';
                    if (item.type === 'boolean' || item.type === 'option') {
                        try {
                            const options = typeof item.options === 'string' 
                                ? JSON.parse(item.options) 
                                : (item.options || []);
                            if (Array.isArray(options) && options.length > 0) {
                                optionsText = `<div class="mt-1"><small class="text-muted">Opciones: ${options.join(', ')}</small></div>`;
                            }
                        } catch (e) {
                            console.error('Error parsing options:', e);
                        }
                    }
                    
                    // Determinar icono según tipo
                    let typeIcon = 'bi-gear';
                    let typeText = item.type;
                    
                    switch(item.type) {
                        case 'text':
                            typeIcon = 'bi-fonts';
                            typeText = 'Texto';
                            break;
                        case 'number':
                            typeIcon = 'bi-123';
                            typeText = 'Numérico';
                            break;
                        case 'boolean':
                            typeIcon = 'bi-toggle-on';
                            typeText = 'Booleano';
                            break;
                        case 'photo':
                            typeIcon = 'bi-camera';
                            typeText = 'Foto';
                            break;
                        case 'signature':
                            typeIcon = 'bi-pen';
                            typeText = 'Firma';
                            break;
                    }
                    
                    itemsHTML += `
                        <tr class="border-bottom">
                            <td style="width: 30%; vertical-align: top;">
                                <strong class="text-dark">${item.label || item.name}</strong>
                                ${item.required ? '<span class="text-danger small ms-1">*</span>' : ''}
                            </td>
                            <td style="width: 40%; vertical-align: top;">
                                ${item.description ? `<div class="text-muted small">${item.description}</div>` : ''}
                                ${optionsText}
                            </td>
                            <td style="width: 30%; vertical-align: top;">
                                <div class="text-muted small">
                                    <i class="bi ${typeIcon} me-1"></i>${typeText}
                                </div>
                                <div class="text-muted small mt-1">
                                    <i class="bi bi-sort-numeric-down me-1"></i>Orden: ${item.order}
                                </div>
                            </td>
                        </tr>
                    `;
                });
                
                itemsHTML += `
                            </tbody>
                        </table>
                    </div>
                `;
                
                container.innerHTML = itemsHTML;
            } else {
                container.innerHTML = `
                    <div class="text-center py-4">
                        <i class="bi bi-clipboard-x fs-1 text-muted"></i>
                        <p class="text-muted small mt-2">No hay items en esta bitácora</p>
                    </div>
                `;
            }
        }
        
        // Resaltar la fila seleccionada
        function highlightActiveRow(checklistId) {
            document.querySelectorAll('.checklist-row').forEach(row => {
                row.classList.remove('table-active');
            });
            
            // Agregar clase 'table-active' a la fila seleccionada
            const activeRow = document.querySelector(`.checklist-row[data-checklist-id="${checklistId}"]`);
            if (activeRow) {
                activeRow.classList.add('table-active');
            }
        }
        
        // Eventos para filas de la tabla
        document.querySelectorAll('.checklist-row').forEach(row => {
            row.addEventListener('click', function(e) {
                // Evitar que se active al hacer clic en botones
                if (e.target.closest('.btn') || e.target.closest('form')) {
                    return;
                }
                loadChecklistInfo(this);
            });
        });

        // Eventos para botones "Ver"
        document.querySelectorAll('.btn-view-checklist').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const checklistId = this.getAttribute('data-checklist-id');
                const checklistRow = document.querySelector(`.checklist-row[data-checklist-id="${checklistId}"]`);
                if (checklistRow) {
                    loadChecklistInfo(checklistRow);
                }
            });
        });
        
        // Inicializar con la primer bitácora
        const firstChecklistRow = document.querySelector('.checklist-row');
        if (firstChecklistRow) {
            loadChecklistInfo(firstChecklistRow);
        }
    });
    </script>
</x-app-layout>