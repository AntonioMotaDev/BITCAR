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
                            <div class="avatar-large me-4 flex-shrink-0">
                                <i class="bi bi-person-fill fs-1"></i>
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
                            <a href="#" class="btn btn-edit position-absolute bottom-0 end-0 d-flex align-items-center px-4 py-2">
                                <i class="bi bi-pencil-square me-2"></i>
                                Editar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Documentos -->
                <div class="card card-custom border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h5 fw-bold text-dark mb-3">Documentos del Personal</h3>
                        <div class="document-list user-documents">
                            <!-- Licencia de Conducir -->
                            <div class="document-item mb-3">
                                @foreach($documents as $document)
                                <div class="d-flex align-items-center">
                                    <div class="document-icon-small bg-primary bg-opacity-10 me-3">
                                        <i class="bi bi-file-text-fill text-prim"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="fw-medium text-dark mb-0 small">{{ $document->file_name}}:</p>
                                        <p class="text-muted small mb-0">{{ $document->expiration_date->format('d/m/Y') }}</p>
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-download"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
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
                                            data-user-documents='@json($user->documents ?? [])'>
                                            
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

                        <!-- Paginación -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <div class="text-muted small">
                                Mostrando {{ $users->count() }} de {{ $users->total() ?? 24 }} usuarios
                            </div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Siguiente</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('users.create-modal')
    @include('users.edit-modal')

     <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. FUNCIÓN PARA CARGAR INFORMACIÓN DEL USUARIO
            function loadUserInfo(userRow) {
                const userId = userRow.getAttribute('data-user-id');
                const userName = userRow.getAttribute('data-user-name');
                const userEmail = userRow.getAttribute('data-user-email');
                const userRole = userRow.getAttribute('data-user-role');
                const userStatus = userRow.getAttribute('data-user-status');
                const userNss = userRow.getAttribute('data-user-nss');
                const userDocuments = JSON.parse(userRow.getAttribute('data-user-documents') || '[]');
                
                // Actualizar información principal
                document.querySelector('.user-name').textContent = userName;
                document.querySelector('.user-id').textContent = `ID: ${userId}`;
                document.querySelector('.user-role').textContent = userRole;
                document.querySelector('.user-email').textContent = userEmail;
                
                // Actualizar estado con badge
                const statusBadge = document.querySelector('.user-status-badge');
                statusBadge.textContent = userStatus;
                statusBadge.setAttribute('data-status', userStatus.toLowerCase());
                
                // Actualizar documentos
                updateUserDocuments(userDocuments);
                
                // Resaltar fila activa
                highlightActiveRow(userId);
                
                // Actualizar URL (opcional, para compartir enlace)
                history.pushState(null, '', `?user=${userId}`);
            }
            
            // 2. ACTUALIZAR DOCUMENTOS
            function updateUserDocuments(documents) {
                const container = document.querySelector('.user-documents');
                
                if (documents && documents.length > 0) {
                    container.innerHTML = documents.map(doc => `
                        <div class="document-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="document-icon-small bg-primary bg-opacity-10 me-3">
                                    <i class="bi bi-file-text-fill text-prim"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="fw-medium text-dark mb-0 small">${doc.file_name}</p>
                                    <p class="text-muted small mb-0">
                                        ${doc.expiration_date ? 'Vence: ' + doc.expiration_date : 'Sin fecha de expiración'}
                                    </p>
                                </div>
                                <button class="btn btn-sm btn-outline-primary" title="Descargar">
                                    <i class="bi bi-download"></i>
                                </button>
                            </div>
                        </div>
                    `).join('');
                } else {
                    container.innerHTML = `
                        <div class="text-center py-3">
                            <i class="bi bi-file-earmark-x fs-1 text-muted"></i>
                            <p class="text-muted small mt-2">No hay documentos disponibles</p>
                        </div>
                    `;
                }
            }
            
            // 3. RESALTAR FILA ACTIVA
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
            
            // 5. MOSTRAR MENSAJE SIN RESULTADOS
            function showNoResultsMessage() {
                document.querySelector('.user-name').textContent = 'No se encontraron usuarios';
                document.querySelector('.user-id').textContent = '';
                document.querySelector('.user-role').textContent = 'N/A';
                document.querySelector('.user-email').textContent = 'N/A';
                updateUserDocuments([]);
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
            
            // 7. INICIALIZAR CON PRIMER USUARIO
            const firstUserRow = document.querySelector('.user-row');
            if (firstUserRow) {
                loadUserInfo(firstUserRow);
            }
            
            // 8. CARGAR USUARIO DESDE URL (OPCIONAL)
            const urlParams = new URLSearchParams(window.location.search);
            const userIdFromUrl = urlParams.get('user');
            if (userIdFromUrl) {
                const userRow = document.querySelector(`.user-row[data-user-id="${userIdFromUrl}"]`);
                if (userRow) {
                    loadUserInfo(userRow);
                }
            }
        });
    </script>

</x-app-layout>