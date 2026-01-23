<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h2 fw-bold text-dark mb-0">
                GESTIONAR PERSONAL
            </h2>
            <div>
            <button type="button" class="btn btn-add" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="bi bi-plus-lg"></i> Crear Nuevo Usuario
            </button>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4 px-4">
        <!-- Contenedor principal con espacio entre columnas -->
        <div class="row"> 
            <!-- Información del usuario -->
            <div class="col-lg-4"> 
                <!-- Tarjeta Principal del Usuario -->
                <div class="card card-custom border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <!-- Encabezado con avatar -->
                        <div class="d-flex align-items-start mb-4">
                            <div class="avatar-large me-4 flex-shrink-0" id="avatar-container">
                                <!-- Imagen del usuario -->
                                    <img src="/storage/{{ $users->first()->image }}" 
                                        alt="Avatar" 
                                        class="user-image rounded-circle w-100 h-100 object-fit-cover">
                                <!-- Icono por defecto -->
                                <div class="avatar-small d-flex align-items-center justify-content-center w-100 h-100">
                                    <i class="bi bi-person-fill fs-1 text-muted" style="display: none;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 info-header">
                                <h1 class="h4 fw-bold  mb-1 user-name">
                                    {{ $users->first()->name ?? 'Seleccione un usuario' }}
                                </h1>
                                <p class="h6  mb-0 user-id">
                                    {{ $users->first()->id ? 'ID: ' . $users->first()->id : '' }}
                                </p>
                            </div>
                        </div>

                        <!-- Información Personal -->
                        <div class="row">
                            <div class="col">
                                <div class="col-12">
                                    <p class="info-label">Cargo</p>
                                    <p class="info-value user-role">{{ $users->first()-> role ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="col-12">
                                    <p class="info-label">Correo</p>
                                    <p class="info-value user-email">{{ $users->first()->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="col-12">
                                    <p class="info-label">Estado</p>
                                    <p class="info-value user-status-badge">{{ $users->first()->status ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Botón Editar -->
                        <div class="position-relative" style="min-height: 60px;" >
                            <button href="#" class="btn btn-edit position-absolute bottom-0 end-0 d-flex align-items-center px-4 py-2 btn-edit-left"
                                data-bs-toggle="modal" 
                                data-bs-target="#editUserModal"
                                title="Editar Usuario">
                                <i class="bi bi-pencil-square me-2"></i>
                                Editar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Documentos -->
                <div class="card card-custom border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h5 fw-bold text-dark mb-3">Documentos del Personal</h3>
                        <div class="document-list user-documents">
                            <!-- Contenedor para documentos dinámicos -->
                            <div id="userDocumentsContainer">
                                <!-- Mensaje inicial -->
                                <div class="text-center py-4">
                                    <i class="bi bi-file-earmark-text fs-1 text-muted"></i>
                                    <p class="text-muted small mt-2 mb-0">Seleccione un usuario para ver sus documentos</p>
                                </div>
                            </div>
                        </div>
                        <div class="position-relative" style="min-height: 60px;">
                            <button href="#" class="btn btn-edit position-absolute bottom-0 end-0 d-flex align-items-center px-4 py-2 btn-upload-left"
                                data-bs-toggle="modal" 
                                data-bs-target="#uploadDocumentUsModal"
                                title="Subir Documento">
                                <i class="bi bi-upload me-2"></i> Subir
                                
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usuarios Registrados -->
            <div class="col-lg-8">
                <div>
                    <div class="card-body">
                        <!-- Barra de búsqueda -->
                        <div class="row mb-4">
                            <div class="search-container">
                                    <div class="input-group search-box  rounded-pill overflow-hidden">
                                        <span class="input-group-text bg-white border-end-0">
                                        </span>
                                        <input type="text" class="form-control border-start-0" placeholder="Buscar usuario..." aria-label="Buscar usuario">
                                        <button class="btn  bg-white"  type="button">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                            </div>
                            <div class="col-12 py-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h2 class="h3 fw-bold text-secondary mb-0">Usuarios Registrados</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Lista de Usuarios -->
                        <div class="table-responsive card card-custom">
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr class="table-light">
                                            <th scope="col" class="ps-3">Usuario</th>
                                            <th scope="col">ID</th>
                                            <th scope="col">Cargo</th>
                                            <th scope="col">Estado</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                            <tr class="user-row {{ $loop->first ? 'active' : '' }}"
                                                data-user-id="{{ $user->id }}"
                                                data-user-name="{{ $user->name }}"
                                                data-user-email="{{ $user->email }}"
                                                data-user-role="{{ $user->role }}"
                                                data-user-status="{{ $user->status }}"
                                                data-user-image="{{ $user->image }}" 
                                                data-user-documents='@json($user->userDocuments ?? [])'>
                                                <!-- Resto del contenido de la fila -->
                                                <td class="ps-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-small bg-primary bg-opacity-10 me-3">
                                                            <i class="bi bi-person-fill text-prim"></i>
                                                        </div>
                                                        <div>
                                                            <h3 class="h6 fw-bold mb-0">{{$user->name}}</h3>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{$user->id}}</td>
                                                <td>{{$user->role}}</td>
                                                <td>{{$user->status}}</td>
                                                <td class="text-end pe-3">
                                                    <div class="d-flex gap-2 justify-content-end">
                                                        <button class="btn btn-eye btn-sm btn-view-user" 
                                                                data-user-id="{{ $user->id }}"
                                                                title="Ver detalles">
                                                            <i class="bi bi-eye"></i> Ver
                                                        </button>
                                                        <button class="btn btn-edit" 
                                                                data-user-id="{{ $user->id }}"
                                                                data-user-name="{{ $user->name }}"
                                                                data-user-email="{{ $user->email }}"
                                                                data-user-role="{{ $user->role }}"
                                                                data-user-status="{{ $user->status }}"
                                                                data-user-image="/storage/{{ $user->image }}"
                                                                title="Editar usuario"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#editUserModal">
                                                            <i class="bi bi-pencil-square"></i> Editar
                                                        </button>
                                                        <button class="btn btn-delete" 
                                                                data-user-id="{{ $user->id }}"
                                                                title="Eliminar usuario">
                                                            <i class="bi bi-trash"></i> Eliminar
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Paginación -->
                        <div class="d-flex justify-content-end mt-2 pt-3 border-top">
                             {{ $users->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('users.create-modal')
    @include('users.edit-modal')
    @include('documents.upload-file-us-modal')

     <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentUserDocuments = [];
            let currentSelectedUserId = null;
            // Cargar la información del usuario
            function loadUserInfo(userRow) {
                const userId = userRow.getAttribute('data-user-id');
                const userName = userRow.getAttribute('data-user-name');
                const userEmail = userRow.getAttribute('data-user-email');
                const userRole = userRow.getAttribute('data-user-role');
                const userStatus = userRow.getAttribute('data-user-status');
                const userImage = userRow.getAttribute('data-user-image');
                const userDocuments = JSON.parse(userRow.getAttribute('data-user-documents') || '[]');
                
    
                // Actualizar avatar
                updateUserAvatar(userImage, userName);
                 
                // Actualizar información principal
                document.querySelector('.user-name').textContent = userName;
                document.querySelector('.user-id').textContent = `ID: ${userId}`;
                document.querySelector('.user-role').textContent = userRole;
                document.querySelector('.user-email').textContent = userEmail;
                document.querySelector('.user-image').src = `/storage/${userImage}`;
                

                
                currentSelectedUserId = userId;

                // Mostrar documentos
                loadUserDocuments(userDocuments,userId);

                updateLeftEditButton(userRow);
                updateLeftUploadButton(userRow);
                
                // Resaltar fila activa
                highlightActiveRow(userId);
                
                // Actualizar URL
                history.pushState(null, '', `?user=${userId}`);
            }

            function updateUserAvatar(userImage, userName) {
                const imgElement = document.querySelector('.user-image');
                const iconElement = document.querySelector('.avatar-small i');
                
                if (userImage) {
                    // Si tiene imagen mostrarla
                    imgElement.src = `/storage/${userImage}`;
                    imgElement.alt = `Avatar de ${userName}`;
                    imgElement.style.display = 'block';
                    
                    if (iconElement) {
                        iconElement.style.display = 'none';
                    }
                    
                    // Manejar error si la imagen no carga
                    imgElement.onerror = function() {
                        imgElement.style.display = 'none';
                        if (iconElement) {
                            iconElement.style.display = 'block';
                        }
                    };
                    
                } else {
                    // Mostrar icono
                    imgElement.style.display = 'none';
                    
                    if (iconElement) {
                        iconElement.style.display = 'block';
                    }
                }
            }

            function updateLeftEditButton(userRow) {
                const leftEditButton = document.querySelector('.btn-edit-left');
                
                if (!leftEditButton) {
                    console.warn('Botón de editar izquierdo no encontrado');
                    return;
                }
                
                // Copiar todos los atributos al botón izquierdo
                const attributes = [
                    'data-user-id',
                    'data-user-name',
                    'data-user-email',
                    'data-user-role',
                    'data-user-image', 
                    'data-user-status'
                ];
                
                attributes.forEach(attr => {
                    const value = userRow.getAttribute(attr);
                    if (value !== null && value !== undefined) {
                        leftEditButton.setAttribute(attr, value);
                    }
                });
                
            }

            function updateLeftUploadButton(userRow) {
                const leftUploadButton = document.querySelector('.btn-upload-left');
                
                if (!leftUploadButton) {
                    console.warn('Botón de subir documento izquierdo no encontrado');
                    return;
                }
                
                // Copiar el atributo id al botón subir izquierdo
                const attributes = [
                    'data-user-id'
                ];
                
                attributes.forEach(attr => {
                    const value = userRow.getAttribute(attr);
                    if (value !== null && value !== undefined) {
                        leftUploadButton.setAttribute(attr, value);
                    }
                });
            }
            
            function highlightActiveRow(userId) {
                // Remover clase 'active' de todas las filas
                document.querySelectorAll('.user-row').forEach(row => {
                    row.classList.remove('active');
                });
                
                // Agregar clase 'active' a la fila seleccionada
                const activeRow = document.querySelector(`.user-row[data-user-id="${userId}"]`);
                if (activeRow) {
                    activeRow.classList.add('active');
                }
            }
            
            // Mostrar mensaje sin resultados
            function showNoResultsMessage() {
                document.querySelector('.user-name').textContent = 'No se encontraron usuarios';
                document.querySelector('.user-id').textContent = '';
                document.querySelector('.user-role').textContent = 'N/A';
                document.querySelector('.user-email').textContent = 'N/A';
            }
            
            // Eventos para botones "Ver"
            document.querySelectorAll('.btn-view-user').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const userRow = this.closest('.user-row');
                    loadUserInfo(userRow);
                });
            });
            
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const userId = this.getAttribute('data-user-id');
                    if (confirm(`¿Está seguro de eliminar al usuario ${userId}?`)) {
                        // Aquí iría la lógica para eliminar
                        console.log(`Eliminar usuario ${userId}`);
                    }
                });
            });
            
            // Incializar con el primer usuario
            const firstUserRow = document.querySelector('.user-row');
            if (firstUserRow) {
                loadUserInfo(firstUserRow);
            }
            
            // Cargar usuario desde la URL
            const urlParams = new URLSearchParams(window.location.search);
            const userIdFromUrl = urlParams.get('user');
            if (userIdFromUrl) {
                const userRow = document.querySelector(`.user-row[data-user-id="${userIdFromUrl}"]`);
                if (userRow) {
                    loadUserInfo(userRow);
                }
            }
    
            /**
             * Cargar y mostrar documentos del usuario
             */
            function loadUserDocuments(documents,userId) {
                currentUserDocuments = documents;
                renderUserDocuments(userId);
            }
            
            /**
             * Renderizar documentos en la interfaz
             */
            function renderUserDocuments(userId) {
                const container = document.querySelector('.user-documents');
                
                if (!container) {
                    console.error('Contenedor .user-documents no encontrado');
                    return;
                }
                
                if (currentUserDocuments && currentUserDocuments.length > 0) {
                    let documentsHTML = `
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="small fw-bold text-prim">Documento</th>
                                        <th class="small fw-bold text-prim">Estado</th>
                                        <th class="small fw-bold text-prim">Vencimiento</th>
                                        <th class="small fw-bold text-prim"></th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;
                    
                    currentUserDocuments.forEach((doc, index) => {
                        // Determinar estado
                        const status = getDocumentStatus(doc);
                        
                        // Formatear fecha de vencimiento
                        const expirationDate = doc.expiration_date 
                            ? formatDate(doc.expiration_date)
                            : 'Sin fecha';
                        
                        // Determinar si está vencido
                        const isExpired = checkIfExpired(doc.expiration_date);
                        
                        documentsHTML += `
                            <tr class=" border-bottom">
                                <td style="vertical-align: middle;">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-file-earmark me-2 text-prim"></i>
                                        <div>
                                            <div class="fw-medium small">${doc.file_name || doc.name || 'Documento'}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="vertical-align: middle;">
                                    ${doc.status ? `<div class="small text-prim">${doc.status}</div>` : ''}
                                </td>
                                <td style="vertical-align: middle;">
                                    <div class="small ${isExpired ? 'text-danger' : 'text-muted'}">
                                        ${expirationDate}
                                        ${isExpired ? '<br><small class="text-danger">¡Vencido!</small>' : ''}
                                    </div>
                                </td>
                                <td style="vertical-align: middle;">
                                    <div class="d-flex gap-1">
                                        ${doc.path ? `
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-primary btn-view-document"
                                                    data-document-id="${doc.id}"
                                                    data-file-name="${doc.file_name || doc.name}"
                                                    data-file-path="${doc.path}"
                                                    title="Ver documento">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-success btn-download-document"
                                                    data-document-id="${doc.id}"
                                                    data-file-name="${doc.file_name || doc.name}"
                                                    data-file-path="${doc.path}"
                                                    title="Descargar">
                                                <i class="bi bi-download"></i>
                                            </button>
                                        ` : ''}
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    
                    documentsHTML += `
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Resumen -->
                        <div class="mt-3 p-2 bg-light rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-files me-1"></i>
                                    Total: ${currentUserDocuments.length} documentos
                                </small>
                                <small class="text-muted">
                                    Vencidos: ${currentUserDocuments.filter(d => checkIfExpired(d.expiration_date)).length}
                                </small>
                            </div>
                        </div>
                    `;
                    
                    container.innerHTML = documentsHTML;
                    
                    // Agregar event listeners a los botones de documentos
                    addDocumentEventListeners(userId);
                    
                } else {
                    container.innerHTML = `
                        <div class="text-center py-4">
                            <i class="bi bi-file-earmark-x fs-1 text-muted"></i>
                            <p class="text-muted small mt-2 mb-1">No hay documentos disponibles</p>
                            <p class="text-muted extra-small">Este usuario no tiene documentos registrados</p>
                        </div>
                    `;
                }
            }
            
            /**
             * Determinar estado del documento
             */
            function getDocumentStatus(document) {
                if (!document.expiration_date) {
                    return 'pending';
                }
                
                const expirationDate = new Date(document.expiration_date);
                const today = new Date();
                
                if (expirationDate < today) {
                    return 'expired';
                }
                
                // Si vence en los próximos 7 días
                const oneWeekFromNow = new Date();
                oneWeekFromNow.setDate(today.getDate() + 7);
                
                if (expirationDate <= oneWeekFromNow) {
                    return 'expiring';
                }
                
                return 'valid';
            }
            
            /**
             * Verificar si un documento está vencido
             */
            function checkIfExpired(expirationDate) {
                if (!expirationDate) return false;
                
                const expDate = new Date(expirationDate);
                const today = new Date();
                
                return expDate < today;
            }
            
            /**
             * Formatear fecha
             */
            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('es-ES', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            }
            
            
            /**
             * Agregar event listeners a los botones de documentos
             */
            function addDocumentEventListeners(currentUserId) {
                document.querySelectorAll('.btn-view-document').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const documentId = this.getAttribute('data-document-id');
                        const userId = currentUserId; // Obtener el ID del usuario actual
                        const filePath = this.getAttribute('data-file-path');
                        if (documentId && userId) {
                            // Construir la URL con ambos parámetros
                            const viewUrl = `/storage/${filePath}`;
                            window.open(viewUrl, '_blank');
                        } else {
                            alert('Documento no disponible para visualización');
                        }
                    });
                });
                
                // Descargar documento
                document.querySelectorAll('.btn-download-document').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const documentId = this.getAttribute('data-document-id');
                        const fileName = this.getAttribute('data-file-name');
                        const filePath = this.getAttribute('data-file-path');
                        downloadDocument(documentId, fileName, filePath);
                    });
                });
                
            }
            
           function downloadDocument(documentId, fileName, filePath) {
                
                if (!filePath) {
                    alert('Error: No se especificó la ruta del archivo');
                    return;
                }
    
                const baseUrl = window.location.origin;
                let fullPath = filePath;
                
                // Si la ruta no comienza con /storage/, agregarlo
                if (!fullPath.startsWith('/storage/') && !fullPath.startsWith('storage/')) {
                    // Quitar / inicial si existe y agregar /storage/
                    fullPath = '/storage/' + fullPath.replace(/^\//, '');
                }
                
                // Construir URL completa
                const fullUrl = baseUrl + (fullPath.startsWith('/') ? fullPath : '/' + fullPath);
                
                // Descarga directa
                const link = document.createElement('a');
                link.href = fullUrl;
                link.download = fileName || 'documento.pdf';
                link.style.display = 'none';
                
                // forzar atributo download
                link.setAttribute('download', fileName || 'documento.pdf');
                
                document.body.appendChild(link);
                
                // Intentar click
                link.click();
                
                // Limpiar
                setTimeout(() => {
                    if (document.body.contains(link)) {
                        document.body.removeChild(link);
                    }
                }, 2000);
            }

        });
    </script>

</x-app-layout>