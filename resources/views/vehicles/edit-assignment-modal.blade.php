<div class="modal fade" id="editAssignmentModal" tabindex="-1" aria-labelledby="editAssignmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAssignmentModalLabel">
                    <i class="bi bi-pencil-square me-2"></i>Editar Asignación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editAssignmentForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <!-- Información de la Asignación -->
                        <div class="col-md-6">
                            <h6 class="fw-bold text-prim mb-3">
                                <i class="bi bi-info-circle me-2"></i>Datos de la Asignación
                            </h6>
                            
                            <!-- Vehículo -->
                            <div class="mb-3">
                                <label for="edit_vehicle_id" class="form-label">
                                    <i class="bi bi-truck me-1"></i>Vehículo *
                                </label>
                                <select class="form-select @error('vehicle_id') is-invalid @enderror" 
                                        id="edit_vehicle_id" 
                                        name="vehicle_id" 
                                        required>
                                    <option value="" disabled>Selecciona un vehículo</option>
                                    @foreach($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}" 
                                                data-plate="{{ $vehicle->license_plate }}"
                                                data-model="{{ $vehicle->model }}"
                                                data-brand="{{ $vehicle->brand }}"
                                                data-color="{{ $vehicle->color }}">
                                            {{ $vehicle->license_plate }} - {{ $vehicle->brand }} {{ $vehicle->model }} {{ $vehicle->color}}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vehicle_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted mt-1 d-block" id="edit-vehicle-info">
                                    <!-- Información del vehículo seleccionado aparecerá aquí -->
                                </small>
                            </div>
                            
                            <!-- Usuario/Conductor -->
                            <div class="mb-3">
                                <label for="edit_user_id" class="form-label">
                                    <i class="bi bi-person me-1"></i>Conductor/Usuario *
                                </label>
                                <select class="form-select @error('user_id') is-invalid @enderror" 
                                        id="edit_user_id" 
                                        name="user_id" 
                                        required>
                                    <option value="" disabled>Selecciona un usuario</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" 
                                                data-role="{{ $user->role }}"
                                                data-email="{{ $user->email }}">
                                            {{ $user->name }} ({{ $user->role }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted mt-1 d-block" id="edit-user-info">
                                    <!-- Información del usuario seleccionado aparecerá aquí -->
                                </small>
                            </div>
                        </div>
                        
                        <!-- Período de Asignación -->
                        <div class="col-md-6">
                            <h6 class="fw-bold text-prim mb-3">
                                <i class="bi bi-calendar-range me-2"></i>Período de Asignación
                            </h6>
                            
                            <!-- Fecha de Inicio -->
                            <div class="mb-3">
                                <label for="edit_start_date" class="form-label">
                                    <i class="bi bi-calendar-check me-1"></i>Fecha de Inicio *
                                </label>
                                <input type="date" 
                                       class="form-control @error('start_date') is-invalid @enderror" 
                                       id="edit_start_date" 
                                       name="start_date" 
                                       required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Fecha en que inicia la asignación</small>
                            </div>
                            
                            <!-- Fecha de Fin -->
                            <div class="mb-3">
                                <label for="edit_end_date" class="form-label">
                                    <i class="bi bi-calendar-x me-1"></i>Fecha de Fin (Opcional)
                                </label>
                                <input type="date" 
                                       class="form-control @error('end_date') is-invalid @enderror" 
                                       id="edit_end_date" 
                                       name="end_date">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Dejar en blanco para asignación indefinida</small>
                            </div>
                            
                        </div>
                    </div>
                    
                    <!-- Resumen de la Asignación -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="fw-bold text-prim mb-2">
                            <i class="bi bi-card-checklist me-2"></i>Resumen de la Asignación
                        </h6>
                        <div class="row small">
                            <div class="col-md-6">
                                <div class="d-flex mb-1">
                                    <span class="text-muted me-2" style="min-width: 120px;">Vehículo:</span>
                                    <span id="edit-summary-vehicle" class="fw-medium">No seleccionado</span>
                                </div>
                                <div class="d-flex mb-1">
                                    <span class="text-muted me-2" style="min-width: 120px;">Conductor:</span>
                                    <span id="edit-summary-user" class="fw-medium">No seleccionado</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex mb-1">
                                    <span class="text-muted me-2" style="min-width: 120px;">Período:</span>
                                    <span id="edit-summary-period" class="fw-medium">Por definir</span>
                                </div>
                                <div class="d-flex mb-1">
                                    <span class="text-muted me-2" style="min-width: 120px;">Duración:</span>
                                    <span id="edit-summary-duration" class="fw-medium">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-edit" id="submitEditAssignBtn">
                        <i class="bi bi-check me-1"></i>Actualizar Asignación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript para el modal de edición -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editAssignModal = document.getElementById('editAssignmentModal');
    const editVehicleSelect = document.getElementById('edit_vehicle_id');
    const editUserSelect = document.getElementById('edit_user_id');
    const editStartDateInput = document.getElementById('edit_start_date');
    const editEndDateInput = document.getElementById('edit_end_date');
    const editAssignForm = document.getElementById('editAssignmentForm');
    const submitEditBtn = document.getElementById('submitEditAssignBtn');
    
    // Elementos para el resumen
    const editSummaryVehicle = document.getElementById('edit-summary-vehicle');
    const editSummaryUser = document.getElementById('edit-summary-user');
    const editSummaryPeriod = document.getElementById('edit-summary-period');
    const editSummaryDuration = document.getElementById('edit-summary-duration');
    const editVehicleInfo = document.getElementById('edit-vehicle-info');
    const editUserInfo = document.getElementById('edit-user-info');
    
    // Cuando se abre el modal, cargar los datos de la asignación
    if (editAssignModal) {
        editAssignModal.addEventListener('show.bs.modal', function(e) {
            const button = e.relatedTarget;
            if (button) {
                const assignmentId = button.getAttribute('data-assignment-id');
                const vehicleId = button.getAttribute('data-vehicle-id');
                const userId = button.getAttribute('data-user-id');
                const startDate = button.getAttribute('data-start-date');
                const expirationDate = button.getAttribute('data-expiration-date');
                
                // Establecer la acción del formulario
                editAssignForm.action = `/vehicles/assignment/${assignmentId}`;
                
                // Cargar valores
                editVehicleSelect.value = vehicleId;
                editUserSelect.value = userId;
                editStartDateInput.value = startDate;
                editEndDateInput.value = expirationDate;
                
                // Disparar cambio para actualizar la información
                editVehicleSelect.dispatchEvent(new Event('change'));
                editUserSelect.dispatchEvent(new Event('change'));
                updateEditSummary();
            }
        });
    }
    
    // Actualizar información del vehículo seleccionado
    if (editVehicleSelect) {
        editVehicleSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const plate = selectedOption.getAttribute('data-plate');
                const model = selectedOption.getAttribute('data-model');
                const brand = selectedOption.getAttribute('data-brand');
                const color = selectedOption.getAttribute('data-color');
    
                editVehicleInfo.innerHTML = `<strong>${brand} ${model}</strong> - Placa: ${plate} - Color: ${color}`;
                editSummaryVehicle.textContent = `${plate} - ${brand} ${model} ${color}`;
            } else {
                editVehicleInfo.innerHTML = '';
                editSummaryVehicle.textContent = 'No seleccionado';
            }
            updateEditSummary();
        });
    }
    
    // Actualizar información del usuario seleccionado
    if (editUserSelect) {
        editUserSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const name = selectedOption.text.split('(')[0].trim();
                const role = selectedOption.getAttribute('data-role');
                const email = selectedOption.getAttribute('data-email');
  
                editUserInfo.innerHTML = `<strong>${role}</strong> - ${email}`;
                editSummaryUser.textContent = `${name} (${role})`;
            } else {
                editUserInfo.innerHTML = '';
                editSummaryUser.textContent = 'No seleccionado';
            }
            updateEditSummary();
        });
    }
    
    // Actualizar fechas y calcular duración
    if (editStartDateInput) {
        editStartDateInput.addEventListener('change', updateEditSummary);
    }
    
    if (editEndDateInput) {
        editEndDateInput.addEventListener('change', updateEditSummary);
        
        // Establecer fecha mínima como fecha de inicio
        editStartDateInput.addEventListener('change', function() {
            editEndDateInput.min = this.value;
            if (editEndDateInput.value && editEndDateInput.value < this.value) {
                editEndDateInput.value = this.value;
            }
            updateEditSummary();
        });
    }
    
    function updateEditSummary() {
        const startDate = editStartDateInput?.value;
        const endDate = editEndDateInput?.value;
        
        if (startDate) {
            // Convertir a formato DD/MM/YYYY
            const [startYear, startMonth, startDay] = startDate.split('-');
            const formattedStart = `${startDay}/${startMonth}/${startYear}`;
            
            if (endDate) {
                const [endYear, endMonth, endDay] = endDate.split('-');
                const formattedEnd = `${endDay}/${endMonth}/${endYear}`;
                
                editSummaryPeriod.textContent = `${formattedStart} al ${formattedEnd}`;
                
                // Calcular días de diferencia
                const start = new Date(startYear, startMonth - 1, startDay, 12, 0, 0);
                const end = new Date(endYear, endMonth - 1, endDay, 12, 0, 0); 
                
                const diffTime = end.getTime() - start.getTime();
                const diffDays = Math.round(diffTime / (1000 * 60 * 60 * 24));
                
                // Mostrar valor absoluto
                const absDiffDays = Math.abs(diffDays);
                editSummaryDuration.textContent = `${absDiffDays} día${absDiffDays !== 1 ? 's' : ''}`;
                
                // Si la fecha de fin es anterior, mostrar advertencia
                if (diffDays < 0) {
                    editSummaryDuration.classList.add('text-danger');
                    editSummaryDuration.innerHTML += ' <small class="text-danger">(Fecha anterior)</small>';
                } else {
                    editSummaryDuration.classList.remove('text-danger');
                }
            } else {
                editSummaryPeriod.textContent = `${formattedStart} (Indefinido)`;
                editSummaryDuration.textContent = 'Indefinido';
                editSummaryDuration.classList.remove('text-danger');
            }
        } else {
            editSummaryPeriod.textContent = 'Por definir';
            editSummaryDuration.textContent = '-';
            editSummaryDuration.classList.remove('text-danger');
        }
    }
    
    // Validación del formulario
    if (editAssignForm) {
        editAssignForm.addEventListener('submit', function(e) {
            // Deshabilitar el botón para evitar múltiples envíos
            if (submitEditBtn) {
                submitEditBtn.disabled = true;
                submitEditBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Actualizando...';
            }
            
            // Validar fechas
            const startDate = editStartDateInput?.value;
            const endDate = editEndDateInput?.value;
            
            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                
                if (end < start) {
                    e.preventDefault();
                    alert('La fecha de fin no puede ser anterior a la fecha de inicio.');
                    if (submitEditBtn) {
                        submitEditBtn.disabled = false;
                        submitEditBtn.innerHTML = '<i class="bi bi-check me-1"></i>Actualizar Asignación';
                    }
                    return false;
                }
            }
            
            // Validar que se haya seleccionado vehículo y usuario
            const vehicleId = editVehicleSelect?.value;
            const userId = editUserSelect?.value;
            
            if (!vehicleId || !userId) {
                e.preventDefault();
                alert('Por favor, selecciona tanto un vehículo como un usuario.');
                if (submitEditBtn) {
                    submitEditBtn.disabled = false;
                    submitEditBtn.innerHTML = '<i class="bi bi-check me-1"></i>Actualizar Asignación';
                }
                return false;
            }
            
            return true;
        });
    }
    
    // Resetear el modal cuando se cierre
    if (editAssignModal) {
        editAssignModal.addEventListener('hidden.bs.modal', function() {
            if (editAssignForm) {
                editAssignForm.reset();
            }
          
            if (submitEditBtn) {
                submitEditBtn.disabled = false;
                submitEditBtn.innerHTML = '<i class="bi bi-check me-1"></i>Actualizar Asignación';
            }
           
            editSummaryVehicle.textContent = 'No seleccionado';
            editSummaryUser.textContent = 'No seleccionado';
            editSummaryPeriod.textContent = 'Por definir';
            editSummaryDuration.textContent = '-';
            
            if (editVehicleInfo) editVehicleInfo.innerHTML = '';
            if (editUserInfo) editUserInfo.innerHTML = '';
            
            document.querySelectorAll('.is-invalid').forEach(element => {
                element.classList.remove('is-invalid');
            });
        });
    }
});
</script>
