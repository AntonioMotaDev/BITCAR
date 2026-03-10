<div class="modal fade" id="uploadDocumentVhModal" tabindex="-1" aria-labelledby="uploadDocumentVhModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadDocumentVhModalLabel">
                    <i class="bi bi-file-earmark-arrow-up me-2"></i>Subir Documento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="uploadDocumentVhForm" action="{{ route('vehicles.documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="vehicle_id" id="document_vehicle_id">
                
                <div class="modal-body">
                    <!-- Nombre del Documento -->
                    <div class="mb-3">
                        <label for="vh_file_name" class="form-label">
                            <i class="bi bi-file-text-fill me-1"></i>Nombre del Documento *
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="vh_file_name" 
                               name="file_name" 
                               placeholder="Ej: Póliza de Seguro"
                               required>
                    </div>
                    
                    <!-- Archivo -->
                    <div class="mb-3">
                        <label for="vh_document_file" class="form-label">
                            <i class="bi bi-paperclip me-1"></i>Archivo *
                        </label>
                        <input type="file" 
                               class="form-control" 
                               id="vh_document_file" 
                               name="document_file"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.txt"
                               required>
                           <small class="text-muted">Formatos permitidos: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG, TXT. Tamano maximo: 5MB</small>
                           <div class="invalid-feedback" id="vh_document_file_error"></div>
                    </div>
                    
                    <!-- Fecha de Expiración -->
                    <div class="mb-3">
                        <label for="vh_expiration_date" class="form-label">
                            <i class="bi bi-calendar-date me-1"></i>Fecha de Expiración (Opcional)
                        </label>
                        <input type="date" 
                               class="form-control" 
                               id="vh_expiration_date" 
                               name="expiration_date">
                        <small class="text-muted">Selecciona la fecha en que expira este documento</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-create">
                        <i class="bi bi-check me-1"></i>Subir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadDocumentVhModal = document.getElementById('uploadDocumentVhModal');
    const uploadDocumentVhForm = document.getElementById('uploadDocumentVhForm');
    const vhDocumentFile = document.getElementById('vh_document_file');
    const vhDocumentFileError = document.getElementById('vh_document_file_error');
    const vhSubmitButton = uploadDocumentVhForm?.querySelector('button[type="submit"]');

    const maxFileSizeBytes = 5 * 1024 * 1024;
    const allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'txt'];

    function validateVehicleFile() {
        if (!vhDocumentFile || !vhDocumentFile.files || vhDocumentFile.files.length === 0) {
            vhDocumentFile?.classList.remove('is-invalid');
            if (vhDocumentFileError) vhDocumentFileError.textContent = '';
            if (vhSubmitButton) vhSubmitButton.disabled = false;
            return true;
        }

        const file = vhDocumentFile.files[0];
        const extension = file.name.split('.').pop()?.toLowerCase() || '';
        let errorMessage = '';

        if (!allowedExtensions.includes(extension)) {
            errorMessage = 'Formato no compatible. Usa: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG o TXT.';
        } else if (file.size > maxFileSizeBytes) {
            errorMessage = 'El archivo supera 5MB. Selecciona uno mas pequeno.';
        }

        if (errorMessage) {
            vhDocumentFile.classList.add('is-invalid');
            if (vhDocumentFileError) vhDocumentFileError.textContent = errorMessage;
            if (vhSubmitButton) vhSubmitButton.disabled = true;
            return false;
        }

        vhDocumentFile.classList.remove('is-invalid');
        if (vhDocumentFileError) vhDocumentFileError.textContent = '';
        if (vhSubmitButton) vhSubmitButton.disabled = false;
        return true;
    }

    vhDocumentFile?.addEventListener('change', validateVehicleFile);

    uploadDocumentVhForm?.addEventListener('submit', function(event) {
        if (!validateVehicleFile()) {
            event.preventDefault();
            event.stopPropagation();
        }
    });
    
    if (uploadDocumentVhModal) {
        uploadDocumentVhModal.addEventListener('shown.bs.modal', function(event) {
            const button = event.relatedTarget;
            
            if (button) {
                const vehicleId = button.getAttribute('data-vehicle-id');
                const vehicleIdInput = this.querySelector('#document_vehicle_id');
                
                if (vehicleIdInput && vehicleId) {
                    vehicleIdInput.value = vehicleId;
                }
            }

            vhDocumentFile?.classList.remove('is-invalid');
            if (vhDocumentFileError) vhDocumentFileError.textContent = '';
            if (vhSubmitButton) vhSubmitButton.disabled = false;
        });
    }
});
</script>