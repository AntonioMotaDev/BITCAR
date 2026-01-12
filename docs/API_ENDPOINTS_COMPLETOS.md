# 📚 BITCAR API - Documentación Completa de Endpoints

**Versión**: 1.0  
**Última actualización**: 12 de enero de 2026  
**Ambiente**: Production API v1  
**Base URL**: `https://tu-backend.com/api/v1`

---

## 📑 TABLA DE CONTENIDOS

1. [Información General](#información-general)
2. [Autenticación](#autenticación)
3. [Checklists](#checklists)
4. [Vehicle Logs (Bitácoras)](#vehicle-logs-bitácoras)
5. [Trips (Viajes)](#trips-viajes)
6. [Incidentes](#incidentes)
7. [Combustible](#combustible)
8. [Códigos de Error](#códigos-de-error)
9. [Ejemplos de Uso](#ejemplos-de-uso)

---

## 🔧 INFORMACIÓN GENERAL

### Headers Requeridos

Todos los endpoints (excepto `/login`) requieren autenticación con Sanctum:

```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {token}
```

### Formatos de Respuesta

**Éxito (2xx)**:
```json
{
  "message": "Descripción del resultado",
  "data": { /* datos específicos */ },
  "meta": { /* opcional: paginación, etc */ }
}
```

**Error (4xx/5xx)**:
```json
{
  "message": "Descripción del error",
  "errors": { /* opcional: errores de validación */ }
}
```

### Códigos de Respuesta

| Código | Significado |
|--------|-------------|
| 200 | OK - Solicitud exitosa |
| 201 | Created - Recurso creado |
| 400 | Bad Request - Datos inválidos |
| 401 | Unauthorized - Token inválido/expirado |
| 403 | Forbidden - Acceso denegado |
| 404 | Not Found - Recurso no existe |
| 422 | Unprocessable Entity - Validación fallida |
| 500 | Internal Server Error - Error del servidor |

---

## 🔐 AUTENTICACIÓN

### POST /login

**Propósito**: Autenticar usuario y obtener token de acceso

**Público**: Sí (sin requiere autorización)

**Request**:
```json
{
  "email": "operador@example.com",
  "password": "contraseña123"
}
```

**Response (200 OK)**:
```json
{
  "message": "Autenticación exitosa",
  "data": {
    "user": {
      "id": 1,
      "name": "Juan García López",
      "email": "operador@example.com",
      "phone": "3001234567",
      "role": "operador",
      "created_at": "2025-01-10T14:30:00Z"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
  }
}
```

**Response (401 Unauthorized)**:
```json
{
  "message": "Las credenciales no coinciden con nuestros registros"
}
```

**Notas**:
- Token válido por 12 horas
- Guardar en Secure Storage (no localStorage)
- El token debe incluirse en header `Authorization: Bearer {token}`

---

### POST /logout

**Propósito**: Cerrar sesión y revocar token

**Requiere**: Autorización (Bearer token)

**Request**:
```json
{}
```

**Response (200 OK)**:
```json
{
  "message": "Sesión cerrada exitosamente"
}
```

**Notas**:
- Invalida el token actual
- Se recomienda limpiar almacenamiento local después

---

### GET /me

**Propósito**: Obtener datos del usuario autenticado actual

**Requiere**: Autorización (Bearer token)

**Response (200 OK)**:
```json
{
  "data": {
    "id": 1,
    "name": "Juan García López",
    "email": "operador@example.com",
    "phone": "3001234567",
    "role": "operador",
    "created_at": "2025-01-10T14:30:00Z"
  }
}
```

---

## 📋 CHECKLISTS

### GET /checklists/active

**Propósito**: Obtener lista de checklists activos disponibles

**Requiere**: Autorización

**Query Parameters**: Ninguno

**Response (200 OK)**:
```json
{
  "data": [
    {
      "id": 1,
      "name": "Inspección Entrada Vehículo",
      "description": "Checklist diario al inicio del turno",
      "is_active": true,
      "created_at": "2025-01-09T10:00:00Z",
      "items": [
        {
          "id": 1,
          "checklist_id": 1,
          "label": "Luces delanteras funcionan",
          "description": "Verificar que ambas luces delanteras encienden",
          "type": "boolean",
          "order": 1,
          "required": true
        },
        {
          "id": 2,
          "checklist_id": 1,
          "label": "Nivel de aceite",
          "description": "Verificar con varilla",
          "type": "text",
          "order": 2,
          "required": false
        },
        {
          "id": 3,
          "checklist_id": 1,
          "label": "Fotografía de odómetro",
          "description": "Capturar foto del odómetro",
          "type": "photo",
          "order": 3,
          "required": true
        },
        {
          "id": 4,
          "checklist_id": 1,
          "label": "Firma operador",
          "description": "Firma digital de conformidad",
          "type": "signature",
          "order": 4,
          "required": true
        }
      ]
    }
  ]
}
```

**Response (404 Not Found)**:
```json
{
  "message": "No hay checklist activo"
}
```

**Tipos de items soportados**:
- `boolean`: Sí/No
- `text`: Texto libre
- `number`: Valor numérico
- `photo`: Captura de foto
- `signature`: Firma digital

---

### POST /checklists/{id}/submit

**Propósito**: Enviar respuestas completadas de un checklist

**Requiere**: Autorización

**Path Parameters**:
- `id` (integer): ID del checklist

**Request**:
```json
{
  "vehicle_id": 1,
  "type": "entrada",
  "mileage": 45000.50,
  "fuel_level": 85.0,
  "notes": "Vehículo en buen estado general",
  "items": [
    {
      "checklist_item_id": 1,
      "boolean_answer": true
    },
    {
      "checklist_item_id": 2,
      "text_answer": "Nivel dentro de parámetros"
    },
    {
      "checklist_item_id": 3,
      "numeric_answer": 45000
    }
  ]
}
```

**Response (201 Created)**:
```json
{
  "message": "Checklist enviado exitosamente",
  "data": {
    "log_id": 15,
    "type": "entrada",
    "vehicle_id": 1,
    "created_at": "2026-01-12T06:30:00Z"
  }
}
```

**Response (422 Unprocessable Entity)**:
```json
{
  "message": "Faltan respuestas en campos requeridos",
  "missing_fields": ["Firma operador"]
}
```

**Validaciones**:
- Todos los ítems marcados como `required: true` son obligatorios
- `type` debe ser `"entrada"` o `"salida"`
- `mileage` debe ser número positivo
- `fuel_level` debe estar entre 0 y 100

**Notas**:
- Crea automáticamente un `VehicleLog`
- Guarda todas las respuestas en `vehicle_log_items`
- Prepara para iniciar/finalizar viaje

---

## 🚗 VEHICLE LOGS (BITÁCORAS)

### POST /vehicle-logs/entry

**Propósito**: Registrar entrada de vehículo al inicio del turno (histórico - deprecated)

**Requiere**: Autorización

**Request**:
```json
{
  "vehicle_id": 1,
  "checklist_id": 1,
  "mileage": 45050.75,
  "fuel_level": 70,
  "notes": "Checklist de entrada completado"
}
```

**Response (201 Created)**:
```json
{
  "message": "Checklist de entrada registrado",
  "data": {
    "log": {
      "id": 102,
      "vehicle_id": 1,
      "user_id": 1,
      "type": "entrada",
      "mileage": 45050.75,
      "fuel_level": 70,
      "created_at": "2026-01-12T18:00:00Z"
    },
    "trip": {
      "id": 50,
      "total_distance_km": 125.25,
      "estimated_fuel_consumption": 20
    }
  }
}
```

**Nota**: Uso obsoleto - preferir `POST /checklists/{id}/submit`

---

### POST /vehicle-logs/exit

**Propósito**: Registrar salida de vehículo al fin del turno (histórico - deprecated)

**Requiere**: Autorización

**Request**:
```json
{
  "vehicle_id": 1,
  "checklist_id": 1,
  "mileage": 45000.50,
  "fuel_level": 85,
  "notes": "Inicio de turno"
}
```

**Response (201 Created)**:
```json
{
  "message": "Checklist de salida registrado",
  "data": {
    "log": {
      "id": 101,
      "vehicle_id": 1,
      "user_id": 1,
      "type": "exit",
      "mileage": 45000.50,
      "fuel_level": 85,
      "created_at": "2026-01-12T06:00:00Z"
    },
    "trip_id": 50
  }
}
```

**Nota**: Uso obsoleto - preferir `POST /checklists/{id}/submit`

---

### POST /vehicle-logs/{log}/photos

**Propósito**: Subir fotograf­ía de inspección de vehículo

**Requiere**: Autorización

**Path Parameters**:
- `log` (integer): ID del vehicle log

**Request** (multipart/form-data):
```
Content-Type: multipart/form-data

file: <archivo de imagen>
description: "Daño en parachoques frontal"
```

**Formatos aceptados**: JPEG, PNG, GIF, WebP
**Tamaño máximo**: 10 MB

**Response (201 Created)**:
```json
{
  "message": "Foto guardada exitosamente",
  "data": {
    "id": 45,
    "vehicle_log_id": 101,
    "file_path": "vehicle-logs/101/image_xyz123.jpg",
    "url": "https://storage.bitcar.com/vehicle-logs/101/image_xyz123.jpg",
    "created_at": "2026-01-12T06:15:00Z"
  }
}
```

**Response (422 Unprocessable Entity)**:
```json
{
  "message": "El archivo debe ser una imagen válida"
}
```

**Notas**:
- La foto se almacena en `storage/vehicle-logs/{log_id}/`
- Se retorna URL pública para descargar
- Es posible subir múltiples fotos

---

### POST /vehicle-logs/{log}/incidents

**Propósito**: Reportar incidente/daño encontrado

**Requiere**: Autorización

**Path Parameters**:
- `log` (integer): ID del vehicle log

**Request**:
```json
{
  "description": "Fuga de aceite en motor",
  "severity": "alta"
}
```

**Severity levels**: `baja` | `media` | `alta` | `critica`

**Response (201 Created)**:
```json
{
  "message": "Incidencia registrada",
  "data": {
    "id": 12,
    "vehicle_log_id": 101,
    "description": "Fuga de aceite en motor",
    "severity": "alta",
    "is_resolved": false,
    "created_at": "2026-01-12T10:45:00Z"
  }
}
```

**Notas**:
- Se registra automáticamente la fecha/hora
- El campo `is_resolved` comienza en `false`
- Importante para auditoría y mantenimiento

---

### POST /vehicle-logs/{log}/fuel-load

**Propósito**: Registrar carga de combustible

**Requiere**: Autorización

**Path Parameters**:
- `log` (integer): ID del vehicle log

**Request**:
```json
{
  "amount_liters": 45.5,
  "cost": 125000,
  "currency": "COP",
  "latitude": 10.3932,
  "longitude": -75.4830,
  "notes": "Gasolinera Shell - Cali"
}
```

**Response (201 Created)**:
```json
{
  "message": "Carga de combustible registrada",
  "data": {
    "id": 8,
    "vehicle_log_id": 101,
    "amount_liters": 45.5,
    "cost": 125000,
    "currency": "COP",
    "created_at": "2026-01-12T12:00:00Z"
  }
}
```

**Validaciones**:
- `amount_liters`: Mayor a 0.1
- `cost`: Mayor a 0
- `currency`: Código ISO 4217 (ej: COP, USD, EUR)
- `latitude`/`longitude`: Coordenadas GPS válidas

**Notas**:
- La ubicación (lat/long) se obtiene automáticamente del GPS
- Se registra para auditoría de consumo
- Útil para análisis de costos operacionales

---

## 🗺️ TRIPS (VIAJES)

### GET /trips

**Propósito**: Obtener historial de viajes del usuario

**Requiere**: Autorización

**Query Parameters**:
- `page` (integer, optional): Número de página (default: 1)
- `per_page` (integer, optional): Items por página (default: 20)

**Response (200 OK)**:
```json
{
  "data": [
    {
      "id": 50,
      "vehicle_id": 1,
      "vehicle": {
        "id": 1,
        "brand": "Toyota",
        "model": "Hilux",
        "license_plate": "ABC-123"
      },
      "start_time": "2026-01-12T06:30:00Z",
      "end_time": "2026-01-12T18:00:00Z",
      "start_mileage": 45000.50,
      "end_mileage": 45125.75,
      "distance_km": 125.25,
      "is_active": false,
      "created_at": "2026-01-12T06:30:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 20,
    "total": 45
  }
}
```

**Notas**:
- Paginación incluida automáticamente
- Ordena por viajes más recientes
- Carga relación con vehículo

---

### GET /trips/active

**Propósito**: Obtener viaje activo (en progreso) del usuario

**Requiere**: Autorización

**Response (200 OK)**:
```json
{
  "data": {
    "id": 50,
    "vehicle_id": 1,
    "vehicle": {
      "id": 1,
      "brand": "Toyota",
      "model": "Hilux",
      "license_plate": "ABC-123"
    },
    "start_time": "2026-01-12T06:30:00Z",
    "end_time": null,
    "start_mileage": 45000.50,
    "end_mileage": null,
    "is_active": true,
    "locations": [
      {
        "id": 1,
        "latitude": 10.3932,
        "longitude": -75.4830,
        "accuracy": 15,
        "speed": 45.5,
        "recorded_at": "2026-01-12T06:30:15Z"
      }
    ]
  }
}
```

**Response (404 Not Found)**:
```json
{
  "message": "No hay viaje activo"
}
```

**Notas**:
- Solo hay un viaje activo por usuario a la vez
- Incluye ubicaciones GPS registradas hasta el momento
- Usado frecuentemente para actualizar mapa en tiempo real

---

### POST /trips

**Propósito**: Iniciar un nuevo viaje

**Requiere**: Autorización

**Request**:
```json
{
  "vehicle_id": 1,
  "start_mileage": 45000.50,
  "start_fuel_level": 85
}
```

**Response (201 Created)**:
```json
{
  "message": "Viaje iniciado",
  "data": {
    "id": 50,
    "vehicle_id": 1,
    "user_id": 1,
    "start_time": "2026-01-12T06:30:00Z",
    "end_time": null,
    "start_mileage": 45000.50,
    "start_fuel_level": 85,
    "is_active": true,
    "created_at": "2026-01-12T06:30:00Z"
  }
}
```

**Response (403 Forbidden)**:
```json
{
  "message": "El vehículo no está asignado a ti"
}
```

**Response (422 Unprocessable Entity)**:
```json
{
  "message": "Ya existe un viaje activo para este vehículo"
}
```

**Validaciones**:
- `vehicle_id` debe existir y estar asignado al usuario
- No puede haber otro viaje activo en el mismo vehículo
- `start_mileage` debe ser positivo

**Notas**:
- Se registra automáticamente `start_time` como ahora
- Prepara para registrar ubicaciones GPS
- Solo puede haber un viaje activo por vehículo

---

### POST /trips/{trip}/locations

**Propósito**: Registrar múltiples ubicaciones GPS durante un viaje

**Requiere**: Autorización

**Path Parameters**:
- `trip` (integer): ID del trip

**Request** (batch):
```json
{
  "locations": [
    {
      "latitude": 10.3932,
      "longitude": -75.4830,
      "accuracy": 15,
      "speed": 45.5,
      "recorded_at": "2026-01-12T06:30:15Z"
    },
    {
      "latitude": 10.3945,
      "longitude": -75.4820,
      "accuracy": 12,
      "speed": 48.2,
      "recorded_at": "2026-01-12T06:31:15Z"
    }
  ]
}
```

**Response (201 Created)**:
```json
{
  "message": "Ubicaciones registradas",
  "data": {
    "locations_count": 2,
    "trip_id": 50
  }
}
```

**Response (403 Forbidden)**:
```json
{
  "message": "No autorizado"
}
```

**Response (422 Unprocessable Entity)**:
```json
{
  "message": "El viaje ya ha finalizado"
}
```

**Validaciones**:
- El viaje debe pertenecer al usuario autenticado
- El viaje debe estar activo (`end_time` es null)
- Coordenadas válidas (lat: -90 a 90, lon: -180 a 180)
- `recorded_at` debe ser datetime válido

**Notas**:
- Acepta hasta 100 ubicaciones por request (para eficiencia de red)
- Se inserta directamente en BD (bulk insert)
- Recomendado: Enviar cada 30-60 segundos
- Precision: accuracy <= 30 metros (descartar si > 100m)

---

### POST /trips/{trip}/end

**Propósito**: Finalizar un viaje en progreso

**Requiere**: Autorización

**Path Parameters**:
- `trip` (integer): ID del trip

**Request**:
```json
{
  "end_mileage": 45125.75,
  "end_fuel_level": 65,
  "notes": "Viaje completado sin incidentes"
}
```

**Response (200 OK)**:
```json
{
  "message": "Viaje finalizado",
  "data": {
    "id": 50,
    "vehicle_id": 1,
    "user_id": 1,
    "start_time": "2026-01-12T06:30:00Z",
    "end_time": "2026-01-12T18:00:00Z",
    "start_mileage": 45000.50,
    "end_mileage": 45125.75,
    "distance_km": 125.25,
    "estimated_fuel_consumption": 20,
    "is_active": false,
    "notes": "Viaje completado sin incidentes"
  }
}
```

**Response (403 Forbidden)**:
```json
{
  "message": "No autorizado"
}
```

**Response (422 Unprocessable Entity)**:
```json
{
  "message": "El viaje ya ha sido finalizado"
}
```

**Validaciones**:
- El viaje debe pertenecer al usuario autenticado
- `end_mileage` debe ser >= `start_mileage`
- `end_fuel_level` debe estar entre 0 y 100 (si se proporciona)

**Cálculos automáticos**:
- `distance_km` = `end_mileage` - `start_mileage`
- `estimated_fuel_consumption` = `start_fuel_level` - `end_fuel_level`
- `end_time` = ahora()

**Notas**:
- Se calcula automáticamente la distancia recorrida
- La distancia GPS (si hay puntos) se calcula con Haversine
- Se estima el consumo de combustible basado en los niveles

---

## ⚠️ INCIDENTES

### POST /vehicle-logs/{log}/incidents

**Ver**: [Vehicle Logs → Incidentes](#postvehicle-logslogincidents)

---

## ⛽ COMBUSTIBLE

### POST /vehicle-logs/{log}/fuel-load

**Ver**: [Vehicle Logs → Combustible](#postvehicle-logslogfuel-load)

---

## 🚨 CÓDIGOS DE ERROR

### Errores Comunes

| Código | Mensaje | Causa |
|--------|---------|-------|
| 401 | Unauthorized | Token expirado o inválido |
| 403 | Forbidden | Usuario no tiene permisos |
| 404 | Not Found | Recurso no existe |
| 422 | Unprocessable Entity | Validación fallida |
| 429 | Too Many Requests | Rate limit excedido |

### Respuesta de Error de Validación

```json
{
  "message": "Faltan campos requeridos",
  "errors": {
    "email": ["El campo email es requerido"],
    "password": ["El password debe tener mínimo 6 caracteres"]
  }
}
```

---

## 📝 EJEMPLOS DE USO

### Ejemplo 1: Flujo Completo de Entrada-Viaje-Salida

```bash
# 1. Login
curl -X POST https://api.bitcar.com/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "operador@example.com",
    "password": "password123"
  }'

# Respuesta contiene TOKEN

# 2. Obtener checklists activos
curl -X GET https://api.bitcar.com/api/v1/checklists/active \
  -H "Authorization: Bearer {TOKEN}"

# 3. Enviar checklist de entrada
curl -X POST https://api.bitcar.com/api/v1/checklists/1/submit \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "vehicle_id": 1,
    "type": "entrada",
    "mileage": 45000.50,
    "fuel_level": 85,
    "items": [
      {
        "checklist_item_id": 1,
        "boolean_answer": true
      }
    ]
  }'

# 4. Iniciar viaje
curl -X POST https://api.bitcar.com/api/v1/trips \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "vehicle_id": 1,
    "start_mileage": 45000.50,
    "start_fuel_level": 85
  }'

# 5. Registrar ubicaciones GPS (cada 30 segundos durante el viaje)
curl -X POST https://api.bitcar.com/api/v1/trips/50/locations \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "locations": [
      {
        "latitude": 10.3932,
        "longitude": -75.4830,
        "accuracy": 15,
        "speed": 45.5,
        "recorded_at": "2026-01-12T06:30:15Z"
      }
    ]
  }'

# 6. Finalizar viaje
curl -X POST https://api.bitcar.com/api/v1/trips/50/end \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "end_mileage": 45125.75,
    "end_fuel_level": 65
  }'

# 7. Enviar checklist de salida
curl -X POST https://api.bitcar.com/api/v1/checklists/2/submit \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "vehicle_id": 1,
    "type": "salida",
    "mileage": 45125.75,
    "fuel_level": 65,
    "items": [
      {
        "checklist_item_id": 5,
        "boolean_answer": true
      }
    ]
  }'

# 8. Logout
curl -X POST https://api.bitcar.com/api/v1/logout \
  -H "Authorization: Bearer {TOKEN}"
```

### Ejemplo 2: Reportar Incidente

```bash
curl -X POST https://api.bitcar.com/api/v1/vehicle-logs/101/incidents \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "description": "Daño en parachoques frontal durante entrega",
    "severity": "media"
  }'
```

### Ejemplo 3: Subir Fotografía

```bash
curl -X POST https://api.bitcar.com/api/v1/vehicle-logs/101/photos \
  -H "Authorization: Bearer {TOKEN}" \
  -F "file=@/path/to/photo.jpg" \
  -F "description=Daño en puerta delantera"
```

### Ejemplo 4: Registrar Carga de Combustible

```bash
curl -X POST https://api.bitcar.com/api/v1/vehicle-logs/101/fuel-load \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "amount_liters": 45.5,
    "cost": 125000,
    "currency": "COP",
    "latitude": 10.3932,
    "longitude": -75.4830,
    "notes": "Gasolinera Shell - Cali"
  }'
```

---

## 🔄 FLUJOS RECOMENDADOS

### Flujo de Operador - Día Completo

```
LOGIN
  ↓
INICIO DE TURNO
  ├─ Obtener Checklists Activos
  ├─ Completar Checklist de Entrada
  ├─ Subir Fotos (inicio)
  └─ Firmar Digitalmente
  ↓
VIAJE ACTIVO
  ├─ Iniciar Viaje
  ├─ Registrar Ubicaciones GPS (cada 30s)
  ├─ Agregar Puntos/Paradas (si aplica)
  └─ Reportar Incidentes (si aplica)
  ↓
FIN DE VIAJE
  ├─ Finalizar Viaje
  ├─ Registrar Combustible
  └─ Revisar Resumen
  ↓
FIN DE TURNO
  ├─ Completar Checklist de Salida
  ├─ Subir Fotos (final)
  ├─ Firmar Digitalmente
  └─ LOGOUT
```

---

## 📊 CAMPOS Y TIPOS DE DATOS

| Campo | Tipo | Rango | Ejemplo |
|-------|------|-------|---------|
| ID | integer | > 0 | 1, 50, 1000 |
| Email | string | Email válido | user@example.com |
| Token | string | 256 caracteres | eyJ0eXA... |
| Mileage | decimal | 0 a 999999.99 | 45000.50 |
| Fuel Level | decimal | 0 a 100 | 85.5 |
| Latitude | decimal | -90 a 90 | 10.39321234 |
| Longitude | decimal | -180 a 180 | -75.48301234 |
| Speed | decimal | 0 a 300 | 45.5 |
| Accuracy | integer | 0 a 1000 | 15 |

---

## 🔗 RELACIONES DE DATOS

```
User
├── Has Many: VehicleAssignments
├── Has Many: VehicleLogs
├── Has Many: Trips
└── Has Many: FuelLoads

Vehicle
├── Has Many: VehicleAssignments
├── Has Many: VehicleLogs
├── Has Many: Trips
└── Has Many: FuelLoads

VehicleLog
├── Belongs To: User
├── Belongs To: Vehicle
├── Has Many: VehicleLogItems
├── Has Many: VehicleLogPhotos
├── Has Many: Incidents
└── Has Many: FuelLoads

Trip
├── Belongs To: User
├── Belongs To: Vehicle
└── Has Many: TripLocations

TripLocation
└── Belongs To: Trip

Incident
└── Belongs To: VehicleLog

FuelLoad
└── Belongs To: VehicleLog
```

---

## 💡 MEJORES PRÁCTICAS

### Seguridad
- ✅ Guardar token en Secure Storage
- ✅ Validar respuestas del servidor
- ✅ No guardar datos sensibles en localStorage
- ✅ Implementar timeout de sesión (15 minutos de inactividad)
- ✅ Validar certificados SSL en producción

### Performance
- ✅ Hacer batch de ubicaciones GPS (máximo 100 por request)
- ✅ Cachear checklists al iniciar app
- ✅ Implementar offline-first para sincronización
- ✅ Comprimir imágenes antes de subir
- ✅ Usar paginación en listados

### Confiabilidad
- ✅ Reintentar requests fallidos (exponential backoff)
- ✅ Validar datos antes de enviar
- ✅ Manejar errores de conexión elegantemente
- ✅ Sincronizar datos offline cuando hay conexión
- ✅ Mantener logs locales para debugging

---

## 📞 SOPORTE

**Base URL Production**: `https://tu-api.com/api/v1`  
**Documentación**: [Consultar APP_MOVIL_PROMPT.md]  
**Estado del API**: [Consultar health check endpoint]

**Última actualización**: 12 de enero de 2026  
**Versión del API**: 1.0  
**Última revisión**: Endpoints implementados y funcionales
