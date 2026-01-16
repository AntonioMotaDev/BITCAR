<div class="modal fade" id="editChecklistModal" tabindex="-1" aria-labelledby="editChecklistModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editChecklistModalLabel">
                    <i class="bi bi-pencil-square me-2"></i>Editar Bitácora
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editChecklistForm" action="{{ route('checklists.update', $checklist) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_checklist_id" name="id" value="">
                
                <div class="modal-body">
                    <div class="row">
                        <!-- Información de la Bitácora -->
                        <div class="col-md-12">
                            <h6 class="fw-bold text-prim mb-3">
                                <i class="bi bi-card-checklist me-2"></i>Información de la Bitácora
                            </h6>
                            
                            <!-- Nombre de la Bitácora -->
                            <div class="mb-3">
                                <label for="edit_checklist_name" class="form-label text-prim">
                                    <i class="bi bi-card-heading me-1"></i>Nombre de la Bitácora *
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="edit_checklist_name" 
                                       name="name" 
                                       placeholder="Ej: Checklist Diario de Mantenimiento"
                                       required>
                            </div>
                            
                            <!-- Descripción de la Bitácora -->
                            <div class="mb-4">
                                <label for="edit_checklist_description" class="form-label text-prim">
                                    <i class="bi bi-text-paragraph me-1"></i>Descripción *
                                </label>
                                <textarea class="form-control" 
                                          id="edit_checklist_description" 
                                          name="description" 
                                          rows="3"
                                          placeholder="Describe el propósito de esta bitácora..."
                                          required></textarea>
                            </div>
                            
                            <!-- Estado de la Bitácora -->
                            <div class="mb-4">
                                <label for="edit_checklist_status" class="form-label text-prim">
                                    <i class="bi bi-toggle-on me-1"></i>Estado
                                </label>
                                <select class="form-select" 
                                        id="edit_checklist_status" 
                                        name="is_active">
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                            
                            <!-- Sección para agregar items-->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="fw-bold text-prim mb-0">
                                        <i class="bi bi-plus-circle me-2"></i>Agregar Items a la Bitácora
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <!-- Formulario para agregar nuevo item -->
                                    <div id="editNewItemForm" class="border p-3 rounded mb-3 bg-light">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label for="edit_item_label" class="form-label small fw-bold text-prim">
                                                    <i class="bi bi-tag me-1"></i>Nombre del Item *
                                                </label>
                                                <input type="text" 
                                                       class="form-control form-control-sm" 
                                                       id="edit_item_label" 
                                                       placeholder="Ej: Nivel de aceite">
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <label for="edit_item_type" class="form-label small fw-bold text-prim">
                                                    <i class="bi bi-gear me-1"></i>Tipo *
                                                </label>
                                                <select class="form-select form-select-sm" id="edit_item_type">
                                                    <option value="" disabled selected>Selecciona un tipo</option>
                                                    @foreach($typeOptions as $value => $data)
                                                        <option value="{{ $value }}">
                                                            {{ $data['label'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <label for="edit_item_description" class="form-label small fw-bold text-prim">
                                                    <i class="bi bi-text-left me-1"></i>Descripción
                                                </label>
                                                <input type="text" 
                                                       class="form-control form-control-sm" 
                                                       id="edit_item_description" 
                                                       placeholder="Ej: Verificar que esté en nivel óptimo">
                                            </div>
                                            
                                            <!-- Checkbox Obligatorio -->
                                            <div class="col-md-12">
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" 
                                                           type="checkbox" 
                                                           id="edit_item_required" 
                                                           value="1">
                                                    <label class="form-check-label small fw-bold text-prim" for="edit_item_required">
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
                                            <button type="button" class="btn btn-i" id="editAddItemBtn">
                                                <i class="bi bi-plus-lg me-1"></i> Agregar Item
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Lista de items agregados -->
                                    <div id="editItemsList" class="mt-3">
                                        <h6 class="fw-bold text-prim mb-3">
                                            <i class="bi bi-list-ul me-2"></i>Items Agregados
                                            <span id="editItemsCount" class="badge bg-primary ms-2">0</span>
                                        </h6>
                                        
                                        <!-- Mensaje cuando no hay items -->
                                        <div id="editNoItemsMessage" class="text-center py-4 border rounded">
                                            <i class="bi bi-inbox fs-1 text-muted"></i>
                                            <p class="text-muted mt-2 mb-0">No hay items en esta bitácora</p>
                                            <small class="text-muted">Usa el formulario de arriba para agregar items</small>
                                        </div>
                                        
                                        <!-- Contenedor para items dinámicos -->
                                        <div id="editItemsContainer" class="list-group" style="display: none;">
                                            <!-- Los items se cargarán aquí dinámicamente -->
                                        </div>
                                        
                                        <!-- Campo oculto para almacenar los items en formato JSON -->
                                        <input type="hidden" name="items" id="editItemsJson" value="{{ is_array(old('items')) ? json_encode(old('items')) : (old('items') ?? '[]') }}">
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
                    <button type="submit" class="btn btn-create" id="editSubmitBtn">
                        <i class="bi bi-check me-1"></i>Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables para edición
    let editItems = [];
    
    // Referencias a elementos del modal
    const editChecklistModal = document.getElementById('editChecklistModal');
    const editChecklistForm = document.getElementById('editChecklistForm');
    const editChecklistIdInput = document.getElementById('edit_checklist_id');
    const editChecklistName = document.getElementById('edit_checklist_name');
    const editChecklistDescription = document.getElementById('edit_checklist_description');
    const editChecklistStatus = document.getElementById('edit_checklist_status');
    const editItemsJsonInput = document.getElementById('editItemsJson');
    
    // Referencias para agregar items
    const editItemLabelInput = document.getElementById('edit_item_label');
    const editItemTypeSelect = document.getElementById('edit_item_type');
    const editItemDescriptionInput = document.getElementById('edit_item_description');
    const editItemRequiredCheckbox = document.getElementById('edit_item_required');
    const editAddItemBtn = document.getElementById('editAddItemBtn');
    const editItemsContainer = document.getElementById('editItemsContainer');
    const editNoItemsMessage = document.getElementById('editNoItemsMessage');
    const editItemsCount = document.getElementById('editItemsCount');
    const editSubmitBtn = document.getElementById('editSubmitBtn');
    
    // Función para agregar un nuevo item
    function addEditItem() {
        const label = editItemLabelInput.value.trim();
        const type = editItemTypeSelect.value;
        
        if (!label) {
            alert('Por favor, ingresa el nombre del item');
            editItemLabelInput.focus();
            return;
        }
        
        if (!type) {
            alert('Por favor, selecciona un tipo');
            editItemTypeSelect.focus();
            return;
        }
        
        // Obtener valor del checkbox
        const isRequired = editItemRequiredCheckbox.checked ? 1 : 0;
        
        // Crear objeto item
        const newItem = {
            id: Date.now(),
            label: label,
            type: type,
            description: editItemDescriptionInput.value.trim(),
            is_required: isRequired,
            order: editItems.length + 1
        };
        
        // Agregar al array
        editItems.push(newItem);
        
        // Renderizar items
        renderEditItems();
        
        // Limpiar formulario
        editItemLabelInput.value = '';
        editItemTypeSelect.value = '';
        editItemDescriptionInput.value = '';
        editItemRequiredCheckbox.checked = false;
        
        // Enfocar en el primer campo
        editItemLabelInput.focus();
    }
    
    // Función para renderizar todos los items
    function renderEditItems() {
        // Actualizar contador
        editItemsCount.textContent = editItems.length;
        
        // Mostrar/ocultar mensaje y contenedor
        if (editItems.length === 0) {
            editNoItemsMessage.style.display = 'block';
            editItemsContainer.style.display = 'none';
        } else {
            editNoItemsMessage.style.display = 'none';
            editItemsContainer.style.display = 'block';
            
            // Generar HTML de los items
            editItemsContainer.innerHTML = editItems.map((item, index) => {
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
                
                return `
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
                                    
                                    <!-- Tipo -->
                                    <div class="d-flex align-items-center gap-3 ms-auto">
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi ${typeIcon} me-1"></i>${typeText}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Botones de acciones -->
                                <div class="d-flex gap-2 ms-3">
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-move-up" ${index === 0 ? 'disabled' : ''} title="Mover arriba">
                                        <i class="bi bi-arrow-up"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-move-down" ${index === editItems.length - 1 ? 'disabled' : ''} title="Mover abajo">
                                        <i class="bi bi-arrow-down"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-item" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
            
            // Actualizar campo oculto con JSON
            editItemsJsonInput.value = JSON.stringify(editItems.map(item => ({
                label: item.label,
                type: item.type,
                description: item.description,
                is_required: item.is_required,
                order: item.order
            })));
        }
    }
    
    // Función para remover un item
    function removeEditItem(itemId) {
        editItems = editItems.filter(item => item.id !== itemId);
        // Reordenar
        editItems.forEach((item, index) => {
            item.order = index + 1;
        });
        renderEditItems();
    }
    
    // Función para mover item hacia arriba
    function moveEditItemUp(itemId) {
        const index = editItems.findIndex(item => item.id === itemId);
        if (index > 0) {
            // Intercambiar posiciones
            [editItems[index], editItems[index - 1]] = [editItems[index - 1], editItems[index]];
            // Actualizar orden
            editItems.forEach((item, i) => {
                item.order = i + 1;
            });
            renderEditItems();
        }
    }
    
    // Función para mover item hacia abajo
    function moveEditItemDown(itemId) {
        const index = editItems.findIndex(item => item.id === itemId);
        if (index < editItems.length - 1) {
            // Intercambiar posiciones
            [editItems[index], editItems[index + 1]] = [editItems[index + 1], editItems[index]];
            // Actualizar orden
            editItems.forEach((item, i) => {
                item.order = i + 1;
            });
            renderEditItems();
        }
    }
    
    // Configurar botones "Editar" en las cards
    document.querySelectorAll('.edit-checklist-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const checklistId = this.getAttribute('data-checklist-id');
            const checklistName = this.getAttribute('data-checklist-name');
            const checklistDescription = this.getAttribute('data-checklist-description');
            const checklistStatus = this.getAttribute('data-checklist-status');
            
            // Obtener items de la card
            const checklistCard = document.querySelector(`.checklist-card[data-checklist-id="${checklistId}"]`);
            let checklistItems = [];
            
            if (checklistCard) {
                try {
                    const editItemsJson = checklistCard.getAttribute('data-checklist-items');
                    checklistItems = editItemsJson ? JSON.parse(editItemsJson) : [];
                } catch (e) {
                    console.error('Error parsing items:', e);
                }
            }
            
            // Llenar campos del modal
            if (editChecklistIdInput) editChecklistIdInput.value = checklistId;
            if (editChecklistName) editChecklistName.value = checklistName || '';
            if (editChecklistDescription) editChecklistDescription.value = checklistDescription || '';
            if (editChecklistStatus) editChecklistStatus.value = checklistStatus === '1' ? '1' : '0';
            if (editChecklistForm) editChecklistForm.action = `/checklists/${checklistId}`;
            
            // Cargar items
            editItems = Array.isArray(checklistItems) ? checklistItems.map((item, index) => ({
                id: item.id || Date.now() + index,
                label: item.label || item.name || '',
                type: item.type || 'text',
                description: item.description || '',
                is_required: item.is_required || item.required || 0,
                order: item.order || index + 1
            })) : [];
            
            // Renderizar items
            renderEditItems();
        });
    });
    
    // Agregar item
    if (editAddItemBtn) {
        editAddItemBtn.addEventListener('click', addEditItem);
    }
    
    // Agregar con Enter
    if (editItemLabelInput) {
        editItemLabelInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addEditItem();
            }
        });
    }
    
    // Delegación de eventos para botones dinámicos
    if (editItemsContainer) {
        editItemsContainer.addEventListener('click', function(e) {
            const itemElement = e.target.closest('[data-item-id]');
            if (!itemElement) return;
            
            const itemId = parseInt(itemElement.getAttribute('data-item-id'));
            
            if (e.target.closest('.btn-remove-item')) {
                if (confirm('¿Estás seguro de eliminar este item?')) {
                    removeEditItem(itemId);
                }
            }
            
            if (e.target.closest('.btn-move-up')) {
                moveEditItemUp(itemId);
            }
            
            if (e.target.closest('.btn-move-down')) {
                moveEditItemDown(itemId);
            }
        });
    }
    
    // Resetear el modal cuando se cierre
    if (editChecklistModal) {
        editChecklistModal.addEventListener('hidden.bs.modal', function() {
            // Resetear items
            editItems = [];
            renderEditItems();
            
            // Limpiar campo del ID
            if (editChecklistIdInput) {
                editChecklistIdInput.value = '';
            }
            
            // Restaurar botón
            if (editSubmitBtn) {
                editSubmitBtn.disabled = false;
                editSubmitBtn.innerHTML = '<i class="bi bi-check me-1"></i>Guardar Cambios';
            }
        });
    }
    
    if (editChecklistForm) {
        editChecklistForm.addEventListener('submit', function(e) {
            if (editItems.length === 0) {
                e.preventDefault();
                alert('Debes agregar al menos un item a la bitácora');
                return false;
            }
            
            if (editSubmitBtn) {
                editSubmitBtn.disabled = true;
                editSubmitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Guardando...';
            }
            
            return true;
        });
    }
});
</script>