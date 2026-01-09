# ESQUEMA DE BASE DE DATOS BITCAR - VERSIÓN ACTUAL (2026)

## 1. Introducción

Este documento describe el esquema completo de la base de datos del sistema BITCAR, reflejando exactamente la implementación actual en código (migraciones y modelos Eloquent). Incluye todos los campos, tipos de datos, relaciones y restricciones necesarias para el control vehicular y bitácoras.

---

## 2. TABLAS DE SEGURIDAD Y AUTENTICACIÓN

### 2.1 Tabla: `users`

**Propósito**: Gestión de usuarios del sistema

| Campo | Tipo | Nullable | Unique | Descripción |
|-------|------|----------|--------|-------------|
| id | bigint (PK) | No | Sí | Identificador único |
| name | varchar(255) | No | No | Nombre completo del usuario |
| email | varchar(255) | No | Sí | Correo electrónico |
| password | varchar(255) | No | No | Contraseña hasheada |
| phone | varchar(20) | Sí | No | Teléfono de contacto |
| role | enum(admin, supervisor, operador) | No | No | Rol del usuario |
| email_verified_at | timestamp | Sí | No | Fecha de verificación de email |
| created_at | timestamp | No | No | Fecha de creación |
| updated_at | timestamp | No | No | Última actualización |

**Índices**: 
- PRIMARY: id
- UNIQUE: email

**Relaciones**:
- HasMany: vehicle_assignments
- HasMany: vehicle_logs
- HasMany: trips
- HasMany: incidents (through vehicle_logs)

---

## 3. CATÁLOGO DE VEHÍCULOS

### 3.1 Tabla: `vehicles`

**Propósito**: Registro maestro de vehículos

| Campo | Tipo | Nullable | Unique | Descripción |
|-------|------|----------|--------|-------------|
| id | bigint (PK) | No | Sí | Identificador único |
| brand | varchar(100) | No | No | Marca (ej: Toyota) |
| model | varchar(100) | No | No | Modelo (ej: Hilux) |
| year | smallint | No | No | Año de fabricación |
| license_plate | varchar(20) | No | Sí | Placas del vehículo |
| vin | varchar(50) | Sí | Sí | Número de serie (VIN) |
| color | varchar(50) | No | No | Color del vehículo |
| type | enum(pickup, sedan, suv, van, camion) | No | No | Tipo de vehículo |
| mileage | decimal(10,2) | No | No | Kilometraje actual |
| fuel_capacity | decimal(10,2) | No | No | Capacidad de combustible (litros) |
| status | enum(activo, mantenimiento, inactivo) | No | No | Estado operativo |
| created_at | timestamp | No | No | Fecha de creación |
| updated_at | timestamp | No | No | Última actualización |

**Índices**:
- PRIMARY: id
- UNIQUE: license_plate
- UNIQUE: vin
- INDEX: status

**Relaciones**:
- HasMany: vehicle_assignments
- HasMany: vehicle_logs
- HasMany: trips
- HasOne: currentAssignment (último assignment activo)

---

### 3.2 Tabla: `vehicle_assignments`

**Propósito**: Asignación de vehículos a usuarios

| Campo | Tipo | Nullable | Descripción |
|-------|------|----------|-------------|
| id | bigint (PK) | No | Identificador único |
| vehicle_id | bigint (FK) | No | Referencia a vehicles.id |
| user_id | bigint (FK) | No | Referencia a users.id |
| start_date | datetime | No | Fecha de inicio de asignación |
| end_date | datetime | Sí | Fecha de fin de asignación |
| is_active | boolean | No | Indica si la asignación está activa |
| created_at | timestamp | No | Fecha de creación |
| updated_at | timestamp | No | Última actualización |

**Índices**:
- PRIMARY: id
- FOREIGN: vehicle_id → vehicles.id
- FOREIGN: user_id → users.id
- INDEX: (vehicle_id, is_active)

**Relaciones**:
- BelongsTo: vehicle
- BelongsTo: user

---

## 4. CHECKLISTS E INSPECCIONES

### 4.1 Tabla: `checklists`

**Propósito**: Plantillas de inspección/checklist para vehículos

| Campo | Tipo | Nullable | Descripción |
|-------|------|----------|-------------|
| id | bigint (PK) | No | Identificador único |
| name | varchar(255) | No | Nombre del checklist |
| description | text | Sí | Descripción detallada |
| is_active | boolean | No | Indica si está activo |
| created_at | timestamp | No | Fecha de creación |
| updated_at | timestamp | No | Última actualización |

**Índices**:
- PRIMARY: id
- INDEX: is_active

**Relaciones**:
- HasMany: checklist_items
- HasMany: vehicle_logs

---

### 4.2 Tabla: `checklist_items`

**Propósito**: Ítems individuales dentro de un checklist

