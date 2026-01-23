<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">
                    <i class="bi bi-person-plus me-2"></i>Editar Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm" action="#" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <!-- Información Personal -->
                        <div class="col-md-6">
                            <h6 class="fw-bold text-prim mb-3">
                                <i class="bi bi-person-badge me-2"></i>Información Personal
                            </h6>
                            
                            <!-- Nombre Completo -->
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="bi bi-person-fill me-1"></i>Nombre Completo *
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value=""
                                       placeholder="Ej: Juan Pérez López"
                                       required
                                       autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Correo Electrónico -->
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope-fill me-1"></i>Correo Electrónico *
                                </label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value=""
                                       placeholder="Ej: usuario@empresa.com"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Contraseña -->
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock-fill me-1"></i>Contraseña *
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Mínimo 8 caracteres">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">La contraseña debe tener al menos 8 caracteres.</small>
                            </div>
                            
                            <!-- Confirmar Contraseña -->
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="bi bi-lock-fill me-1"></i>Confirmar Contraseña *
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           placeholder="Repite la contraseña">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Información Adicional -->
                        <div class="col-md-6">
                            <h6 class="fw-bold text-prim mb-3">
                                <i class="bi bi-gear-fill me-2"></i>Configuración del Usuario
                            </h6>
                            
                            <!-- Rol -->
                            <div class="mb-3">
                                <label for="role" class="form-label">
                                    <i class="bi bi-person-badge me-1"></i>Rol del Usuario *
                                </label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" 
                                        name="role" 
                                        required>
                                    <option value="" disabled selected>Selecciona un rol</option>
                                    @foreach($roleOptions as $value => $data)
                                        <option value="{{ $value }}" {{ $user->role == $value ? 'selected' : '' }}>
                                             {{ $data['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Estado -->
                            <div class="mb-3">
                                <label for="status" class="form-label">
                                    <i class="bi bi-toggle-on me-1"></i>Estado del Usuario *
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="" disabled selected>Selecciona un estado</option>
                                    <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>
                                        Activo
                                    </option>
                                    <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>
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
                                    <i class="bi bi-image-fill me-1"></i>Foto de Perfil (Opcional)
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
        
        const editUserModal = document.getElementById('editUserModal');
        
        if (editUserModal) {
            editUserModal.addEventListener('shown.bs.modal', function(event) {
                
                const button = event.relatedTarget;
                
                if (!button) {
                    console.error('No se encontró el botón relacionado');
                    return;
                }
                
                // Obtener datos del usuario desde los atributos data
                const userId = button.getAttribute('data-user-id');
                const userName = button.getAttribute('data-user-name') || '';
                const userEmail = button.getAttribute('data-user-email') || '';
                const userRole = button.getAttribute('data-user-role') || '';
                const userStatus = button.getAttribute('data-user-status') || '';
                const userImage = button.getAttribute('data-user-image') || '';
                
                const modalElement = document.getElementById('editUserModal');
                
                // Inicializar el flag de eliminar imagen a 0 cada que se abre el modal
                const deleteImageFlag = modalElement.querySelector('#delete_image_flag');
                if (deleteImageFlag) {
                    deleteImageFlag.value = '0';
                }
                
                // Actualizar los campos del formulario
                setFieldValueInModal(modalElement, 'name', userName);
                setFieldValueInModal(modalElement, 'email', userEmail);
                setFieldValueInModal(modalElement, 'role', userRole);
                setFieldValueInModal(modalElement, 'status', userStatus);
                
                // Actualizar vista previa de imagen si existe
                const imagePreview = modalElement.querySelector('#imagePreview');
                const imagePreviewContainer = modalElement.querySelector('.image-preview-container');
                
                if (userImage && userImage.trim() !== '' && imagePreview && imagePreviewContainer) {
                    // Asegurarse de que la ruta sea completa
                    const imageUrl = userImage.startsWith('/storage/') 
                        ? userImage 
                        : `/storage/${userImage.trim()}`;
                    
                    imagePreview.src = imageUrl;
                    imagePreviewContainer.style.display = 'block';
                } else if (imagePreviewContainer) {
                    imagePreviewContainer.style.display = 'none';
                }
                
                // Actualizar la acción del formulario
                const editUserForm = modalElement.querySelector('#editUserForm');
                if (editUserForm && userId) {
                    const baseUrl = "{{ route('users.update', ':id') }}";
                    const actionUrl = baseUrl.replace(':id', userId);
                    editUserForm.action = actionUrl;
                    
                }
                
                // Inicializar funcionalidad de mostrar/ocultar contraseña
                initPasswordToggle(modalElement);
                
                // Inicializar funcionalidad de vista previa de imagen
                initImagePreview(modalElement);
            });
        }
        
        // Función para establecer valores en campos del modal
        function setFieldValueInModal(modalElement, elementId, value) {
            const element = modalElement.querySelector(`#${elementId}`);
            if (element) {
                element.value = value;
                
                if (element.tagName === 'SELECT') {
                    // Primero, deseleccionar todas las opciones
                    element.querySelectorAll('option').forEach(option => {
                        option.selected = false;
                    });
                    
                    // Luego, seleccionar la opción correcta
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
        
        // Función para inicializar mostrar/ocultar contraseña
        function initPasswordToggle(modalElement) {
            const togglePasswordBtn = modalElement.querySelector('#togglePassword');
            const togglePasswordConfirmBtn = modalElement.querySelector('#togglePasswordConfirm');
            const passwordInput = modalElement.querySelector('#password');
            const passwordConfirmInput = modalElement.querySelector('#password_confirmation');
            
            if (togglePasswordBtn && passwordInput) {
                togglePasswordBtn.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    // Cambiar ícono
                    const icon = this.querySelector('i');
                    if (type === 'password') {
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    } else {
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    }
                });
            }
            
            if (togglePasswordConfirmBtn && passwordConfirmInput) {
                togglePasswordConfirmBtn.addEventListener('click', function() {
                    const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordConfirmInput.setAttribute('type', type);
                    
                    // Cambiar ícono
                    const icon = this.querySelector('i');
                    if (type === 'password') {
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    } else {
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    }
                });
            }
        }
        
        // Función para inicializar vista previa de imagen
        function initImagePreview(modalElement) {
            const imageInput = modalElement.querySelector('#image');
            const imagePreview = modalElement.querySelector('#imagePreview');
            const imagePreviewContainer = modalElement.querySelector('.image-preview-container');
            const removeImageBtn = modalElement.querySelector('#removeImage');
            const deleteImageFlag = modalElement.querySelector('#delete_image_flag');
            
            if (imageInput && imagePreview) {
                imageInput.addEventListener('change', function() {
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
                            imagePreview.src = e.target.result;
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
                    
                    if (deleteImageFlag) {
                        deleteImageFlag.value = '1';
                    } else {
                        console.error('ERROR: No se encontró delete_image_flag');
                    }
                });
            }
        }
        
        // Configurar validación del formulario
        document.addEventListener('submit', function(e) {
            if (e.target && e.target.id === 'editUserForm') {
                
                e.preventDefault();
                
                const submitBtn = e.target.querySelector('#submitBtn');
                
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Actualizando...';
                }
                
                let isValid = true;
                let errorFields = [];
                let errorMessage = '';
                
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
                
                // Validar contraseña
                const passwordInput = e.target.querySelector('#password');
                const passwordConfirmInput = e.target.querySelector('#password_confirmation');
                
                if (passwordInput && passwordConfirmInput) {
                    const password = passwordInput.value;
                    const passwordConfirm = passwordConfirmInput.value;
                    
                    if (password.length > 0 && password.length < 8) {
                        passwordInput.classList.add('is-invalid');
                        isValid = false;
                        errorMessage = 'La contraseña debe tener al menos 8 caracteres.';
                    }
                    
                    if (password !== passwordConfirm) {
                        passwordInput.classList.add('is-invalid');
                        passwordConfirmInput.classList.add('is-invalid');
                        isValid = false;
                        errorMessage = 'Las contraseñas no coinciden.';
                    }
                }
                
                // Validar email
                const emailInput = e.target.querySelector('#email');
                if (emailInput && emailInput.value) {
                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailPattern.test(emailInput.value)) {
                        emailInput.classList.add('is-invalid');
                        isValid = false;
                        errorMessage = 'Por favor, ingresa un correo electrónico válido.';
                    }
                }
                
                if (!isValid) {
                    alert(errorMessage || `Por favor, completa los siguientes campos: ${errorFields.join(', ')}`);
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="bi bi-check me-1"></i>Actualizar';
                    }
                    return false;
                }
                e.target.submit();
                return true;
            }
        });
        
        // Resetear el modal cuando se cierre
        if (editUserModal) {
            editUserModal.addEventListener('hidden.bs.modal', function () {
                
                // Restaurar botón si existe
                const submitBtn = this.querySelector('#submitBtn');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-check me-1"></i>Actualizar';
                }
                
                // Limpiar campos del formulario
                const form = this.querySelector('#editUserForm');
                if (form) {
                    form.reset();
                    
                    // Ocultar vista previa de imagen
                    const imagePreviewContainer = this.querySelector('.image-preview-container');
                    if (imagePreviewContainer) {
                        imagePreviewContainer.style.display = 'none';
                    }
                    
                    const modalErrors = this.querySelectorAll('.is-invalid');
                    modalErrors.forEach(element => {
                        element.classList.remove('is-invalid');
                    });
                }
            });
        } 
    });
</script>