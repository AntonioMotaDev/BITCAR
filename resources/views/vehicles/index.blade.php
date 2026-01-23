<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h2 fw-bold text-dark mb-0">
                GESTIONAR UNIDADES VEHICULARES
            </h2>
            <div>
                <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#createVehicleModal">
                    <i class="bi bi-plus-lg"></i> Crear Nueva Unidad
                </button>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4 px-4">
        <!-- Contenedor principal con espacio entre columnas -->
        <div class="row"> 
            <!-- Información de la unidad -->
            <div class="col-lg-4"> 
                <!-- Tarjeta Principal de la Unidad -->
                <div class="card card-custom border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <!-- Encabezado con avatar -->
                        <div class="d-flex align-items-start mb-4">
                            <div class="avatar-large me-4 flex-shrink-0" id="avatar-container">
                                <!-- Imagen del vehículo -->
                                    <img src="/storage/{{ $vehicles->first()->image }}" 
                                        alt="Avatar" 
                                        class="vehicle-image rounded-circle w-100 h-100 object-fit-cover">
                                <!-- Icono por defecto -->
                                <div class="avatar-small d-flex align-items-center justify-content-center w-100 h-100">
                                    <i class="bi bi-person-fill fs-1 text-muted" style="display: none;"></i>
                                </div>
                            </div>
                            
                            <div class="flex-grow-1 info-header">
                                <h1 class="h4 fw-bold mb-1 vehicle-brand">
                                    {{ $vehicles->first()->brand ?? 'Seleccione una unidad' }}
                                </h1>
                                <p class="h6 mb-0 vehicle-model">
                                    {{ $vehicles->first()->model ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <!-- Información de la Unidad -->
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <p class="info-label">Año</p>
                                        <p class="info-value vehicle-year">{{ $vehicles->first()->year ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <p class="info-label">Color</p>
                                        <p class="info-value vehicle-color">{{ $vehicles->first()->color ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <p class="info-label">Tipo</p>
                                        <p class="info-value vehicle-type">{{ $vehicles->first()->type ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <p class="info-label">Placa</p>
                                        <p class="info-value vehicle-license_plate">{{ $vehicles->first()->license_plate ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <p class="info-label">Kilometraje</p>
                                        <p class="info-value vehicle-mileage">{{ $vehicles->first()->mileage ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <p class="info-label">Estado</p>
                                        <p class="info-value vehicle-status">{{ $vehicles->first()->status ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <p class="info-label">Cap Max.Combustible</p>
                                        <p class="info-value vehicle-fuel_capacity">{{ $vehicles->first()->fuel_capacity ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <p class="info-label">VIN</p>
                                        <p class="info-value vehicle-vin">{{ $vehicles->first()->vin ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botón Editar -->
                        <div class="position-relative" style="min-height: 60px;" >
                            <button class="btn btn-edit position-absolute bottom-0 end-0 d-flex align-items-center px-4 py-2 btn-edit-left"
                                data-bs-toggle="modal" 
                                data-bs-target="#editVehicleModal"
                                title="Editar unidad">
                                <i class="bi bi-pencil-square me-2"></i>
                                Editar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Documentos -->
                <div class="card card-custom border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h5 fw-bold text-dark mb-3">Documentos de la Unidad</h3>
                        <div class="document-list vehicle-documents">
                            <!-- Contenedor para documentos dinámicos -->
                            <div id="vehicleDocumentsContainer">
                                <!-- Mensaje inicial -->
                                <div class="text-center py-4">
                                    <i class="bi bi-file-earmark-text fs-1 text-muted"></i>
                                    <p class="text-muted small mt-2 mb-0">Seleccione un vehículo para ver sus documentos</p>
                                </div>
                            </div>
                        </div>
                        <div class="position-relative" style="min-height: 60px;">
                            <button href="#" class="btn btn-edit position-absolute bottom-0 end-0 d-flex align-items-center px-4 py-2 btn-upload-left"
                                data-bs-toggle="modal" 
                                data-bs-target="#uploadDocumentVhModal"
                                title="Subir Documento">
                                <i class="bi bi-upload me-2"></i> Subir  
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Unidades Registradas -->
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
                                    <h2 class="h3 fw-bold text-secondary mb-0">Unidades Registradas</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Lista de Usuarios -->
                        <div class="table-responsive card card-custom">
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr class="table-light">
                                            <th scope="col" class="ps-3">Marca</th>
                                            <th scope="col">Modelo</th>
                                            <th scope="col">Tipo</th>
                                            <th scope="col">Año</th>
                                            <th scope="col">Placa</th>
                                            <th scope="col" class="text-end pe-3">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($vehicles as $vehicle)
                                        <tr class="vehicle-row {{ $loop->first ? 'active' : '' }}"
                                            data-vehicle-id="{{ $vehicle->id }}"
                                            data-vehicle-brand="{{ $vehicle->brand }}"
                                            data-vehicle-model="{{ $vehicle->model }}"
                                            data-vehicle-color="{{ $vehicle->color }}"
                                            data-vehicle-type="{{ $vehicle->type }}"
                                            data-vehicle-year="{{ $vehicle->year }}"
                                            data-vehicle-mileage="{{ $vehicle->mileage }}"
                                            data-vehicle-fuel-capacity="{{ $vehicle->fuel_capacity }}"
                                            data-vehicle-license-plate="{{ $vehicle->license_plate }}"
                                            data-vehicle-vin="{{ $vehicle->vin }}"
                                            data-vehicle-status="{{ $vehicle->status }}"
                                            data-vehicle-image="{{ $vehicle->image }}"
                                            data-vehicle-documents='@json($vehicle->vehicleDocuments ?? [])'>
                                            
                                            <td class="ps-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-small bg-primary bg-opacity-10 me-3">
                                                        <i class="bi bi-car-front text-prim"></i>
                                                    </div>
                                                    <div>
                                                        <h3 class="h6 fw-bold mb-0 info-header">{{ $vehicle->brand }}</h3>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $vehicle->model }}</td>
                                            <td>{{ $vehicle->type }}</td>
                                            <td>{{ $vehicle->year }}</td>
                                            <td>{{ $vehicle->license_plate }}                                  </td>
                                            <td class="text-end pe-3">
                                                <div class="d-flex gap-2 justify-content-end">
                                                    <button class="btn btn-eye btn-view-vehicle" 
                                                            data-vehicle-id="{{ $vehicle->id }}"
                                                            title="Ver detalles">
                                                        <i class="bi bi-eye"></i> Ver
                                                    </button>
                                                    <button class="btn btn-edit"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editVehicleModal"
                                                            data-vehicle-id="{{ $vehicle->id }}"
                                                            data-vehicle-brand="{{ $vehicle->brand }}"
                                                            data-vehicle-model="{{ $vehicle->model }}"
                                                            data-vehicle-year="{{ $vehicle->year }}"
                                                            data-vehicle-color="{{ $vehicle->color }}"
                                                            data-vehicle-fuel-capacity="{{ $vehicle->fuel_capacity }}"
                                                            data-vehicle-license-plate="{{ $vehicle->license_plate }}"
                                                            data-vehicle-vin="{{ $vehicle->vin }}"
                                                            data-vehicle-mileage="{{ $vehicle->mileage }}"
                                                            data-vehicle-type="{{ $vehicle->type }}"
                                                            data-vehicle-status="{{ $vehicle->status }}"
                                                            data-vehicle-image="/storage/{{ $vehicle->image }}"
                                                            title="Editar unidad">
                                                        <i class="bi bi-pencil-square"></i> Editar
                                                    </button>
                                                    <button class="btn btn-delete" 
                                                            data-vehicle-id="{{ $vehicle->id }}"
                                                            title="Eliminar unidad">
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
                             {{ $vehicles->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('vehicles.create-modal')
    @include('vehicles.edit-modal')
    @include('documents.upload-file-vh-modal')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
         
            let currentVehicleDocuments = [];
            let currentVehicleId = null;
            
            // Cargar información de la unidad
            function loadVehicleInfo(vehicleRow) {
                if (!vehicleRow) {
                    console.error('La fila de la unidad no existe');
                    return;
                }
                
                currentVehicleId = vehicleRow.getAttribute('data-vehicle-id');
                const vehicleBrand = vehicleRow.getAttribute('data-vehicle-brand');
                const vehicleModel = vehicleRow.getAttribute('data-vehicle-model');
                const vehicleColor = vehicleRow.getAttribute('data-vehicle-color');
                const vehicleType = vehicleRow.getAttribute('data-vehicle-type');
                const vehicleYear = vehicleRow.getAttribute('data-vehicle-year');
                const vehicleMileage = vehicleRow.getAttribute('data-vehicle-mileage');
                const vehicleFuelCapacity = vehicleRow.getAttribute('data-vehicle-fuel-capacity');
                const vehicleLicensePlate = vehicleRow.getAttribute('data-vehicle-license-plate');
                const vehicleVin = vehicleRow.getAttribute('data-vehicle-vin');
                const vehicleStatus = vehicleRow.getAttribute('data-vehicle-status');
                const vehicleImage = vehicleRow.getAttribute('data-vehicle-image');

                // Actualizar avatar
                updateUserAvatar(vehicleImage, vehicleBrand);
                  
                // Actualizar información principal
                updateElementText('.vehicle-brand', vehicleBrand || 'N/A');
                updateElementText('.vehicle-model', vehicleModel || 'N/A');
                updateElementText('.vehicle-color', vehicleColor || 'N/A');
                updateElementText('.vehicle-type', vehicleType || 'N/A');
                updateElementText('.vehicle-year', vehicleYear || 'N/A');
                updateElementText('.vehicle-mileage', vehicleMileage || 'N/A');
                updateElementText('.vehicle-fuel_capacity', vehicleFuelCapacity || 'N/A');   
                updateElementText('.vehicle-license_plate', vehicleLicensePlate || 'N/A');
                updateElementText('.vehicle-vin', vehicleVin || 'N/A');
                updateElementText('.vehicle-status', vehicleStatus || 'N/A');
                document.querySelector('.vehicle-image').src = `/storage/${vehicleImage}`;

                // Actualizar documentos
                try {
                    currentVehicleDocuments = JSON.parse(vehicleRow.getAttribute('data-vehicle-documents') || '[]');
                    renderVehicleDocuments();
                } catch (e) {
                    console.error('Error al parsear documentos:', e);
                    currentVehicleDocuments = [];
                    renderVehicleDocuments();
                }
                // Actuaizar datos en el botón de editar izquierdo
                updateLeftEditButton(vehicleRow);
                updateLeftUploadButton(vehicleRow);

                // Resaltar fila activa
                highlightActiveRow(currentVehicleId);
                
                // Actualizar URL
                history.pushState(null, '', `?vehicle=${currentVehicleId}`);
            }

            function updateUserAvatar(vehicleImage, vehicleBrand) {
                const imgElement = document.querySelector('.vehicle-image');
                const iconElement = document.querySelector('.avatar-small i');
                
                if (vehicleImage) {
                    // Si tiene imagen mostrarla
                    imgElement.src = `/storage/${vehicleImage}`;
                    imgElement.alt = `Avatar de ${vehicleBrand}`;
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

            function updateLeftEditButton(vehicleRow) {
                const leftEditButton = document.querySelector('.btn-edit-left');
                
                if (!leftEditButton) {
                    console.warn('Botón de editar izquierdo no encontrado');
                    return;
                }
                
                // Copiar todos los atributos al botón izquierdo
                const attributes = [
                    'data-vehicle-id',
                    'data-vehicle-brand',
                    'data-vehicle-model',
                    'data-vehicle-year',
                    'data-vehicle-color',
                    'data-vehicle-fuel-capacity',
                    'data-vehicle-license-plate',
                    'data-vehicle-vin',
                    'data-vehicle-mileage',
                    'data-vehicle-type',
                    'data-vehicle-status',
                    'data-vehicle-image'
                ];
                
                attributes.forEach(attr => {
                    const value = vehicleRow.getAttribute(attr);
                    if (value !== null && value !== undefined) {
                        leftEditButton.setAttribute(attr, value);
                    }
                });
            }

            function updateLeftUploadButton(vehicleRow) {
                const leftUploadButton = document.querySelector('.btn-upload-left');
                
                if (!leftUploadButton) {
                    console.warn('Botón de subir documento izquierdo no encontrado');
                    return;
                }
                
                // Copiar el atributo id al botón subir izquierdo
                const attributes = [
                    'data-vehicle-id'
                ];
                
                attributes.forEach(attr => {
                    const value = vehicleRow.getAttribute(attr);
                    if (value !== null && value !== undefined) {
                        leftUploadButton.setAttribute(attr, value);
                    }
                });
            }
            
            // función actualizar texto
            function updateElementText(selector, text) {
                const element = document.querySelector(selector);
                if (element) {
                    element.textContent = text;
                    return true;
                } else {
                    console.warn(`Elemento no encontrado: ${selector}`);
                    return false;
                }
            }
            
            // Fromatear fecha
            function formatDate(dateString) {
                if (!dateString) return 'Sin fecha';
                try {
                    const date = new Date(dateString);
                    if (isNaN(date.getTime())) return 'Sin fecha';
                    return date.toLocaleDateString('es-ES', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                } catch (e) {
                    console.warn('Error formateando fecha:', e);
                    return 'Sin fecha';
                }
            }
            
            // Función para verificar si el documento está vencido
            function checkIfExpired(expirationDate) {
                if (!expirationDate) return false;
                
                try {
                    const expDate = new Date(expirationDate);
                    expDate.setHours(0, 0, 0, 0);
                    
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    
                    return expDate < today;
                } catch (e) {
                    console.warn('Error verificando fecha de vencimiento:', e);
                    return false;
                }
            }
            
            // Determinar estado del documento
            function getDocumentStatus(document) {
                if (!document.expiration_date) {
                    return 'Pendiente';
                }
                
                try {
                    const expirationDate = new Date(document.expiration_date);
                    expirationDate.setHours(0, 0, 0, 0);
                    
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    
                    if (expirationDate < today) {
                        return 'Vencido';
                    }
                    
                    // Si vence en los próximos 7 días
                    const oneWeekFromNow = new Date();
                    oneWeekFromNow.setDate(today.getDate() + 7);
                    oneWeekFromNow.setHours(0, 0, 0, 0);
                    
                    if (expirationDate <= oneWeekFromNow) {
                        return 'Por vencer';
                    }
                    
                    return 'Vigente';
                } catch (e) {
                    console.warn('Error determinando estado del documento:', e);
                    return 'Indeterminado';
                }
            }
            
            // Renderizar documentos
            function renderVehicleDocuments() {
                const container = document.querySelector('.vehicle-documents');
                
                if (!container) {
                    console.error('Contenedor .vehicle-documents no encontrado');
                    return;
                }
                
                if (currentVehicleDocuments && currentVehicleDocuments.length > 0) {
                    // Contar documentos vencidos
                    const expiredCount = currentVehicleDocuments.filter(doc => checkIfExpired(doc.expiration_date)).length;
                    
                    let documentsHTML = `
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="small fw-bold text-prim">Documento</th>
                                        <th class="small fw-bold text-prim">Estado</th>
                                        <th class="small fw-bold text-prim">Vencimiento</th>
                                        <th class="small fw-bold text-prim text-end"></th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;
                    
                    currentVehicleDocuments.forEach((doc) => {
                        const fileName = doc.file_name || doc.name || 'Documento';
                        const expirationDate = formatDate(doc.expiration_date);
                        const isExpired = checkIfExpired(doc.expiration_date);
                        const status = getDocumentStatus(doc);
                        
                        documentsHTML += `
                            <tr class="border-bottom">
                                <td style="vertical-align: middle;">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-file-earmark me-2 text-prim"></i>
                                        <div>
                                            <div class="fw-medium small">${fileName}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="vertical-align: middle;">
                                    <span class="small fw-medium">${doc.status}</span>
                                </td>
                                <td style="vertical-align: middle;">
                                    <div class="small ${isExpired ? 'text-danger fw-medium' : 'text-muted'}">
                                        ${expirationDate}
                                        ${isExpired ? '<br><small class="text-danger">¡Vencido!</small>' : ''}
                                    </div>
                                </td>
                                <td style="vertical-align: middle;" class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        ${doc.path ? `
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-primary btn-view-document"
                                                    data-document-id="${doc.id}"
                                                    data-file-name="${fileName}"
                                                    data-file-path="${doc.path}"
                                                    title="Ver documento">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-success btn-download-document"
                                                    data-document-id="${doc.id}"
                                                    data-file-name="${fileName}"
                                                    data-file-path="${doc.path}"
                                                    title="Descargar">
                                                <i class="bi bi-download"></i>
                                            </button>
                                        ` : `
                                            <span class="text-muted small">No disponible</span>
                                        `}
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
                        <div class="mt-3 p-3 bg-light rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-files me-1"></i>
                                    Total: ${currentVehicleDocuments.length} documentos
                                </small>
                                <small class="text-muted">
                                    Vencidos: ${currentVehicleDocuments.filter(d => checkIfExpired(d.expiration_date)).length}
                                </small>
                            </div>
                        </div>
                    `;
                    
                    container.innerHTML = documentsHTML;
                    
                    // Agregar event listeners a los botones de documentos
                    addDocumentEventListeners(currentVehicleId);
                    
                } else {
                    container.innerHTML = `
                        <div class="text-center py-4">
                            <i class="bi bi-file-earmark-x fs-1 text-muted"></i>
                            <p class="text-muted small mt-2 mb-1">No hay documentos disponibles</p>
                            <p class="text-muted extra-small">Este vehículo no tiene documentos registrados</p>
                        </div>
                    `;
                }
            }
            

            function addDocumentEventListeners(currentVehicleId) {
                document.querySelectorAll('.btn-view-document').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const documentId = this.getAttribute('data-document-id');
                        const vehicleId = currentVehicleId; // Obtener el ID del vehiculo actual
                        const filePath = this.getAttribute('data-file-path');
                        if (documentId && vehicleId) {
                            // Construir la URL
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
            
            // Descargar documento
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
            
            // Resaltar fila activa
            function highlightActiveRow(vehicleId) {
                document.querySelectorAll('.vehicle-row').forEach(row => {
                    row.classList.remove('active');
                });
                
                const activeRow = document.querySelector(`.vehicle-row[data-vehicle-id="${vehicleId}"]`);
                if (activeRow) {
                    activeRow.classList.add('active');
                }
            }
            
            // Filtrar Unidades
            function filterVehicles() {
                const searchTerm = document.getElementById('vehicleSearch')?.value.toLowerCase() || '';
                const typeFilter = document.getElementById('typeFilter')?.value || '';
                const rows = document.querySelectorAll('.vehicle-row');
                let visibleCount = 0;
                
                rows.forEach(row => {
                    const vehicleBrand = row.getAttribute('data-vehicle-brand')?.toLowerCase() || '';
                    const vehicleModel = row.getAttribute('data-vehicle-model')?.toLowerCase() || '';
                    const vehicleType = row.getAttribute('data-vehicle-type')?.toLowerCase() || '';
                    const vehicleLicensePlate = row.getAttribute('data-vehicle-license-plate')?.toLowerCase() || '';
                    
                    const matchesSearch = searchTerm === '' || 
                        vehicleBrand.includes(searchTerm) || 
                        vehicleModel.includes(searchTerm) ||
                        vehicleLicensePlate.includes(searchTerm);
                    
                    const matchesType = typeFilter === '' || vehicleType === typeFilter;
                    
                    if (matchesSearch && matchesType) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Actualizar contadores si existen
                const vehicleCountElement = document.getElementById('vehicleCount');
                const showingCountElement = document.getElementById('showingCount');
                
                if (vehicleCountElement) {
                    vehicleCountElement.textContent = visibleCount + ' unidades';
                }
                if (showingCountElement) {
                    showingCountElement.textContent = visibleCount;
                }
            }
            
            function setupEventListeners() {
                // Eventos para botones "Ver" en cada fila
                document.querySelectorAll('.btn-view-vehicle').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const vehicleRow = this.closest('.vehicle-row');
                        if (vehicleRow) {
                            loadVehicleInfo(vehicleRow);
                        } else {
                            console.error('No se encontró la fila de la unidad');
                        }
                    });
                });

                
                document.querySelectorAll('.btn-delete').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const vehicleId = this.getAttribute('data-vehicle-id');
                        if (confirm(`¿Está seguro de eliminar esta unidad?`)) {
                            console.log('Eliminar unidad:', vehicleId);
                            // Aquí iría la lógica para eliminar
                        }
                    });
                });
                
                // Eventos para búsqueda y filtros
                const vehicleSearch = document.getElementById('vehicleSearch');
                const typeFilter = document.getElementById('typeFilter');
                
                if (vehicleSearch) {
                    vehicleSearch.addEventListener('input', filterVehicles);
                }
                
                if (typeFilter) {
                    typeFilter.addEventListener('change', filterVehicles);
                }
                
            }
            
            function initialize() {
                
                // Verificar contenedor de documentos
                const container = document.querySelector('.vehicle-documents');
                
                // Configurar event listeners
                setupEventListeners();
                
                // Cargar primera unidad si existe
                const firstVehicleRow = document.querySelector('.vehicle-row');
                if (firstVehicleRow) {
                    loadVehicleInfo(firstVehicleRow);
                } else {
                    console.warn('No se encontraron unidades');
                }
                
                // Cargar unidad desde URL si existe
                const urlParams = new URLSearchParams(window.location.search);
                const vehicleIdFromUrl = urlParams.get('vehicle');
                if (vehicleIdFromUrl) {
                    const vehicleRow = document.querySelector(`.vehicle-row[data-vehicle-id="${vehicleIdFromUrl}"]`);
                    if (vehicleRow) {
                        loadVehicleInfo(vehicleRow);
                    }
                }
            }
            
            // Iniciar todo
            initialize();
        });
    </script>
</x-app-layout>