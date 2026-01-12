# 🚀 BITCAR API - Guía Rápida de Referencia

**Generado**: 12 de enero de 2026  
**Base URL**: `https://tu-backend.com/api/v1`  
**Versión**: 1.0

---

## 📌 ENDPOINTS POR CATEGORÍA

### 🔐 AUTENTICACIÓN (Públicos)
```
POST   /login          → Autenticar y obtener token
POST   /logout         → Cerrar sesión (requiere auth)
GET    /me             → Datos del usuario actual (requiere auth)
```

### 📋 CHECKLISTS (Requieren auth)
```
GET    /checklists/active                   → Listar checklists disponibles
POST   /checklists/{id}/submit              → Enviar respuestas ✨ NUEVO
```

### 🚗 VEHICLE LOGS - BITÁCORAS (Requieren auth)
```
POST   /vehicle-logs/entry                  → Entrada de vehículo (deprecado)
POST   /vehicle-logs/exit                   → Salida de vehículo (deprecado)
POST   /vehicle-logs/{log}/photos           → Subir foto ✨ NUEVO
POST   /vehicle-logs/{log}/incidents        → Reportar incidente
POST   /vehicle-logs/{log}/fuel-load        → Registrar combustible ✨ NUEVO
```

### 🗺️ TRIPS - VIAJES (Requieren auth)
```
GET    /trips                               → Historial de viajes
GET    /trips/active                        → Viaje activo actual
POST   /trips                               → Iniciar nuevo viaje ✨ NUEVO
POST   /trips/{trip}/locations              → Registrar ubicaciones GPS
POST   /trips/{trip}/end                    → Finalizar viaje ✨ NUEVO
```

---

## 🔄 FLUJO TÍPICO DE USO

### Entrada (Inicio del día)
```
1. POST /login                    [email, password]
2. GET  /checklists/active        [obtener lista]
3. POST /checklists/{id}/submit   [llenar checklist]
   - vehicle_id, type, mileage, fuel_level, items
4. POST /vehicle-logs/{log}/photos [fotos de entrada]
```

### Viaje Activo
```
1. POST /trips                    [create]
2. POST /trips/{trip}/locations   [cada 30 segundos]
3. POST /vehicle-logs/{log}/incidents [si aplica]
4. POST /vehicle-logs/{log}/fuel-load [si aplica]
```

### Salida (Fin del día)
```
1. POST /trips/{trip}/end         [finalizar viaje]
2. POST /checklists/{id}/submit   [checklist salida]
3. POST /vehicle-logs/{log}/photos [fotos finales]
4. POST /logout                   [cerrar sesión]
```

---

## 📊 TABLA RÁPIDA DE ENDPOINTS

| Método | Endpoint | Auth | Nuevo | Descripción |
|--------|----------|------|-------|-------------|
| POST | /login | ❌ | | Autenticar |
| POST | /logout | ✅ | | Cerrar sesión |
| GET | /me | ✅ | | Datos usuario |
| GET | /checklists/active | ✅ | | Listar checklists |
| POST | /checklists/{id}/submit | ✅ | ✨ | Enviar checklist |
| POST | /vehicle-logs/entry | ✅ | | Entrada (deprecado) |
| POST | /vehicle-logs/exit | ✅ | | Salida (deprecado) |
| POST | /vehicle-logs/{log}/photos | ✅ | ✨ | Subir foto |
| POST | /vehicle-logs/{log}/incidents | ✅ | | Incidente |
| POST | /vehicle-logs/{log}/fuel-load | ✅ | ✨ | Carga combustible |
| GET | /trips | ✅ | | Historial viajes |
| GET | /trips/active | ✅ | | Viaje activo |
| POST | /trips | ✅ | ✨ | Iniciar viaje |
| POST | /trips/{trip}/locations | ✅ | | Ubicaciones GPS |
| POST | /trips/{trip}/end | ✅ | ✨ | Finalizar viaje |

---

## 🎯 PAYLOADS PRINCIPALES

### Login
```json
{
  "email": "operador@example.com",
  "password": "password123"
}
```

### Checklist Submit
```json
{
  "vehicle_id": 1,
  "type": "entrada",
  "mileage": 45000.50,
  "fuel_level": 85,
  "items": [
    { "checklist_item_id": 1, "boolean_answer": true },
    { "checklist_item_id": 2, "text_answer": "OK" }
  ]
}
```

### Iniciar Viaje
```json
{
  "vehicle_id": 1,
  "start_mileage": 45000.50,
  "start_fuel_level": 85
}
```