| Campo | Tipo | Nullable | Descripción |
|-------|------|----------|-------------|
| id | bigint (PK) | No | Identificador único |
| checklist_id | bigint (FK) | No | Referencia a checklists.id |
| label | varchar(255) | No | Nombre/descripción del ítem |
| description | text | Sí | Descripción detallada del ítem |
| type | enum(boolean, text, number, photo, signature) | No | Tipo de respuesta esperada |
| order | int | No | Orden de aparición |
| required | boolean | No | Indica si es obligatorio |
| created_at | timestamp | No | Fecha de creación |
| updated_at | timestamp | No | Última actualización |

**Índices**:
- PRIMARY: id
- FOREIGN: checklist_id → checklists.id
- INDEX: (checklist_id, order)

**Relaciones**:
- BelongsTo: checklist
- HasMany: vehicle_log_items

---

## 5. BITÁCORAS VEHICULARES

### 5.1 Tabla: `vehicle_logs`

**Propósito**: Registro de entrada/salida y eventos de vehículos

| Campo | Tipo | Nullable | Descripción |
|-------|------|----------|-------------|
| id | bigint (PK) | No | Identificador único |
| vehicle_id | bigint (FK) | No | Referencia a vehicles.id |
| user_id | bigint (FK) | No | Referencia a users.id (operador) |
| checklist_id | bigint (FK) | Sí | Referencia a checklists.id |
| type | enum(entrada, salida) | No | Tipo de registro |
| mileage | decimal(10,2) | No | Kilometraje registrado |
| fuel_level | decimal(10,2) | Sí | Nivel de combustible (%) |
| notes | text | Sí | Notas adicionales |
| created_at | timestamp | No | Fecha/hora del registro |
| updated_at | timestamp | No | Última actualización |

**Índices**:
- PRIMARY: id
- FOREIGN: vehicle_id → vehicles.id
- FOREIGN: user_id → users.id
- FOREIGN: checklist_id → checklists.id
- INDEX: (vehicle_id, type, created_at)
- INDEX: (user_id, created_at)

**Relaciones**:
- BelongsTo: vehicle
- BelongsTo: user
- BelongsTo: checklist
- HasMany: vehicle_log_items
- HasMany: vehicle_log_photos
- HasOne: signature
- HasMany: incidents

---

### 5.2 Tabla: `vehicle_log_items`

**Propósito**: Respuestas a ítems de checklist en cada bitácora

| Campo | Tipo | Nullable | Descripción |
|-------|------|----------|-------------|
| id | bigint (PK) | No | Identificador único |
| vehicle_log_id | bigint (FK) | No | Referencia a vehicle_logs.id |
| checklist_item_id | bigint (FK) | No | Referencia a checklist_items.id |
| boolean_answer | boolean | Sí | Respuesta booleana (Sí/No) |
| text_answer | text | Sí | Respuesta de texto |
| numeric_answer | decimal(10,2) | Sí | Respuesta numérica |
| created_at | timestamp | No | Fecha de creación |
| updated_at | timestamp | No | Última actualización |

**Índices**:
- PRIMARY: id
- FOREIGN: vehicle_log_id → vehicle_logs.id
- FOREIGN: checklist_item_id → checklist_items.id

**Relaciones**:
- BelongsTo: vehicle_log
- BelongsTo: checklist_item

---

### 5.3 Tabla: `vehicle_log_photos`

**Propósito**: Fotos adjuntas en bitácoras

| Campo | Tipo | Nullable | Descripción |
|-------|------|----------|-------------|
| id | bigint (PK) | No | Identificador único |
| vehicle_log_id | bigint (FK) | No | Referencia a vehicle_logs.id |
| file_path | varchar(255) | No | Ruta del archivo (base64 o URL) |
| description | varchar(255) | Sí | Descripción de la foto |
| created_at | timestamp | No | Fecha de creación |
| updated_at | timestamp | No | Última actualización |

**Índices**:
- PRIMARY: id
- FOREIGN: vehicle_log_id → vehicle_logs.id

**Relaciones**:
- BelongsTo: vehicle_log

---

### 5.4 Tabla: `signatures`

**Propósito**: Firmas digitales de operadores en bitácoras

| Campo | Tipo | Nullable | Descripción |
|-------|------|----------|-------------|
| id | bigint (PK) | No | Identificador único |
| vehicle_log_id | bigint (FK) | No | Referencia a vehicle_logs.id |
| signature_data | longtext | No | Firma en base64 o SVG |
| signer_name | varchar(255) | No | Nombre de quien firma |
| signed_at | timestamp | No | Fecha/hora de la firma |
| created_at | timestamp | No | Fecha de creación |
| updated_at | timestamp | No | Última actualización |

