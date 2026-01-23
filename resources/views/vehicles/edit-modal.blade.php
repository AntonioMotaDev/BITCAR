<div class="modal fade" id="editVehicleModal" tabindex="-1" aria-labelledby="editVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editVehicleModalLabel">
                    <i class="bi bi-car-front-plus me-2"></i>Editar Unidad
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editVehicleForm" action="#" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <!-- Información General -->
                        <div class="col-md-6">
                            <h6 class="fw-bold text-prim mb-3">
                                <i class="bi bi-car-front me-2"></i>Información General
                            </h6>
                            
                            <!-- Marca -->
                            <div class="mb-3">
                                <label for="brand" class="form-label">
                                    <i class="bi bi-tag-fill  me-1"></i>Marca *
                                </label>
                                <input type="text" 
                                       class="form-control @error('brand') is-invalid @enderror" 
                                       id="brand" 
                                       name="brand" 
                                       value=""
                                       placeholder="Ej: Nissan"
                                       required
                                       autofocus>
                                @error('brand')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Modelo -->
                            <div class="mb-3">
                                <label for="model" class="form-label">
                                    <i class="bi-car-front-fill me-1"></i>Modelo *
                                </label>
                                <input type="model" 
                                       class="form-control @error('model') is-invalid @enderror" 
                                       id="model" 
                                       name="model" 
                                       value=""
                                       placeholder="Ej: Frontier"
                                       required>
                                @error('model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Año -->
                            <div class="mb-3">
                                <label for="year" class="form-label">
                                    <i class="bi  bi-calendar-date-fill  me-1"></i>Año *
                                </label>
                                <input type="number" 
                                       class="form-control @error('year') is-invalid @enderror" 
                                       id="year" 
                                       name="year" 
                                       value=""
                                       placeholder="Ej: 2005"
                                       required>
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Color -->
                            <div class="mb-3">
                                <label for="color" class="form-label">
                                    <i class="bi bi-palette-fill me-1"></i>Color *
                                </label>
                                <input type="text" 
                                       class="form-control @error('color') is-invalid @enderror" 
                                       id="color" 
                                       name="color" 
                                       value=""
                                       placeholder="Ej: Blanco"
                                       required
                                       autofocus>
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Capacidad Tanque Gasolina -->
                            <div class="mb-3">
                            <label for="fuel_capacity" class="form-label">
                                    <i class="bi bi-fuel-pump-fill me-1"></i>Capacidad Tanque Gasolina *
                                </label>
                                <input type="number" 
                                       class="form-control @error('fuel_capacity') is-invalid @enderror" 
                                       id="fuel_capacity" 
                                       name="fuel_capacity" 
                                       value=""
                                       placeholder="000.00"
                                       required
                                       autofocus>
                                @error('fuel_capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                        </div>
                        
                        <!-- Información Adicional -->
                        <div class="col-md-6">
                            <h6 class="fw-bold text-prim mb-3">
                                <i class="bi bi-gear-fill me-2"></i>Configuración de la Unidad
                            </h6>
                            
                            <!-- Tipo -->
                            <div class="mb-3">
                                <label for="type" class="form-label">
                                    <i class="bi bi-truck me-1"></i>Tipo de Unidad *
                                </label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                        id="type" 
                                        name="type" 
                                        required>
                                    <option value="" disabled selected>Selecciona un tipo</option>
                                    @foreach($typeOptions as $value => $data)
                                        <option value="{{ $value }}" {{ "" == $value ? 'selected' : '' }}>
                                             {{ $data['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Placas -->
                            <div class="mb-3">
                                <label for="license_plate" class="form-label">
                                    <i class="bi bi-card-text me-1"></i>Placas *
                                </label>
                                <input type="text" 
                                       class="form-control @error('license_plate') is-invalid @enderror" 
                                       id="license_plate" 
                                       name="license_plate" 
                                       value=""
                                       placeholder="Ej: DEF-456"
                                       required
                                       autofocus>
                                @error('license_plate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- VIN -->
                            <div class="mb-3">
                                <label for="vin" class="form-label">
                                    <i class="bi bi-upc-scan me-1"></i>VIN *
                                </label>
                                <input type="text" 
                                       class="form-control @error('vin') is-invalid @enderror" 
                                       id="vin" 
                                       name="vin" 
                                       value=""
                                       placeholder="Ej: 2HGBH41JXMN109187"
                                       required
                                       autofocus>
                                @error('vin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kilometraje -->
                            <div class="mb-3">
                                <label for="mileage" class="form-label">
                                    <i class="bi bi-speedometer2 me-1"></i>Kilometraje *
                                </label>
                                <input type="number" 
                                       class="form-control @error('mileage') is-invalid @enderror" 
                                       id="mileage" 
                                       name="mileage" 
                                       value=""
                                       placeholder="000.00"
                                       required
                                       autofocus>
                                @error('mileage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Estado -->
                            <div class="mb-3">
                                <label for="status" class="form-label">
                                    <i class="bi bi bi-toggle-on me-1"></i>Estado del Vehículo *
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="" disabled selected>Selecciona un estado</option>
                                    <option value="activo" {{ $vehicle->status == 'activo' ? 'selected' : '' }}>
                                        Activo
                                    </option>
                                    <option value="mantenimiento" {{ $vehicle->status == 'mantenimiento' ? 'selected' : '' }}>
                                        Mantenimiento
                                    </option>
                                    <option value="inactivo" {{ $vehicle->status == 'inactivo' ? 'selected' : '' }}>
                                        Inactivo
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Imagen de Perfil -->
                            <div class="mb-3">
                                <label for="image" class="form-label">
                                    <i class="bi bi-image-fill me-1"></i>Foto de Perfil de la Unidad (Opcional)
                                </label>
                                <div class="input-group">
                                    <input type="file" 
                                           class="form-control @error('image') is-invalid @enderror" 
                                           id="image" 
                                           name="image"
                                           accept="image/*">
                                    <label class="input-group-text" for="image">
                                        <i class="bi bi-upload"></i>
                                    </label>
                                </div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="mt-2">
                                    <small class="text-muted">Formatos aceptados: JPG, PNG, GIF. Tamaño máximo: 2MB</small>
                                </div>
                                <!-- Vista previa de la imagen -->
                                <div class="mt-3 text-center">
                                    <div class="image-preview-container" style="display: none;">
                                        <img id="imagePreview" class="img-thumbnail rounded-circle" 
                                             style="width: 120px; height: 120px; object-fit: cover;"
                                             alt="Vista previa">
                                        <button type="button" class="btn btn-sm btn-danger mt-2" id="removeImage">
                                            <i class="bi bi-trash"></i> Remover
                                        </button>
                                    </div>
                                    <input type="hidden" name="delete_image" value="0" id="delete_image_flag">
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
                        <i class="bi bi-check me-1"></i>Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript para el modal -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        const editVehicleModal = document.getElementById('editVehicleModal');
        
        if (editVehicleModal) {
            editVehicleModal.addEventListener('shown.bs.modal', function(event) {
                
                const button = event.relatedTarget;
                const modalElement = this;
                
                if (!button) {
                    console.error('No se encontró el botón relacionado');
                    return;
                }
                
                // Obtener datos del vehículo desde los atributos data
                const vehicleId = button.getAttribute('data-vehicle-id');
                const vehicleBrand = button.getAttribute('data-vehicle-brand') || '';
                const vehicleModel = button.getAttribute('data-vehicle-model') || '';
                const vehicleYear = button.getAttribute('data-vehicle-year') || '';
                const vehicleColor = button.getAttribute('data-vehicle-color') || '';
                const vehicleFuelCapacity = button.getAttribute('data-vehicle-fuel-capacity') || '';
                const vehicleLicensePlate = button.getAttribute('data-vehicle-license-plate') || '';
                const vehicleVin = button.getAttribute('data-vehicle-vin') || '';
                const vehicleMileage = button.getAttribute('data-vehicle-mileage') || '';
                const vehicleType = button.getAttribute('data-vehicle-type') || '';
                const vehicleStatus = button.getAttribute('data-vehicle-status') || '';
                const vehicleImage = button.getAttribute('data-vehicle-image') || '';

                // Inicializar el flag de eliminar imagen a 0 cada que se abre el modal
                const deleteImageFlag = modalElement.querySelector('#delete_image_flag');
                if (deleteImageFlag) {
                    deleteImageFlag.value = '0';
                }
                
                // Actualizar los campos del formulario
                setFieldValueInModal(modalElement, 'brand', vehicleBrand);
                setFieldValueInModal(modalElement, 'model', vehicleModel);
                setFieldValueInModal(modalElement, 'year', vehicleYear);
                setFieldValueInModal(modalElement, 'color', vehicleColor);
                setFieldValueInModal(modalElement, 'fuel_capacity', vehicleFuelCapacity);
                setFieldValueInModal(modalElement, 'license_plate', vehicleLicensePlate);
                setFieldValueInModal(modalElement, 'vin', vehicleVin);
                setFieldValueInModal(modalElement, 'mileage', vehicleMileage);
                setFieldValueInModal(modalElement, 'type', vehicleType);
                setFieldValueInModal(modalElement, 'status', vehicleStatus);
                
                // vista previa de la imagen
                initImagePreview(modalElement);
                
                // Actualizar vista previa de imagen si existe
                const imagePreview = modalElement.querySelector('#imagePreview');
                const imagePreviewContainer = modalElement.querySelector('.image-preview-container');
                
                if (vehicleImage && vehicleImage.trim() !== '' && imagePreview && imagePreviewContainer) {
                    // Asegurarse de que la ruta sea completa
                    const imageUrl = vehicleImage.startsWith('/storage/') 
                        ? vehicleImage 
                        : `/storage/${vehicleImage.trim()}`;
                    
                    imagePreview.src = imageUrl;
                    imagePreviewContainer.style.display = 'block';
                } else if (imagePreviewContainer) {
                    imagePreviewContainer.style.display = 'none';
                }
                
                // Actualizar la acción del formulario
                const editVehicleForm = modalElement.querySelector('#editVehicleForm');
                if (editVehicleForm && vehicleId) {
                    const baseUrl = "{{ route('vehicles.update', ':id') }}";
                    const actionUrl = baseUrl.replace(':id', vehicleId);
                    editVehicleForm.action = actionUrl;

                    let methodInput = editVehicleForm.querySelector('input[name="_method"]')
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'PUT';
                        form.appendChild(methodInput);
                    }
                }
            });
        }
        
        // Función para inicializar vista previa de imagen
        function initImagePreview(modalElement) {
            const imageInput = modalElement.querySelector('#image');
            const imagePreview = modalElement.querySelector('#imagePreview');
            const imagePreviewContainer = modalElement.querySelector('.image-preview-container');
            const removeImageBtn = modalElement.querySelector('#removeImage');
            const deleteImageFlag = modalElement.querySelector('#delete_image_flag');
            
            if (imageInput) {
                // Limpiar evento anterior 
                const newImageInput = imageInput.cloneNode(true);
                imageInput.parentNode.replaceChild(newImageInput, imageInput);
                
                // Agregar nuevo evento
                newImageInput.addEventListener('change', function(e) {

                    const file = this.files[0];
                    if (file) {
                        // Validar tamaño (2MB máximo)
                        if (file.size > 2 * 1024 * 1024) {
                            alert('La imagen no puede superar los 2MB');
                            this.value = '';
                            return;
                        }
                        
                        // Validar tipo de archivo
                        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                        if (!validTypes.includes(file.type)) {
                            alert('Formato de imagen no válido. Use JPG, PNG, GIF o WebP.');
                            this.value = '';
                            return;
                        }
                        
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            if (imagePreview) {
                                imagePreview.src = e.target.result;
                            }
                            if (imagePreviewContainer) {
                                imagePreviewContainer.style.display = 'block';
                            }
                        };
                        reader.readAsDataURL(file);
                        
                        if (deleteImageFlag) {
                            deleteImageFlag.value = '0';
                        }
                    }
                });
            }
            
            // Configurar botón para remover imagen
            if (removeImageBtn) {
                // Limpiar evento anterior
                const newRemoveBtn = removeImageBtn.cloneNode(true);
                removeImageBtn.parentNode.replaceChild(newRemoveBtn, removeImageBtn);
                
                newRemoveBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const imageInput = modalElement.querySelector('#image');
                    const imagePreview = modalElement.querySelector('#imagePreview');
                    const imagePreviewContainer = modalElement.querySelector('.image-preview-container');
                    
                    // Limpiar input de archivo
                    if (imageInput) {
                        imageInput.value = '';
                    }
                    
                    // Limpiar vista previa
                    if (imagePreview) {
                        imagePreview.src = '';
                    }
                    
                    // Ocultar contenedor
                    if (imagePreviewContainer) {
                        imagePreviewContainer.style.display = 'none';
                    }
                   
                    if (deleteImageFlag) {
                        deleteImageFlag.value = '1';
                    }
                });
            }
        }
        
        function setFieldValueInModal(modalElement, elementId, value) {
            const element = modalElement.querySelector(`#${elementId}`);
            if (element) {
                element.value = value;
                
                if (element.tagName === 'SELECT') {
                    element.querySelectorAll('option').forEach(option => {
                        option.selected = false;
                    });

                    const optionToSelect = element.querySelector(`option[value="${value}"]`);
                    if (optionToSelect) {
                        optionToSelect.selected = true;
                    } else {
                        console.warn(`Opción ${value} no encontrada en el select ${elementId}`);
                    }
                }
            } else {
                console.error(`Elemento ${elementId} no encontrado en el modal`);
            }
        }
        
        document.addEventListener('submit', function(e) {
            if (e.target && e.target.id === 'editVehicleForm') {
                
                e.preventDefault();
                
                const submitBtn = e.target.querySelector('#submitBtn');
                
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Actualizando...';
                }
                
                let isValid = true;
                let errorFields = [];
                
                // Validar campos requeridos
                const requiredFields = e.target.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                        errorFields.push(field.name || field.id);
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
                
                if (!isValid) {
                    alert(`Por favor, completa los siguientes campos: ${errorFields.join(', ')}`);
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="bi bi-check me-1"></i>Actualizar';
                    }
                    return false;
                }

                const formData = new FormData(e.target);
                
                e.target.submit();
                return true;
            }
        });
        
        // Resetear el modal cuando se cierre
        if (editVehicleModal) {
            editVehicleModal.addEventListener('hidden.bs.modal', function () {
                // Limpiar el formulario
                const form = this.querySelector('#editVehicleForm');
                if (form) {
                    form.reset();
                    
                    // Limpiar vista previa
                    const imagePreview = this.querySelector('#imagePreview');
                    const imagePreviewContainer = this.querySelector('.image-preview-container');
                    if (imagePreview) imagePreview.src = '';
                    if (imagePreviewContainer) imagePreviewContainer.style.display = 'none';
                    
                    // Remover flag de eliminación
                    const deleteFlag = form.querySelector('input[name="delete_image"]');
                    if (deleteFlag) deleteFlag.remove();
                }
            });
        }
        
    });
</script>
