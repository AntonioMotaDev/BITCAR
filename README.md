# BITCAR - Sistema de Bitácora Vehicular

Sistema completo de gestión de bitácoras vehiculares desarrollado en **Laravel 12** con arquitectura limpia, panel administrativo web y API REST para aplicación móvil.

##  Características

### Panel Web (Administración)
- Dashboard con estadísticas en tiempo real
- CRUD de vehículos, usuarios y checklists
- Visualización de bitácoras y viajes
- Gestión de incidencias
- Control de roles (admin, supervisor, operador)

### API REST (Aplicación Móvil)
- Autenticación con Laravel Sanctum
- Login/logout de operadores
- Obtención de vehículo asignado
- Registro de checklists de entrada/salida
- Tracking GPS con múltiples puntos por viaje
- Cálculo automático de distancias (Haversine)
- Registro de incidencias
- Subida de fotos y firmas

##  Stack Tecnológico

- **Backend**: Laravel 12
- **Frontend Web**: Blade + Bootstrap 5
- **Base de datos**: MySQL 8+
- **Autenticación Web**: Laravel Breeze
- **Autenticación API**: Laravel Sanctum
- **Almacenamiento**: Local/S3-compatible

- PHP 8.2+
- Composer
- MySQL 8+
- Node.js & NPM

### Pasos

1. **Configurar base de datos**
Crear base de datos `bitcar` en MySQL y editar `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bitcar
DB_USERNAME=root
DB_PASSWORD=
```

2. **Ejecutar migraciones y seeders**
```bash
php artisan migrate --seed
```

3. **Crear enlace simbólico para storage**
```bash
php artisan storage:link
```

4. **Iniciar servidor**
```bash
php artisan serve
```

Acceder a: `http://localhost:8000`

##  Usuarios de Prueba

| Email | Password | Rol |
|-------|----------|-----|
| admin@bitcar.com | password | admin |
| supervisor@bitcar.com | password | supervisor |
| juan.perez@bitcar.com | password | operador |

##  Estructura del Proyecto

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/V1/          # Controladores API versionados
│   │   └── Web/             # Controladores panel web
│   ├── Requests/            # FormRequests validaciones
│   └── Resources/           # API Resources
├── Models/                  # Modelos Eloquent
├── Policies/                # Policies de autorización
└── Services/                # Servicios de dominio
    ├── VehicleAssignmentService.php
    ├── VehicleLogService.php
    ├── ChecklistService.php
    └── TripService.php
```

##  API Endpoints

### Autenticación
```bash
POST /api/v1/login
POST /api/v1/logout
GET /api/v1/me
```

### Checklists
```bash
GET /api/v1/checklists/active
```

### Bitácoras
```bash
POST /api/v1/vehicle-logs/exit     # Registrar salida
POST /api/v1/vehicle-logs/entry    # Registrar entrada
POST /api/v1/vehicle-logs/{log}/incidents
```

### Viajes
```bash
GET /api/v1/trips                  # Historial
GET /api/v1/trips/active           # Viaje activo
POST /api/v1/trips/{trip}/locations # GPS tracking
```


##  Servicios de Dominio

### VehicleAssignmentService
Gestión de asignaciones de vehículos a operadores

### VehicleLogService
Creación de logs con items, fotos y firma

### ChecklistService
Gestión de plantillas de checklist

### TripService
- Iniciar/finalizar viajes
- Cálculo de distancias (Haversine)
- Registro de tracking GPS

##  Roles y Permisos

**Admin**: Acceso completo
**Supervisor**: Ver vehículos, bitácoras y reportes
**Operador**: Registrar checklists y tracking GPS

##  Flujo de Trabajo

1. Admin asigna vehículo a Operador
2. Operador inicia sesión en app móvil
3. Operador registra checklist de **salida** → inicia viaje
4. App envía ubicaciones GPS periódicamente
5. Operador registra checklist de **entrada** → finaliza viaje
6. Sistema calcula distancia y consumo
7. Supervisor/Admin visualiza reportes


## Próximos Pasos

- [ ] Vistas CRUD completas
- [ ] Tests unitarios
- [ ] Notificaciones
- [ ] Reportes PDF/Excel
- [ ] Integración con mapas

---

**BITCAR** © 2025 - Desarrollado con Laravel 12 y Clean Architecture
