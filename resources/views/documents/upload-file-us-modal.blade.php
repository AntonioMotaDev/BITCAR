<div class="modal fade" id="uploadDocumentUsModal" tabindex="-1" aria-labelledby="uploadDocumentUsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadDocumentUsModalLabel">
                    <i class="bi bi-file-earmark-arrow-up me-2"></i>Subir Documento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="uploadDocumentUsForm" action="{{ route('users.documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_id" id="document_user_id">
                
                <div class="modal-body">
                    <!-- Nombre del Documento -->
                    <div class="mb-3">
                        <label for="us_file_name" class="form-label">
                            <i class="bi bi-file-text-fill me-1"></i>Nombre del Documento *
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="us_file_name" 
                               name="file_name" 
                               placeholder="Ej: Póliza de Seguro"
                               required>
                    </div>
                    
                    <!-- Archivo -->
                    <div class="mb-3">
                        <label for="us_document_file" class="form-label">
                            <i class="bi bi-paperclip me-1"></i>Archivo *
                        </label>
                        <input type="file" 
                               class="form-control" 
                               id="us_document_file" 
                               name="document_file"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.txt"
                               required>
                           <small class="text-muted">Formatos permitidos: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG, TXT. Tamano maximo: 5MB</small>
                           <div class="invalid-feedback" id="us_document_file_error"></div>
                    </div>
                    
                    <!-- Fecha de Expiración -->
                    <div class="mb-3">
                        <label for="us_expiration_date" class="form-label">
                            <i class="bi bi-calendar-date me-1"></i>Fecha de Expiración (Opcional)
                        </label>
                        <input type="date" 
                               class="form-control" 
                               id="us_expiration_date" 
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
    const uploadDocumentUsModal = document.getElementById('uploadDocumentUsModal');
    const uploadDocumentUsForm = document.getElementById('uploadDocumentUsForm');
    const usDocumentFile = document.getElementById('us_document_file');
    const usDocumentFileError = document.getElementById('us_document_file_error');
    const usSubmitButton = uploadDocumentUsForm?.querySelector('button[type="submit"]');

    const maxFileSizeBytes = 5 * 1024 * 1024;
    const allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'txt'];

    function validateUserFile() {
        if (!usDocumentFile || !usDocumentFile.files || usDocumentFile.files.length === 0) {
            usDocumentFile?.classList.remove('is-invalid');
            if (usDocumentFileError) usDocumentFileError.textContent = '';
            if (usSubmitButton) usSubmitButton.disabled = false;
            return true;
        }

        const file = usDocumentFile.files[0];
        const extension = file.name.split('.').pop()?.toLowerCase() || '';
        let errorMessage = '';

        if (!allowedExtensions.includes(extension)) {
            errorMessage = 'Formato no compatible. Usa: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG o TXT.';
        } else if (file.size > maxFileSizeBytes) {
            errorMessage = 'El archivo supera 5MB. Selecciona uno mas pequeno.';
        }

        if (errorMessage) {
            usDocumentFile.classList.add('is-invalid');
            if (usDocumentFileError) usDocumentFileError.textContent = errorMessage;
            if (usSubmitButton) usSubmitButton.disabled = true;
            return false;
        }

        usDocumentFile.classList.remove('is-invalid');
        if (usDocumentFileError) usDocumentFileError.textContent = '';
        if (usSubmitButton) usSubmitButton.disabled = false;
        return true;
    }

    usDocumentFile?.addEventListener('change', validateUserFile);

    uploadDocumentUsForm?.addEventListener('submit', function(event) {
        if (!validateUserFile()) {
            event.preventDefault();
            event.stopPropagation();
        }
    });
    
    if (uploadDocumentUsModal) {
        uploadDocumentUsModal.addEventListener('shown.bs.modal', function(event) {
            const button = event.relatedTarget;
            
            if (button) {
                const userId = button.getAttribute('data-user-id');
                const userIdInput = this.querySelector('#document_user_id');
                
                if (userIdInput && userId) {
                    userIdInput.value = userId;
                }
            }

            usDocumentFile?.classList.remove('is-invalid');
            if (usDocumentFileError) usDocumentFileError.textContent = '';
            if (usSubmitButton) usSubmitButton.disabled = false;
        });
    }
});
</script>