**Índices**:
- PRIMARY: id
- FOREIGN: vehicle_log_id → vehicle_logs.id
- UNIQUE: vehicle_log_id (una firma por bitácora)

**Relaciones**:
- BelongsTo: vehicle_log

---

## 6. INCIDENTES Y EVENTOS

### 6.1 Tabla: `incidents`

**Propósito**: Registro de incidentes reportados en vehículos

| Campo | Tipo | Nullable | Descripción |
|-------|------|----------|-------------|
| id | bigint (PK) | No | Identificador único |
| vehicle_log_id | bigint (FK) | No | Referencia a vehicle_logs.id |
| description | text | No | Descripción del incidente |
| severity | enum(baja, media, alta, critica) | No | Nivel de severidad |
| is_resolved | boolean | No | Indica si fue resuelto |
| resolution_notes | text | Sí | Notas de resolución |
| created_at | timestamp | No | Fecha de reporte |
| updated_at | timestamp | No | Última actualización |

**Índices**:
- PRIMARY: id
- FOREIGN: vehicle_log_id → vehicle_logs.id
- INDEX: severity
- INDEX: is_resolved

**Relaciones**:
- BelongsTo: vehicle_log

---

## 7. VIAJES Y SEGUIMIENTO GPS

### 7.1 Tabla: `trips`

**Propósito**: Registro de viajes con seguimiento GPS

| Campo | Tipo | Nullable | Descripción |
|-------|------|----------|-------------|
| id | bigint (PK) | No | Identificador único |
| vehicle_id | bigint (FK) | No | Referencia a vehicles.id |
| user_id | bigint (FK) | No | Referencia a users.id (conductor) |
| start_time | datetime | No | Fecha/hora de inicio |
| end_time | datetime | Sí | Fecha/hora de fin |
| start_mileage | decimal(10,2) | No | Kilometraje al inicio |
| end_mileage | decimal(10,2) | Sí | Kilometraje al fin |
| start_fuel_level | decimal(10,2) | Sí | Nivel de combustible al inicio (%) |
| end_fuel_level | decimal(10,2) | Sí | Nivel de combustible al fin (%) |
| distance_km | decimal(10,2) | Sí | Distancia recorrida (calculada) |
| estimated_fuel_consumption | decimal(10,2) | Sí | Consumo estimado de combustible |
| notes | text | Sí | Notas adicionales del viaje |
| is_active | boolean | No | Indica si el viaje está en curso |
| created_at | timestamp | No | Fecha de creación |
| updated_at | timestamp | No | Última actualización |

**Índices**:
- PRIMARY: id
- FOREIGN: vehicle_id → vehicles.id
- FOREIGN: user_id → users.id
- INDEX: (is_active, user_id)
- INDEX: (start_time, end_time)

**Relaciones**:
- BelongsTo: vehicle
- BelongsTo: user
- HasMany: trip_locations

---

### 7.2 Tabla: `trip_locations`

**Propósito**: Puntos GPS registrados durante un viaje

| Campo | Tipo | Nullable | Descripción |
|-------|------|----------|-------------|
| id | bigint (PK) | No | Identificador único |
| trip_id | bigint (FK) | No | Referencia a trips.id |
| latitude | decimal(10,8) | No | Latitud (formato GPS) |
| longitude | decimal(10,8) | No | Longitud (formato GPS) |
| accuracy | int | Sí | Precisión del GPS en metros |
| speed | decimal(10,2) | Sí | Velocidad instantánea (km/h) |
| recorded_at | datetime | No | Fecha/hora del registro |
| created_at | timestamp | No | Fecha de creación |
| updated_at | timestamp | No | Última actualización |

**Índices**:
- PRIMARY: id
- FOREIGN: trip_id → trips.id
- INDEX: (trip_id, recorded_at)

**Relaciones**:
- BelongsTo: trip

---

## 8. ESTRUCTURA DE DATOS COMPLEMENTARIA

### 8.1 Tabla: `personal_access_tokens` (Laravel Sanctum)

**Propósito**: Tokens API para autenticación móvil

| Campo | Tipo | Nullable | Descripción |
|-------|------|----------|-------------|
| id | bigint (PK) | No | Identificador único |
| tokenable_type | varchar(255) | No | Tipo de modelo (User) |
| tokenable_id | bigint | No | ID del usuario |
| name | varchar(255) | No | Nombre del token |
| token | varchar(80) | No | Token hasheado |
| abilities | text | Sí | Capacidades JSON |
| last_used_at | timestamp | Sí | Última vez usado |
| created_at | timestamp | No | Fecha de creación |
| updated_at | timestamp | No | Última actualización |

**Relaciones**:
- MorphTo: tokenable (users)

---

### 8.2 Tabla: `cache`

**Propósito**: Cache de Laravel

