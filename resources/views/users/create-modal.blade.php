<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">
                    <i class="bi bi-person-plus me-2"></i>Crear Nuevo Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createUserForm" action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
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
                                       value="{{ old('name') }}"
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
                                       value="{{ old('email') }}"
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
                                           placeholder="Mínimo 8 caracteres"
                                           required>
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
                                           placeholder="Repite la contraseña"
                                           required>
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
                                        <option value="{{ $value }}" {{ old('role') == $value ? 'selected' : '' }}>
                                             {{ $data['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <strong>Administrador:</strong> Acceso completo al sistema<br>
                                        <strong>Supervisor:</strong> Gestiona equipos y operadores<br>
                                        <strong>Operador:</strong> Acceso desde app móvil
                                    </small>
                                </div>
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
                        <i class="bi bi-check me-1"></i>Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript para el modal -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });
    }
    
    if (togglePasswordConfirm && passwordConfirmInput) {
        togglePasswordConfirm.addEventListener('click', function() {
            const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });
    }
    
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