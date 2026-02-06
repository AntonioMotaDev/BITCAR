<div class="modal fade" id="assignVehicleModal" tabindex="-1" aria-labelledby="assignVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignVehicleModalLabel">
                    <i class="bi bi-truck me-2"></i>Asignar Unidad Vehicular
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignVehicleForm" action="{{ route('vehicles.assignment.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Información de la Asignación -->
                        <div class="col-md-6">
                            <h6 class="fw-bold text-prim mb-3">
                                <i class="bi bi-info-circle me-2"></i>Datos de la Asignación
                            </h6>
                            
                            <!-- Vehículo -->
                            <div class="mb-3">
                                <label for="vehicle_id" class="form-label">
                                    <i class="bi bi-truck me-1"></i>Vehículo *
                                </label>
                                <select class="form-select @error('vehicle_id') is-invalid @enderror" 
                                        id="vehicle_id" 
                                        name="vehicle_id" 
                                        required>
                                    <option value="" disabled selected>Selecciona un vehículo</option>
                                    @foreach($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}" 
                                                {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}
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
                                <small class="text-muted mt-1 d-block" id="vehicle-info">
                                    <!-- Información del vehículo seleccionado aparecerá aquí -->
                                </small>
                            </div>
                            
                            <!-- Usuario/Conductor -->
                            <div class="mb-3">
                                <label for="user_id" class="form-label">
                                    <i class="bi bi-person me-1"></i>Conductor/Usuario *
                                </label>
                                <select class="form-select @error('user_id') is-invalid @enderror" 
                                        id="user_id" 
                                        name="user_id" 
                                        required>
                                    <option value="" disabled selected>Selecciona un usuario</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" 
                                                {{ old('user_id') == $user->id ? 'selected' : '' }}
                                                data-role="{{ $user->role }}"
                                                data-email="{{ $user->email }}">
                                            {{ $user->name }} ({{ $user->role }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted mt-1 d-block" id="user-info">
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
                                <label for="start_date" class="form-label">
                                    <i class="bi bi-calendar-check me-1"></i>Fecha de Inicio *
                                </label>
                                <input type="date" 
                                       class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" 
                                       name="start_date" 
                                       value="{{ old('start_date', date('Y-m-d')) }}"
                                       required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Fecha en que inicia la asignación</small>
                            </div>
                            
                            <!-- Fecha de Fin -->
                            <div class="mb-3">
                                <label for="end_date" class="form-label">
                                    <i class="bi bi-calendar-x me-1"></i>Fecha de Fin (Opcional)
                                </label>
                                <input type="date" 
                                       class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" 
                                       name="end_date" 
                                       value="{{ old('end_date') }}"
                                       min="{{ date('Y-m-d') }}">
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
                                    <span id="summary-vehicle" class="fw-medium">No seleccionado</span>
                                </div>
                                <div class="d-flex mb-1">
                                    <span class="text-muted me-2" style="min-width: 120px;">Conductor:</span>
                                    <span id="summary-user" class="fw-medium">No seleccionado</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex mb-1">
                                    <span class="text-muted me-2" style="min-width: 120px;">Período:</span>
                                    <span id="summary-period" class="fw-medium">Por definir</span>
                                </div>
                                <div class="d-flex mb-1">
                                    <span class="text-muted me-2" style="min-width: 120px;">Duración:</span>
                                    <span id="summary-duration" class="fw-medium">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-create" id="submitAssignBtn">
                        <i class="bi bi-check me-1"></i>Asignar Vehículo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript para el modal -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const assignModal = document.getElementById('assignVehicleModal');
    const vehicleSelect = document.getElementById('vehicle_id');
    const userSelect = document.getElementById('user_id');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const assignForm = document.getElementById('assignVehicleForm');
    const submitBtn = document.getElementById('submitAssignBtn');
    
    // Elementos para el resumen
    const summaryVehicle = document.getElementById('summary-vehicle');
    const summaryUser = document.getElementById('summary-user');
    const summaryPeriod = document.getElementById('summary-period');
    const summaryDuration = document.getElementById('summary-duration');
    const vehicleInfo = document.getElementById('vehicle-info');
    const userInfo = document.getElementById('user-info');
    
    // Actualizar información del vehículo seleccionado
    if (vehicleSelect) {
        vehicleSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const plate = selectedOption.getAttribute('data-plate');
                const model = selectedOption.getAttribute('data-model');
                const brand = selectedOption.getAttribute('data-brand');
                const color = selectedOption.getAttribute('data-color');
    
                vehicleInfo.innerHTML = `<strong>${brand} ${model}</strong> - Placa: ${plate} - Color: ${color}`;
          
                summaryVehicle.textContent = `${plate} - ${brand} ${model} ${color}`;
            } else {
                vehicleInfo.innerHTML = '';
                summaryVehicle.textContent = 'No seleccionado';
            }
            updateSummary();
        });
    }
    
    // Actualizar información del usuario seleccionado
    if (userSelect) {
        userSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const name = selectedOption.text.split('(')[0].trim();
                const role = selectedOption.getAttribute('data-role');
                const email = selectedOption.getAttribute('data-email');
  
                userInfo.innerHTML = `<strong>${role}</strong> - ${email}`;
           
                summaryUser.textContent = `${name} (${role})`;
            } else {
                userInfo.innerHTML = '';
                summaryUser.textContent = 'No seleccionado';
            }
            updateSummary();
        });
    }
    
    // Actualizar fechas y calcular duración
    if (startDateInput) {
        startDateInput.addEventListener('change', updateSummary);
    }
    
    if (endDateInput) {
        endDateInput.addEventListener('change', updateSummary);
        
        // Establecer fecha mínima como fecha de inicio
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = this.value;
            }
            updateSummary();
        });
    }
    
    function updateSummary() {
        const startDate = startDateInput?.value;
        const endDate = endDateInput?.value;
        
        if (startDate) {
            // Convertir a formato DD/MM/YYYY
            const [startYear, startMonth, startDay] = startDate.split('-');
            const formattedStart = `${startDay}/${startMonth}/${startYear}`;
            
            if (endDate) {
                const [endYear, endMonth, endDay] = endDate.split('-');
                const formattedEnd = `${endDay}/${endMonth}/${endYear}`;
                
                summaryPeriod.textContent = `${formattedStart} al ${formattedEnd}`;
                
                // Calcular días de diferencia
                const start = new Date(startYear, startMonth - 1, startDay, 12, 0, 0);
                const end = new Date(endYear, endMonth - 1, endDay, 12, 0, 0); 
                
                const diffTime = end.getTime() - start.getTime();
                const diffDays = Math.round(diffTime / (1000 * 60 * 60 * 24));
                
                // Mostrar valor absoluto
                const absDiffDays = Math.abs(diffDays);
                summaryDuration.textContent = `${absDiffDays} día${absDiffDays !== 1 ? 's' : ''}`;
                
                // Si la fecha de fin es anterior, mostrar advertencia
                if (diffDays < 0) {
                    summaryDuration.classList.add('text-danger');
                    summaryDuration.innerHTML += ' <small class="text-danger">(Fecha anterior)</small>';
                } else {
                    summaryDuration.classList.remove('text-danger');
                }
            } else {
                summaryPeriod.textContent = `${formattedStart} (Indefinido)`;
                summaryDuration.textContent = 'Indefinido';
                summaryDuration.classList.remove('text-danger');
            }
        } else {
            summaryPeriod.textContent = 'Por definir';
            summaryDuration.textContent = '-';
            summaryDuration.classList.remove('text-danger');
        }
    }
    // Validación del formulario
    if (assignForm) {
        assignForm.addEventListener('submit', function(e) {
            // Deshabilitar el botón para evitar múltiples envíos
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Asignando...';
            }
            
            // Validar fechas
            const startDate = startDateInput?.value;
            const endDate = endDateInput?.value;
            
            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                
                if (end < start) {
                    e.preventDefault();
                    alert('La fecha de fin no puede ser anterior a la fecha de inicio.');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="bi bi-check me-1"></i>Asignar Vehículo';
                    }
                    return false;
                }
            }
            
            // Validar que se haya seleccionado vehículo y usuario
            const vehicleId = vehicleSelect?.value;
            const userId = userSelect?.value;
            
            if (!vehicleId || !userId) {
                e.preventDefault();
                alert('Por favor, selecciona tanto un vehículo como un usuario.');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-check me-1"></i>Asignar Vehículo';
                }
                return false;
            }
            
            return true;
        });
    }
    
    // Resetear el modal cuando se cierre
    if (assignModal) {
        assignModal.addEventListener('hidden.bs.modal', function() {
      
            if (assignForm) {
                assignForm.reset();
            }
          
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-check me-1"></i>Asignar Vehículo';
            }
           
            summaryVehicle.textContent = 'No seleccionado';
            summaryUser.textContent = 'No seleccionado';
            summaryPeriod.textContent = 'Por definir';
            summaryDuration.textContent = '-';
            
            if (vehicleInfo) vehicleInfo.innerHTML = '';
            if (userInfo) userInfo.innerHTML = '';
            
            document.querySelectorAll('.is-invalid').forEach(element => {
                element.classList.remove('is-invalid');
            });
            document.querySelectorAll('.invalid-feedback').forEach(element => {
                element.remove();
            });
            
            if (endDateInput) {
                endDateInput.min = new Date().toISOString().split('T')[0];
            }
        });
        
        assignModal.addEventListener('shown.bs.modal', function() {
            const today = new Date().toISOString().split('T')[0];
            if (startDateInput && !startDateInput.value) {
                startDateInput.value = today;
            }
            if (endDateInput) {
                endDateInput.min = startDateInput?.value || today;
            }
            updateSummary();
        });
    }
    
    // Mostrar errores de validación del servidor
    @if($errors->any() && old('_token'))
        const modal = new bootstrap.Modal(document.getElementById('assignVehicleModal'));
        modal.show();
    @endif
});
</script>