### Registrar Ubicaciones
```json
{
  "locations": [
    {
      "latitude": 10.3932,
      "longitude": -75.4830,
      "accuracy": 15,
      "speed": 45.5,
      "recorded_at": "2026-01-12T06:30:15Z"
    }
  ]
}
```

### Finalizar Viaje
```json
{
  "end_mileage": 45125.75,
  "end_fuel_level": 65
}
```

### Reportar Incidente
```json
{
  "description": "Daño en parachoques",
  "severity": "media"
}
```

### Cargar Combustible
```json
{
  "amount_liters": 45.5,
  "cost": 125000,
  "currency": "COP"
}
```

### Subir Foto (multipart)
```
file: <imagen.jpg>
description: "Daño en puerta"
```

---

## 🔑 HEADERS REQUERIDOS

### Todas las solicitudes
```
Content-Type: application/json
Accept: application/json
```

### Solicitudes Autenticadas
```
Authorization: Bearer {token}
```

### Para subir fotos
```
Content-Type: multipart/form-data
Authorization: Bearer {token}
```

---

## ✅ VALIDACIONES CLAVE

| Campo | Validación |
|-------|-----------|
| email | Formato válido |
| password | Mínimo 6 caracteres |
| vehicle_id | Debe existir y estar asignado |
| mileage | Número positivo |
| fuel_level | Entre 0 y 100 |
| latitude | Entre -90 y 90 |
| longitude | Entre -180 y 180 |
| type | "entrada" o "salida" |
| severity | "baja", "media", "alta", "critica" |
| amount_liters | Mayor a 0.1 |
| currency | Código ISO (COP, USD, etc) |

---

## 🚨 CÓDIGOS DE ERROR COMUNES

| Código | Mensaje | Causa |
|--------|---------|-------|
| 401 | Unauthorized | Token expirado/inválido |
| 403 | Forbidden | Acceso denegado |
| 404 | Not Found | Recurso no existe |
| 422 | Validation Failed | Datos inválidos |
| 429 | Too Many Requests | Rate limit |
| 500 | Server Error | Error del servidor |

---

## 💾 CAMBIOS IMPLEMENTADOS

✨ **Nuevos Endpoints Implementados**:

1. ✅ `POST /checklists/{id}/submit` 
   - Envía respuestas completadas de checklist
   - Crea automaticamente VehicleLog

2. ✅ `POST /vehicle-logs/{log}/photos`
   - Sube fotografías de inspección
   - Soporta JPEG, PNG, GIF, WebP

3. ✅ `POST /trips`
   - Iniciar nuevo viaje
   - Validaciones de asignación y conflictos

4. ✅ `POST /trips/{trip}/end`
   - Finalizar viaje en progreso
   - Calcula distancia y consumo

5. ✅ `POST /vehicle-logs/{log}/fuel-load`
   - Registra carga de combustible
   - Con ubicación GPS y costo

### Modelos Creados
- ✅ `FuelLoad` - Nuevo modelo para cargas de combustible

### Rutas Actualizadas
- ✅ routes/api.php - Agregadas todas las nuevas rutas

---

## 📁 DOCUMENTACIÓN DISPONIBLE

En `/docs`:

1. **APP_MOVIL_PROMPT.md** - Guía completa para desarrollo mobile
2. **API_ENDPOINTS_COMPLETOS.md** - Este documento (documentación detallada)
3. **GUIA_RAPIDA_ENDPOINTS.md** - Esta guía rápida
4. **SCHEMA_ACTUAL_BITCAR.md** - Esquema de base de datos
5. **DATABASE_SCHEME.md** - Documentación legacy

---

## 🔗 URLs ÚTILES

**Base URL**: `https://tu-backend.com/api/v1`  
**Docs Mobile**: [APP_MOVIL_PROMPT.md](APP_MOVIL_PROMPT.md)  
**Docs API**: [API_ENDPOINTS_COMPLETOS.md](API_ENDPOINTS_COMPLETOS.md)  
**Schema**: [SCHEMA_ACTUAL_BITCAR.md](SCHEMA_ACTUAL_BITCAR.md)

---

## ⚡ TIPS DE IMPLEMENTACIÓN

### Frontend Mobile
- Guardar token en Secure Storage
- Implementar retry logic con exponential backoff
- Cachear checklists al iniciar
- Implementar offline-first sync
- Enviar ubicaciones cada 30 segundos durante viaje

### Backend
- Rate limiting: máximo 100 requests/minuto por usuario
- Timeout conexión: 30 segundos
- Máximo tamaño imagen: 10 MB
- Máximo locations por request: 100
- Validar todos los inputs

---

**Última actualización**: 12 de enero de 2026  
**Estado**: ✅ Producción - Todos los endpoints implementados
