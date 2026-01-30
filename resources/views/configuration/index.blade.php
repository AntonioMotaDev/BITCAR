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
                                <button class="btn btn-edit d-flex justify-content-center px-4 py-2 edit-checklist-btn"
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
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Bitácoras Registradas -->
            <div class="col-lg-8">
                <!-- Barra de búsqueda -->
                        <div class="mb-4">
                            <div class="input-group search-box rounded-pill overflow-hidden">
                                <span class="input-group-text bg-white border-end-0">
                                </span>
                                <input type="text" class="form-control border-start-0" 
                                       placeholder="Buscar bitácora por nombre o descripción..." 
                                       aria-label="Buscar bitácora"
                                       id="searchChecklist">
                                <button class="btn btn-light" type="button" id="searchBtn">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            <div class="col-12 py-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h2 class="h3 fw-bold text-secondary mb-0">Bitácoras Creadas</h2>
                                </div>
                            </div>
                        </div>
                <div class="card card-custom border-0 shadow-sm h-90">
                    <div class="card-body p-4 d-flex flex-column">
                        <!-- Contenedor horizontal con scroll ajustado -->
                        <div class="flex-grow-1 d-flex flex-column">
                            <div class="checklists-horizontal-container felx-group-1 d-flex align-items-center justify-content-center">
                                <div class="checklists-scroll-wrapper" id="checklistsContainer">
                                    <!-- Cards de Checklists -->
                                     @if($checklists->isEmpty())
                                        <div class="text-center py-4">
                                            <i class="bi bi-clipboard-x fs-1 text-muted"></i>
                                            <p class="text-muted small mt-2">No hay bitácoras creadas aún</p>
                                        </div>
                                    @else
                                        @foreach($checklists as $checklist)
                                            @php
                                                // Preparar los items para JSON
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
                                            @endphp
                                            
                                            <div class="checklist-card @if($loop->first) active @endif" 
                                                data-checklist-id="{{ $checklist->id }}"
                                                data-checklist-name="{{ $checklist->name }}"
                                                data-checklist-description="{{ $checklist->description }}"
                                                data-checklist-status="{{ $checklist->is_active }}"
                                                data-checklist-type="{{ $checklist->type }}"
                                                data-checklist-created-at="{{ $checklist->created_at->format('d/m/Y') }}"
                                                data-checklist-items="{{ $itemsData->toJson() }}">
                                                <div class="card-body d-flex flex-column">
                                                    <!-- Header -->
                                                    <div class="mb-3">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <div class="checklist-icon bg-primary bg-opacity-10 me-3">
                                                                <i class="bi bi-clipboard-check text-prim"></i>
                                                            </div>
                                                            <div>
                                                                <h5 class="h5 fw-bold text-prim mb-0">{{ $checklist->name }}</h5>
                                                                <div class="d-flex align-items-center mt-1">
                                                                    <span class="badge 
                                                                        @if($checklist->is_active == 1)
                                                                            bg-success bg-opacity-10 text-success
                                                                        @else
                                                                            bg-danger bg-opacity-10 text-danger
                                                                        @endif
                                                                        border 
                                                                        @if($checklist->is_active == 1)
                                                                            border-success border-opacity-25
                                                                        @else
                                                                            border-danger border-opacity-25
                                                                        @endif small">
                                                                        {{ $checklist->is_active == 1 ? 'Activo' : 'Inactivo' }}
                                                                    </span>
                                                                    <span class="text-prim small ms-2">
                                                                        <i class="bi bi-calendar me-1"></i>
                                                                        {{ $checklist->created_at->format('d/m/Y') }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Tipo -->
                                                    <div class="mb-3">
                                                        <p class="small text-prim checklist-type">
                                                            <i class="bi bi-tag me-1"></i>
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
                                                                
                                                                echo $typeLabels[$checklist->type] ?? $checklist->type;
                                                            @endphp
                                                        </p>
                                                    </div>
                                                    
                                                    <!-- Descripción -->
                                                    <div class="mb-3">
                                                        <p class="small text-prim checklist-description-preview">
                                                            {{ Str::limit($checklist->description, 100) }}
                                                        </p>
                                                    </div>
                                                    
                                                    <!-- Items count -->
                                                    <div class="mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-list-check text-primary me-2"></i>
                                                            <span class="fw-medium small">{{ $checklist->checklistItems->count() }} items</span>
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                    
                                                    <!-- Botones -->
                                                    <div class="mt-auto pt-3 border-top">
                                                        <div class="d-flex gap-2">
                                                            <button class="btn btn-eye btn-view-checklist flex-grow-1"
                                                                    data-checklist-id="{{ $checklist->id }}">
                                                                <i class="bi bi-eye me-1"></i> Ver
                                                            </button>
                                                            <button class="btn btn-edit flex-grow-1 edit-checklist-btn"
                                                                    data-checklist-id="{{ $checklist->id }}"
                                                                    data-checklist-name="{{ $checklist->name }}"
                                                                    data-checklist-description="{{ $checklist->description }}"
                                                                    data-checklist-status="{{ $checklist->is_active }}"
                                                                    data-checklist-type="{{ $checklist->type }}"
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#editChecklistModal">
                                                                <i class="bi bi-pencil-square me-1"></i> Editar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Indicador de scroll -->
                            <div class="text-center mt-3 pt-2">
                                <small class="text-muted">
                                    <i class="bi bi-arrow-left-right me-1"></i>
                                    Desliza para ver más bitácoras
                                </small>
                            </div>
                            <!-- Paginación -->
                            <div class="d-flex justify-content-end mt-2 pt-3 border-top">
                                {{ $checklists->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
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
        function loadChecklistInfo(checklistCard) {
            const checklistId = checklistCard.getAttribute('data-checklist-id');
            const checklistName = checklistCard.getAttribute('data-checklist-name');
            const checklistDescription = checklistCard.getAttribute('data-checklist-description');
            const checklistStatus = checklistCard.getAttribute('data-checklist-status');
            const checklistType = checklistCard.getAttribute('data-checklist-type');
            const checklistCreatedAt = checklistCard.getAttribute('data-checklist-created-at');
            const checklistItems = JSON.parse(checklistCard.getAttribute('data-checklist-items') || '[]');
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
            
            // Resaltar card activa
            highlightActiveCard(checklistId);
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
        
        // resaltar la card seleccionada
        function highlightActiveCard(checklistId) {
            document.querySelectorAll('.checklist-card').forEach(card => {
                card.classList.remove('active');
            });
            
            // Agregar clase 'active' a la card seleccionada
            const activeCard = document.querySelector(`.checklist-card[data-checklist-id="${checklistId}"]`);
            if (activeCard) {
                activeCard.classList.add('active');
                
                // Scroll horizontal para hacer visible la card activa
                const container = document.querySelector('.checklists-horizontal-container');
                if (container) {
                    activeCard.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'nearest',
                        inline: 'center' 
                    });
                }
            }
        }
        // búsqueda de bitácoras
        function searchChecklists(query) {
            const cards = document.querySelectorAll('.checklist-card');
            let visibleCount = 0;
            const searchTerm = query.toLowerCase().trim();
            
            cards.forEach(card => {
                const name = card.getAttribute('data-checklist-name').toLowerCase();
                const description = card.getAttribute('data-checklist-description').toLowerCase();
                
                if (searchTerm === '' || name.includes(searchTerm) || description.includes(searchTerm)) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Si no hay resultados, mostrar mensaje en panel izquierdo
            if (visibleCount === 0 && searchTerm !== '') {
                showNoResultsMessage();
            } else if (visibleCount > 0) {
                // Cargar la primera bitácora visible
                const firstVisibleCard = document.querySelector('.checklist-card[style="display: flex;"], .checklist-card:not([style])');
                if (firstVisibleCard) {
                    loadChecklistInfo(firstVisibleCard);
                }
            }
        }

        function showNoResultsMessage() {
            document.querySelector('.checklist-name').textContent = 'No se encontraron bitácoras';
            document.querySelector('.checklist-id').textContent = '';
            document.querySelector('.checklist-description').textContent = 'Intente con otros términos de búsqueda';
            document.querySelector('.checklist-created-at').textContent = 'N/A';
            
            const statusBadge = document.querySelector('.checklist-status-badge');
            statusBadge.textContent = 'N/A';
            statusBadge.className = 'checklist-status-badge badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25';
            
            updateChecklistItems([]);
        }
        
        // Eventos para botones "Ver"
        document.querySelectorAll('.btn-view-checklist').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const checklistId = this.getAttribute('data-checklist-id');
                const checklistCard = document.querySelector(`.checklist-card[data-checklist-id="${checklistId}"]`);
                if (checklistCard) {
                    loadChecklistInfo(checklistCard);
                }
            });
        });
        
        // Evento para búsqueda
        const searchInput = document.getElementById('searchChecklist');
        const searchBtn = document.getElementById('searchBtn');
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                searchChecklists(this.value);
            });
            
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchChecklists(this.value);
                }
            });
        }
        
        if (searchBtn) {
            searchBtn.addEventListener('click', function() {
                searchChecklists(searchInput.value);
            });
        }
        
        // Inicializar con la primer bitácora
        const firstChecklistCard = document.querySelector('.checklist-card');
        if (firstChecklistCard) {
            // Cargar items del primer checklist
            const initialItems = JSON.parse(firstChecklistCard.getAttribute('data-checklist-items') || '[]');
            updateChecklistItems(initialItems);
            firstChecklistCard.classList.add('active');
        }
    });
    </script>
</x-app-layout>