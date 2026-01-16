<div class="modal fade" id="createVehicleModal" tabindex="-1" aria-labelledby="createVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createVehicleModalLabel">
                    <i class="bi bi-car-front-plus me-2"></i>Crear Nueva Unidad
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createVehicleForm" action="{{ route('vehicles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
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
                                       value="{{ old('brand') }}"
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
                                       value="{{ old('model') }}"
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
                                       value="{{ old('year') }}"
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
                                       value="{{ old('color') }}"
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
                                       value="{{ old('fuel_capacity') }}"
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
                                        <option value="{{ $value }}" {{ old('role') == $value ? 'selected' : '' }}>
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
                                       value="{{ old('license_plate') }}"
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
                                       value="{{ old('vin') }}"
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
                                       step="0.01"
                                       min="0"
                                       value="{{ old('mileage') }}"
                                       placeholder="000.00"
                                       required
                                       autofocus>
                                @error('mileage')
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
                        <i class="bi bi-check me-1"></i>Crear Unidad
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript para el modal -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 2. Vista previa de imagen
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const imagePreviewContainer = document.querySelector('.image-preview-container');
    const removeImageBtn = document.getElementById('removeImage');
    
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    }
    
    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', function() {
            if (imageInput) {
                imageInput.value = '';
            }
            if (imagePreview) {
                imagePreview.src = '';
            }
            if (imagePreviewContainer) {
                imagePreviewContainer.style.display = 'none';
            }
        });
    }
    
    // 3. Validación del formulario
    const createUserForm = document.getElementById('createUserForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (createUserForm) {
        createUserForm.addEventListener('submit', function(e) {
            // Deshabilitar el botón para evitar múltiples envíos
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Creando...';
            }
            
            // Validación de contraseña
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirmation').value;
            
            if (password !== passwordConfirm) {
                e.preventDefault();
                alert('Las contraseñas no coinciden. Por favor, verifica.');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Crear Usuario';
                }
                return false;
            }
            
            if (password.length < 8) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 8 caracteres.');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Crear Usuario';
                }
                return false;
            }
            
            // Validación de email
            const email = document.getElementById('email').value;
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailPattern.test(email)) {
                e.preventDefault();
                alert('Por favor, ingresa un correo electrónico válido.');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Crear Usuario';
                }
                return false;
            }
            
            // Si pasa todas las validaciones, el formulario se enviará
            return true;
        });
    }
    
    // 4. Resetear el modal cuando se cierre
    const createUserModal = document.getElementById('createUserModal');
    if (createUserModal) {
        createUserModal.addEventListener('hidden.bs.modal', function () {
            // Resetear el formulario
            if (createUserForm) {
                createUserForm.reset();
            }
            
            // Resetear vista previa de imagen
            if (imagePreviewContainer) {
                imagePreviewContainer.style.display = 'none';
            }
            if (imagePreview) {
                imagePreview.src = '';
            }
            if (imageInput) {
                imageInput.value = '';
            }
            
            // Restaurar botón
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Crear Usuario';
            }
            
            // Restaurar íconos de contraseña
            if (togglePassword) {
                const icon = togglePassword.querySelector('i');
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
            if (togglePasswordConfirm) {
                const icon = togglePasswordConfirm.querySelector('i');
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
            
            // Restaurar inputs de contraseña a tipo password
            if (passwordInput) {
                passwordInput.type = 'password';
            }
            if (passwordConfirmInput) {
                passwordConfirmInput.type = 'password';
            }
            
            // Remover clases de error
            document.querySelectorAll('.is-invalid').forEach(element => {
                element.classList.remove('is-invalid');
            });
            document.querySelectorAll('.invalid-feedback').forEach(element => {
                element.remove();
            });
        });
    }
    
    // 5. Mostrar errores de validación del servidor
    @if($errors->any())
        const modal = new bootstrap.Modal(document.getElementById('createUserModal'));
        modal.show();
    @endif
});
</script>