| Campo | Tipo | Descripción |
|-------|------|-------------|
| key | varchar(255) | Clave única |
| value | longtext | Valor almacenado |
| expiration | int | Timestamp de expiración |

---

### 8.3 Tabla: `jobs`

**Propósito**: Colas de trabajo asincrónicas

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint (PK) | Identificador único |
| queue | varchar(255) | Nombre de la cola |
| payload | longtext | Datos del trabajo |
| attempts | tinyint | Intentos realizados |
| reserved_at | int | Timestamp de reserva |
| available_at | int | Timestamp disponible |
| created_at | int | Timestamp creación |

---

## 9. RELACIONES CLAVE DEL SISTEMA

### Diagrama de Relaciones Principal

```
users
├── HasMany: vehicle_assignments
├── HasMany: vehicle_logs
└── HasMany: trips

vehicles
├── HasMany: vehicle_assignments
├── HasMany: vehicle_logs
└── HasMany: trips

vehicle_assignments
├── BelongsTo: user
└── BelongsTo: vehicle

checklists
├── HasMany: checklist_items
└── HasMany: vehicle_logs

checklist_items
├── BelongsTo: checklist
└── HasMany: vehicle_log_items

vehicle_logs
├── BelongsTo: vehicle
├── BelongsTo: user
├── BelongsTo: checklist
├── HasMany: vehicle_log_items
├── HasMany: vehicle_log_photos
├── HasOne: signature
└── HasMany: incidents

vehicle_log_items
├── BelongsTo: vehicle_log
└── BelongsTo: checklist_item

vehicle_log_photos
└── BelongsTo: vehicle_log

signatures
└── BelongsTo: vehicle_log

incidents
└── BelongsTo: vehicle_log

trips
├── BelongsTo: vehicle
├── BelongsTo: user
└── HasMany: trip_locations

trip_locations
└── BelongsTo: trip
```

---

## 10. RESTRICCIONES DE INTEGRIDAD

1. **ON DELETE RESTRICT**: Todas las FK protegen contra eliminaciones accidentales
2. **ON UPDATE CASCADE**: Actualizaciones de PK se propagan a FK
3. **Unicidad**: 
   - email en users
   - license_plate en vehicles
   - vin en vehicles (opcional)
4. **Inmutabilidad**: vehicle_logs no deben ser editables (solo lecturas)
5. **Cascada lógica**: Soft deletes donde sea necesario

---

## 11. ÍNDICES DE PERFORMANCE

| Tabla | Índices |
|-------|---------|
| users | email (UNIQUE) |
| vehicles | license_plate (UNIQUE), vin (UNIQUE), status |
| vehicle_logs | (vehicle_id, type, created_at), (user_id, created_at) |
| trips | (is_active, user_id), (start_time, end_time) |
| trip_locations | (trip_id, recorded_at) |
| checklist_items | (checklist_id, order) |
| vehicle_log_items | (vehicle_log_id, checklist_item_id) |
| incidents | severity, is_resolved |

---

## 12. TIPOS DE DATOS RECOMENDADOS

- **Coordenadas GPS**: `decimal(10,8)` (precisión de 1.1 mm)
- **Distancias/Kilometraje**: `decimal(10,2)` (hasta 99,999,999.99)
- **Combustible**: `decimal(10,2)` (litros y porcentaje)
- **Velocidad**: `decimal(10,2)` (km/h)
- **Timestamps**: `datetime` o `timestamp`
- **IDs**: `bigint` (preparado para escala)

---

## 13. CONSIDERACIONES DE ESCALABILIDAD

- **Particionar vehicle_logs** por año (muy voluminosa)
- **Archivar trip_locations** después de 6 meses
- **Caché de estadísticas** para reportes
- **Índices adicionales** por región geográfica si es necesario

---

## 14. CAMPOS FALTANTES / PENDIENTES DE IMPLEMENTAR

### Posibles mejoras futuras:

- **Tabla: `vehicle_services`** - Historial de mantenimiento
- **Tabla: `fuel_loads`** - Registro de carga de combustible
- **Tabla: `activity_logs`** - Auditoría de acciones de usuarios
- **Tabla: `documents`** - Documentación de vehículos (licencias, pólizas)
- **Tabla: `geofences`** - Zonas permitidas de operación
- **Tabla: `maintenance_alerts`** - Alertas de mantenimiento programado

---

## 15. CHARSET Y COLACIÓN

- **Charset**: `utf8mb4` (soporte completo UTF-8)
- **Collation**: `utf8mb4_unicode_ci` (case-insensitive, Unicode)

---

## HISTORIAL DE VERSIONES

| Versión | Fecha | Cambios |
|---------|-------|---------|
| 1.0 | 09/01/2026 | Esquema inicial actualizado con implementación actual |
| - | - | - |

