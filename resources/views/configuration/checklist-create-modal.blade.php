<div class="modal fade" id="createChecklistModal" tabindex="-1" aria-labelledby="createChecklistModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createChecklistModalLabel">
                    <i class="bi bi-list-check me-2"></i>Crear Nueva Bitácora
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createChecklistForm" action="{{ route('checklists.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Información de la Bitácora -->
                        <div class="col-md-12">
                            <h6 class="fw-bold text-prim mb-3">
                                <i class="bi bi-card-checklist me-2"></i>Información de la Bitácora
                            </h6>
                            
                            <!-- Nombre de la Bitácora -->
                            <div class="mb-3">
                                <label for="checklist_name" class="form-label text-prim">
                                    <i class="bi bi-card-heading me-1"></i>Nombre de la Bitácora *
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="checklist_name" 
                                       name="name" 
                                       value="{{ old('name') }}"
                                       placeholder="Ej: Checklist Diario de Mantenimiento"
                                       required
                                       autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Descripción de la Bitácora -->
                            <div class="mb-4">
                                <label for="checklist_description" class="form-label text-prim">
                                    <i class="bi bi-text-paragraph me-1"></i>Descripción *
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="checklist_description" 
                                          name="description" 
                                          rows="3"
                                          placeholder="Describe el propósito de esta bitácora..."
                                          required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tipo de la Bitácora -->
                            <div class="mb-4">
                                <label for="checklist_type" class="form-label text-prim">
                                    <i class="bi bi-toggle-on me-1"></i>Tipo *
                                </label>
                                <select class="form-select form-select-sm @error('checklist_type') is-invalid @enderror" id="checklist_type" name="checklist_type" required>
                                    <option value="" disabled selected>Selecciona un tipo</option>
                                        @foreach($typeChecklistOptions as $value => $data)
                                            <option value="{{ $value }}">
                                                {{ $data['label'] }}
                                            </option>
                                        @endforeach
                                </select>
                                @error('checklist_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Sección para agregar items -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="fw-bold text-prim mb-0">
                                        <i class="bi bi-plus-circle me-2"></i>Agregar Items a la Bitácora
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <!-- Formulario para agregar nuevo item -->
                                    <div id="newItemForm" class="border p-3 rounded mb-3 bg-light">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label for="item_label" class="form-label small fw-bold text-prim">
                                                    <i class="bi bi-tag me-1"></i>Nombre del Item *
                                                </label>
                                                <input type="text" 
                                                       class="form-control form-control-sm" 
                                                       id="item_label" 
                                                       placeholder="Ej: Nivel de aceite">
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <label for="item_type" class="form-label small fw-bold text-prim">
                                                    <i class="bi bi-gear me-1"></i>Tipo *
                                                </label>
                                                <select class="form-select form-select-sm" id="item_type">
                                                    <option value="" disabled selected>Selecciona un tipo</option>
                                                    @foreach($typeOptions as $value => $data)
                                                        <option value="{{ $value }}">
                                                            {{ $data['label'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <label for="item_description" class="form-label small fw-bold text-prim">
                                                    <i class="bi bi-text-left me-1"></i>Descripción
                                                </label>
                                                <input type="text" 
                                                       class="form-control form-control-sm" 
                                                       id="item_description" 
                                                       placeholder="Ej: Verificar que esté en nivel óptimo">
                                            </div>
                                            
                                            <!-- Checkbox Obligatorio -->
                                            <div class="col-md-12">
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" 
                                                           type="checkbox" 
                                                           id="item_required" 
                                                           value="1">
                                                    <label class="form-check-label small fw-bold text-prim" for="item_required">
                                                        <i class="bi bi-exclamation-circle me-1"></i>Obligatorio
                                                    </label>
                                                    <small class="text-muted d-block ms-4">
                                                        Marca esta casilla si este item es requerido para completar la bitácora
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Botón para agregar item -->
                                        <div class="mt-3 text-end">
                                            <button type="button" class="btn btn-i" id="addItemBtn">
                                                <i class="bi bi-plus-lg me-1"></i> Agregar Item
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Lista de items agregados -->
                                    <div id="itemsList" class="mt-3">
                                        <h6 class="fw-bold text-prim mb-3">
                                            <i class="bi bi-list-ul me-2"></i>Items Agregados
                                            <span id="itemsCount" class="badge bg-primary ms-2">0</span>
                                        </h6>
                                        
                                        <!-- Mensaje cuando no hay items -->
                                        <div id="noItemsMessage" class="text-center py-4 border rounded">
                                            <i class="bi bi-inbox fs-1 text-muted"></i>
                                            <p class="text-muted mt-2 mb-0">No hay items agregados todavía</p>
                                            <small class="text-muted">Usa el formulario de arriba para agregar items</small>
                                        </div>
                                        
                                        <!-- Contenedor para items dinámicos -->
                                        <div id="itemsContainer" class="list-group" style="display: none;">
                                            <!-- Los items se agregarán aquí dinámicamente -->
                                        </div>
                                        
                                        <!-- Campo oculto para almacenar los items en formato JSON -->
                                        <input type="hidden" name="items" id="itemsJson" value="{{ old('items', '[]') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-create" id="submitBtn">
                        <i class="bi bi-check me-1"></i>Crear Bitácora
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript para el modal -->
<script>
document.addEventListener('DOMContentLoaded', function() {

    let items = [];
    const itemLabelInput = document.getElementById('item_label');
    const itemTypeSelect = document.getElementById('item_type');
    const itemDescriptionInput = document.getElementById('item_description');
    const itemRequiredCheckbox = document.getElementById('item_required');
    const addItemBtn = document.getElementById('addItemBtn');
    const itemsContainer = document.getElementById('itemsContainer');
    const noItemsMessage = document.getElementById('noItemsMessage');
    const itemsCount = document.getElementById('itemsCount');
    const itemsJsonInput = document.getElementById('itemsJson');
    const createChecklistForm = document.getElementById('createChecklistForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Cargar items existentes
    try {
        const existingItems = JSON.parse(itemsJsonInput.value || '[]');
        if (existingItems.length > 0) {
            items = existingItems;
            renderItems();
        }
    } catch (e) {
        console.error('Error parsing existing items:', e);
    }
    
    // Función para agregar un nuevo item
    function addItem() {
        const label = itemLabelInput.value.trim();
        const type = itemTypeSelect.value;
        
        if (!label) {
            alert('Por favor, ingresa el nombre del item');
            itemLabelInput.focus();
            return;
        }
        
        if (!type) {
            alert('Por favor, selecciona un tipo');
            itemTypeSelect.focus();
            return;
        }
        
        // Obtener valor del checkbox 
        const isRequired = itemRequiredCheckbox.checked ? 1 : 0;
        
        // Crear objeto item
        const newItem = {
            id: Date.now(),
            label: label,
            type: type,
            description: itemDescriptionInput.value.trim(),
            is_required: isRequired, 
            order: items.length + 1
        };
        
        // Agregar al array
        items.push(newItem);
        
        // Renderizar items
        renderItems();
        
        // Limpiar formulario
        itemLabelInput.value = '';
        itemTypeSelect.value = '';
        itemDescriptionInput.value = '';
        itemRequiredCheckbox.checked = false; // Resetear checkbox
        
        // Enfocar en el primer campo
        itemLabelInput.focus();
    }
    
    // 4. Función para renderizar todos los items
    function renderItems() {
        // Actualizar contador
        itemsCount.textContent = items.length;
        
        // Mostrar/ocultar mensaje y contenedor
        if (items.length === 0) {
            noItemsMessage.style.display = 'block';
            itemsContainer.style.display = 'none';
        } else {
            noItemsMessage.style.display = 'none';
            itemsContainer.style.display = 'block';
            
            // Generar HTML de los items
            itemsContainer.innerHTML = items.map((item, index) => `
                <div class="list-group-item d-flex justify-content-between align-items-center mb-2 border rounded" data-item-id="${item.id}">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3 flex-grow-1">
                                <!-- Número y label -->
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-secondary">${index + 1}</span>
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <h6 class="mb-0 fw-bold">${item.label}</h6>
                                            ${item.is_required ? `
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 small">
                                                    <i class="bi bi-exclamation-circle me-1"></i>Obligatorio
                                                </span>
                                            ` : ''}
                                        </div>
                                        ${item.description ? `
                                            <small class="text-muted">
                                                <i class="bi bi-text-left me-1"></i>${item.description}
                                            </small>
                                        ` : ''}
                                    </div>
                                </div>
                                
                                <!-- Tipo y recuadro de respuesta -->
                                <div class="d-flex align-items-center gap-3 ms-auto">
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-gear me-1"></i>${getTypeLabel(item.type)}
                                    </span>
                                    
                                    <!-- Recuadro que simula el input de respuesta -->
                                    <div class="response-preview">
                                        ${getResponsePreview(item.type)}
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Botones de acciones -->
                            <div class="d-flex gap-2 ms-3">
                                <button type="button" class="btn btn-sm btn-outline-primary btn-move-up" ${index === 0 ? 'disabled' : ''} title="Mover arriba">
                                    <i class="bi bi-arrow-up"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary btn-move-down" ${index === items.length - 1 ? 'disabled' : ''} title="Mover abajo">
                                    <i class="bi bi-arrow-down"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-item" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
            
            // Actualizar campo oculto con JSON
            itemsJsonInput.value = JSON.stringify(items.map(item => ({
                label: item.label,
                type: item.type,
                description: item.description,
                is_required: item.is_required,
                order: item.order
            })));
        }
    }
    
    function getResponsePreview(type) {
        const previews = {
            'checkbox': `
                <div class="form-check m-0">
                    <input class="form-check-input" type="checkbox" disabled style="transform: scale(0.8);">
                    <label class="form-check-label text-muted small ms-1">Sí/No</label>
                </div>
            `,
            'text': `
                <div class="input-group input-group-sm" style="width: 120px;">
                    <input type="text" class="form-control" placeholder="Texto" disabled style="font-size: 0.85rem;">
                </div>
            `,
            'number': `
                <div class="input-group input-group-sm" style="width: 100px;">
                    <input type="number" class="form-control" placeholder="0" disabled style="font-size: 0.85rem;">
                </div>
            `,
            'select': `
                <div class="input-group input-group-sm" style="width: 140px;">
                    <select class="form-select" disabled style="font-size: 0.85rem;">
                        <option selected>Seleccionar...</option>
                    </select>
                </div>
            `,
            'date': `
                <div class="input-group input-group-sm" style="width: 140px;">
                    <input type="date" class="form-control" disabled style="font-size: 0.85rem;">
                </div>
            `,
            'time': `
                <div class="input-group input-group-sm" style="width: 120px;">
                    <input type="time" class="form-control" disabled style="font-size: 0.85rem;">
                </div>
            `,
            'textarea': `
                <div class="input-group input-group-sm" style="width: 150px;">
                    <textarea class="form-control" rows="1" placeholder="Respuesta..." disabled style="font-size: 0.85rem;"></textarea>
                </div>
            `,
            'file': `
                <div class="input-group input-group-sm" style="width: 140px;">
                    <input type="file" class="form-control" disabled style="font-size: 0.85rem;">
                </div>
            `
        };
        
        return previews[type] || `
            <div class="input-group input-group-sm" style="width: 120px;">
                <input type="text" class="form-control" placeholder="Respuesta" disabled style="font-size: 0.85rem;">
            </div>
        `;
    }
    
    // Función para obtener el label del tipo
    function getTypeLabel(typeValue) {
        const typeOption = Array.from(itemTypeSelect.options).find(option => option.value === typeValue);
        return typeOption ? typeOption.textContent : typeValue;
    }
    
    // Función para remover un item
    function removeItem(itemId) {
        items = items.filter(item => item.id !== itemId);
        // Reordenar
        items.forEach((item, index) => {
            item.order = index + 1;
        });
        renderItems();
    }
    
    // Función para mover item hacia arriba
    function moveItemUp(itemId) {
        const index = items.findIndex(item => item.id === itemId);
        if (index > 0) {
            // Intercambiar posiciones
            [items[index], items[index - 1]] = [items[index - 1], items[index]];
            // Actualizar orden
            items.forEach((item, i) => {
                item.order = i + 1;
            });
            renderItems();
        }
    }
    
    // Función para mover item hacia abajo
    function moveItemDown(itemId) {
        const index = items.findIndex(item => item.id === itemId);
        if (index < items.length - 1) {
            // Intercambiar posiciones
            [items[index], items[index + 1]] = [items[index + 1], items[index]];
            // Actualizar orden
            items.forEach((item, i) => {
                item.order = i + 1;
            });
            renderItems();
        }
    }

    if (addItemBtn) {
        addItemBtn.addEventListener('click', addItem);
    }
    
    if (itemsContainer) {
        itemsContainer.addEventListener('click', function(e) {
            const itemElement = e.target.closest('[data-item-id]');
            if (!itemElement) return;
            
            const itemId = parseInt(itemElement.getAttribute('data-item-id'));
            
            // Botón eliminar
            if (e.target.closest('.btn-remove-item')) {
                if (confirm('¿Estás seguro de eliminar este item?')) {
                    removeItem(itemId);
                }
            }
            
            // Botón mover arriba
            if (e.target.closest('.btn-move-up')) {
                moveItemUp(itemId);
            }
            
            // Botón mover abajo
            if (e.target.closest('.btn-move-down')) {
                moveItemDown(itemId);
            }
        });
    }
    
    // Validación del formulario principal
    if (createChecklistForm) {
        createChecklistForm.addEventListener('submit', function(e) {
            // Validar que haya al menos un item
            if (items.length === 0) {
                e.preventDefault();
                alert('Debes agregar al menos un item a la bitácora');
                return false;
            }
            
            // Deshabilitar botón para evitar múltiples envíos
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Creando...';
            }
            
            return true;
        });
    }
    
    // Resetear el modal cuando se cierre
    const createChecklistModal = document.getElementById('createChecklistModal');
    if (createChecklistModal) {
        createChecklistModal.addEventListener('hidden.bs.modal', function() {
            // Resetear items
            items = [];
            renderItems();
            
            // Resetear campos del formulario
            if (createChecklistForm) {
                createChecklistForm.reset();
            }
            
            // Restaurar botón
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-check me-1"></i>Crear Bitácora';
            }
            
            // Limpiar mensajes de error
            document.querySelectorAll('.is-invalid').forEach(element => {
                element.classList.remove('is-invalid');
            });
        });
    }
});
</script>