# 📱 BITCAR Mobile - Prompt Completo para Desarrollo

**Versión**: 1.0  
**Fecha**: 12 de enero de 2026  
**Proyecto**: BITCAR Mobile App (Expo + React Native)  
**Backend**: Laravel API v1 disponible en `https://api.bitcar.local/api/v1`

---

## 📋 TABLA DE CONTENIDOS

1. [Contexto de Negocio](#contexto-de-negocio)
2. [Requisitos Funcionales](#requisitos-funcionales)
3. [Especificaciones Técnicas](#especificaciones-técnicas)
4. [API Endpoints](#api-endpoints)
5. [Estructura de Datos](#estructura-de-datos)
6. [Arquitectura de la App](#arquitectura-de-la-app)
7. [Stack Tecnológico](#stack-tecnológico)
8. [Guías de Seguridad](#guías-de-seguridad)
9. [Flujos de Usuario](#flujos-de-usuario)
10. [Instrucciones de Setup](#instrucciones-de-setup)

---

## 🎯 CONTEXTO DE NEGOCIO

### Visión
**BITCAR** es un sistema completo de gestión vehicular diseñado para operadores de flota. La app móvil permite a los **operadores de unidades** registrar actividades, inspeccionar vehículos, rastrear viajes GPS en tiempo real y reportar incidentes, directamente desde dispositivos móviles.

### Usuarios Objetivo
- **Operadores**: Conductores/operadores de vehículos (rol: `operador`)
- **Supervisores**: Pueden monitorear desde el dashboard web (no incluido en mobile)
- **Administradores**: Configuran el sistema (no incluido en mobile)

### Funcionalidades Principales
1. ✅ **Autenticación segura** con tokens Sanctum
2. ✅ **Checklists de inspección** - Entrada/salida de vehículos
3. ✅ **Gestión de Viajes** - Inicio, seguimiento GPS, fin de viaje
4. ✅ **Registro de Incidentes** - Reportar problemas del vehículo
5. ✅ **Consumo de Combustible** - Registrar cargas de gasolina
6. ✅ **Captura de Fotos** - Documentar estado del vehículo
7. ✅ **Firma Digital** - Validación de checklists completados
8. ✅ **Modo Offline** - Sincronización automática cuando hay conexión

---

## 📊 REQUISITOS FUNCIONALES

### 1. AUTENTICACIÓN (RF-001)

**Descripción**: Sistema de login seguro para operadores

**Funcionalidades**:
- Login con email/contraseña
- Mantener sesión activa con token
- Logout seguro
- Manejo de refresh tokens
- Recuperación de datos del usuario autenticado

**Flujo**:
```
Login → Validar credenciales → Guardar token → Acceso a pantallas privadas
```

**Datos requeridos**:
```json
{
  "user": {
    "id": "number",
    "name": "string",
    "email": "string",
    "phone": "string",
    "role": "operador|supervisor|admin",
    "created_at": "datetime"
  },
  "token": "string"
}
```

---

### 2. DASHBOARD/HOME (RF-002)

**Descripción**: Pantalla principal con resumen de actividades

**Elementos**:
- Nombre y foto del operador
- Vehículo asignado actualmente (si existe)
  - Marca, modelo, placa
  - Kilometraje actual
  - Nivel de combustible
  - Estado
- Últimas actividades
  - Último checklist realizado
  - Viaje activo (si existe)
  - Incidentes sin resolver
- Accesos directos a funciones principales

---

### 3. GESTIÓN DE CHECKLISTS (RF-003)

**Descripción**: Completar inspecciones de vehículos al inicio y fin del turno

#### 3.1 Listar Checklists Activos
- **Pantalla**: Inicio de sesión
- **Acción**: GET `/v1/checklists/active`
- **Mostrar**: Lista de checklists disponibles
- **Objetivo**: Que el operador seleccione qué checklist llenar

#### 3.2 Formulario Dinámico de Checklist
**Tipos de preguntas soportadas**:
1. **Boolean** (Sí/No) - Con radio buttons
2. **Text** (Texto libre) - Input field
3. **Number** (Número) - Input numérico
4. **Photo** (Foto) - Captura desde cámara
5. **Signature** (Firma) - Captura de firma digital

**Validaciones**:
- Ítems marcados como `required: true` son obligatorios
- No permitir envío si hay campos requeridos vacíos
- Mostrar progreso (X de Y ítems completados)

**Estados**:
- **En progreso**: Puede guardarse como borrador
- **Completado**: Puede enviarse al backend
- **Enviado**: Mostrar confirmación

**Acciones**:
- Guardar como borrador (AsyncStorage)
- Continuar después (resumir desde el punto actual)
- Enviar al backend
- Cancelar

---

### 4. ENTRADA/SALIDA DE VEHÍCULOS (RF-004)

**Descripción**: Registrar cuando operador toma o devuelve vehículo

#### 4.1 Entrada de Vehículo
**Pantalla**: Después de completar checklist de entrada

**Datos a registrar**:
```json
{
  "vehicle_id": "number",
  "checklist_id": "number",
  "type": "entrada",
  "mileage": "decimal",
  "fuel_level": "decimal (0-100 %)",
  "notes": "string opcional",
  "checklist_responses": []
}
```

**Campos en formulario**:
1. Seleccionar vehículo asignado
2. Ingresar kilometraje (leer desde vehículo si es posible)
3. Ingresar nivel de combustible (visual gauge)
4. Fotografías del estado general
5. Firmar digitalmente

#### 4.2 Salida de Vehículo
**Idéntico a entrada pero con `type: "salida"`**

**Flujo esperado**:
```
Home → Entrada/Salida Vehículo → Seleccionar Checklist → 
Llenar Formulario → Tomar Fotos → Firmar → Enviar
```

---

### 5. GESTIÓN DE VIAJES (RF-005)

**Descripción**: Rastrear viajes con seguimiento GPS en tiempo real

#### 5.1 Iniciar Viaje
**Pantalla**: Viaje activo

**Requisitos**:
- Usuario debe tener vehículo asignado
- Debe haber hecho checklist de entrada previamente

**Datos**:
```json
{
  "vehicle_id": "number",
  "start_time": "datetime (ahora)",
  "start_mileage": "decimal",
  "start_fuel_level": "decimal",
  "is_active": true
}
```

**Acciones**:
- Iniciar rastreo GPS cada 30 segundos (configurable)
- Mostrar mapa con ubicación en tiempo real
- Mostrar velocidad actual
- Acumular distancia recorrida
- Permitir agregar puntos/paradas

#### 5.2 Rastreo Durante Viaje
**Pantalla**: Mapa en tiempo real

**Funcionalidades**:
- Mapa (Google Maps o Mapbox)
- Marcador de ubicación actual
- Ruta del viaje (polyline)
- Datos en vivo:
  - Velocidad actual (km/h)
  - Distancia acumulada
  - Tiempo en viaje
  - Ubicación (lat/long)
- Botones de acción:
  - **+ Punto**: Agregar parada/punto de interés
  - **Pausa**: Pausar rastreo temporalmente
  - **Fin Viaje**: Terminar viaje

**Precisión GPS**:
- Mínimo: accuracy > 30 metros
- Descartar puntos con accuracy > 100 metros
- Usar GPS + Network location (híbrido)

#### 5.3 Agregar Puntos en el Viaje
**Popup cuando toca "+ Punto"**:
```json
{
  "latitude": "decimal(10,8)",
  "longitude": "decimal(10,8)",
  "type": "parada|entrega|recogida|otro",
  "description": "string",
  "photos": ["array de IDs"]
}
```

#### 5.4 Finalizar Viaje
**Formulario**:
1. Confirmar término del viaje
2. Ingresar kilometraje final
3. Ingresar nivel de combustible final
4. Agregar notas (opcional)
5. Tomar foto final del odómetro (recomendado)

**Validaciones**:
- Kilometraje final > Inicial
- Fuel level registrado
- Mínimo 1 punto GPS registrado

**Datos enviados**:
```json
{
  "end_time": "datetime",
  "end_mileage": "decimal",
  "end_fuel_level": "decimal",
  "distance_km": "decimal (calculado)",
  "notes": "string",
  "is_active": false
}
```

---

### 6. REPORTE DE INCIDENTES (RF-006)

**Descripción**: Reportar problemas/daños del vehículo

**Cuándo**: Desde el dashboard o durante viaje activo

**Formulario**:
```
1. Descripción detallada (textarea)
2. Severidad (baja/media/alta/crítica)
3. Fotografía del incidente (requerida)
4. Ubicación GPS (automática)
5. Enviar
```

**Validaciones**:
- Descripción mínimo 10 caracteres
- Foto requerida
- Confirmar envío

**Respuesta**:
```json
{
  "id": "number",
  "description": "string",
  "severity": "baja|media|alta|critica",
  "is_resolved": false,
  "created_at": "datetime"
}
```

---

### 7. REGISTRO DE COMBUSTIBLE (RF-007)

**Descripción**: Registrar carga de gasolina

**Cuándo**: Desde dashboard o después de finalizar viaje

**Formulario**:
```
1. Cantidad cargada (litros)
2. Costo total (moneda local)
3. Ubicación de gasolinera (GPS automático)
4. Foto del recibo (recomendada)
5. Enviar
```

**Validaciones**:
- No exceder capacidad del vehículo
- Cantidad > 0
- Costo > 0

---

## 🔧 ESPECIFICACIONES TÉCNICAS

### Ambiente de Desarrollo
- **Node**: v18+ (recomendado v20)
- **Expo**: 51.x o superior
- **React Native**: 0.73.x o superior
- **TypeScript**: 5.x (recomendado)
- **OS Soportados**: iOS 13.4+, Android 5.0+

### Permisos Necesarios

#### iOS
```xml
<key>NSLocationWhenInUseUsageDescription</key>
<string>BITCAR necesita acceso a tu ubicación para rastrear viajes</string>

<key>NSPhotoLibraryUsageDescription</key>
<string>BITCAR necesita acceso a fotos para capturar inspecciones</string>

<key>NSCameraUsageDescription</key>
<string>BITCAR necesita acceso a cámara para evidencia fotográfica</string>
```

#### Android
```xml
<uses-permission android:name="android.permission.ACCESS_FINE_LOCATION" />
<uses-permission android:name="android.permission.ACCESS_COARSE_LOCATION" />
<uses-permission android:name="android.permission.CAMERA" />
<uses-permission android:name="android.permission.READ_EXTERNAL_STORAGE" />
<uses-permission android:name="android.permission.WRITE_EXTERNAL_STORAGE" />
<uses-permission android:name="android.permission.INTERNET" />
```

### Configuración de app.json
```json
{
  "expo": {
    "name": "BITCAR Mobile",
    "slug": "bitcar-mobile",
    "version": "1.0.0",
    "assetBundlePatterns": ["**/*"],
    "ios": {
      "supportsTabletMode": false,
      "bundleIdentifier": "com.antoniomota.bitcar"
    },
    "android": {
      "package": "com.antoniomota.bitcar",
      "versionCode": 1
    },
    "plugins": [
      [
        "expo-location",
        { "locationAlwaysAndWhenInUsePermission": "Allow" }
      ],
      [
        "expo-camera",
        { "cameraPermission": "Allow BITCAR to access your camera." }
      ]
    ]
  }
}
```

---

## 🔌 API ENDPOINTS

### Base URL
```
https://tu-backend.com/api/v1
```

### Headers Requeridos
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

---

### 🔐 AUTENTICACIÓN

#### Login
```
POST /login
```

**Request**:
```json
{
  "email": "operador@example.com",
  "password": "contraseña"
}
```

**Response (200)**:
```json
{
  "user": {
    "id": 1,
    "name": "Juan García",
    "email": "operador@example.com",
    "phone": "3001234567",
    "role": "operador",
    "created_at": "2025-01-10T14:30:00Z"
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

**Response (401)**:
```json
{
  "message": "Las credenciales no coinciden con nuestros registros"
}
```

---

#### Get Current User
```
GET /me
```

**Headers**:
- Authorization: Bearer {token}

**Response (200)**:
```json
{
  "id": 1,
  "name": "Juan García",
  "email": "operador@example.com",
  "phone": "3001234567",
  "role": "operador",
  "created_at": "2025-01-10T14:30:00Z"
}
```

---

#### Logout
```
POST /logout
```

**Response (200)**:
```json
{
  "message": "Logged out successfully"
}
```

---

### 📋 CHECKLISTS

#### Get Active Checklists
```
GET /checklists/active
```

**Response (200)**:
```json
{
  "data": [
    {
      "id": 1,
      "name": "Inspección Entrada",
      "description": "Checklist de entrada diaria",
      "is_active": true,
      "items": [
        {
          "id": 1,
          "label": "Luces delanteras",
          "description": "Verificar funcionamiento de luces",
          "type": "boolean",
          "order": 1,
          "required": true
        },
        {
          "id": 2,
          "label": "Nivel de aceite",
          "description": "Verificar con varilla",
          "type": "text",
          "order": 2,
          "required": false
        }
      ]
    }
  ]
}
```

---

#### Submit Checklist (NUEVO ENDPOINT REQUERIDO)
```
POST /checklists/{id}/submit
```

**Request**:
```json
{
  "vehicle_id": 1,
  "type": "entrada",
  "mileage": 45000.50,
  "fuel_level": 85,
  "notes": "Vehículo en buen estado",
  "items": [
    {
      "checklist_item_id": 1,
      "boolean_answer": true
    },
    {
      "checklist_item_id": 2,
      "text_answer": "Nivel OK"
    }
  ]
}
```

**Response (201)**:
```json
{
  "id": 1,
  "vehicle_id": 1,
  "type": "entrada",
  "mileage": 45000.50,
  "fuel_level": 85,
  "created_at": "2026-01-12T10:30:00Z"
}
```

---

### 🚗 VEHICLE LOGS

#### Create Entry Log
```
POST /vehicle-logs/entry
```

**Request**:
```json
{
  "vehicle_id": 1,
  "checklist_id": 1,
  "mileage": 45000.50,
  "fuel_level": 85,
  "notes": "Entrada normal"
}
```

**Response (201)**:
```json
{
  "id": 101,
  "vehicle_id": 1,
  "user_id": 1,
  "checklist_id": 1,
  "type": "entrada",
  "mileage": 45000.50,
  "fuel_level": 85,
  "created_at": "2026-01-12T06:00:00Z"
}
```

---

#### Create Exit Log
```
POST /vehicle-logs/exit
```

**Request**:
```json
{
  "vehicle_id": 1,
  "checklist_id": 1,
  "mileage": 45050.75,
  "fuel_level": 70,
  "notes": "Salida normal"
}
```

**Response (201)**:
```json
{
  "id": 102,
  "vehicle_id": 1,
  "user_id": 1,
  "checklist_id": 1,
  "type": "salida",
  "mileage": 45050.75,
  "fuel_level": 70,
  "created_at": "2026-01-12T18:00:00Z"
}
```

---

#### Add Incident to Log
```
POST /vehicle-logs/{log_id}/incidents
```

**Request**:
```json
{
  "description": "Daño en parachoques frontal",
  "severity": "media",
  "latitude": 10.3932,
  "longitude": -75.4830
}
```

**Response (201)**:
```json
{
  "id": 5,
  "vehicle_log_id": 101,
  "description": "Daño en parachoques frontal",
  "severity": "media",
  "is_resolved": false,
  "created_at": "2026-01-12T10:45:00Z"
}
```

---

#### Upload Photos to Log (NUEVO ENDPOINT REQUERIDO)
```
POST /vehicle-logs/{log_id}/photos
```

**Request** (multipart/form-data):
```
file: [imagen]
description: "Foto del estado inicial"
```

**Response (201)**:
```json
{
  "id": 1,
  "vehicle_log_id": 101,
  "file_path": "vehicle-logs/2026-01-12/photo_abc123.jpg",
  "description": "Foto del estado inicial",
  "created_at": "2026-01-12T06:05:00Z"
}
```

---

### 🗺️ VIAJES

#### Get Active Trip
```
GET /trips/active
```

**Response (200)**:
```json
{
  "id": 50,
  "vehicle_id": 1,
  "user_id": 1,
  "start_time": "2026-01-12T06:30:00Z",
  "end_time": null,
  "start_mileage": 45000.50,
  "end_mileage": null,
  "start_fuel_level": 85,
  "end_fuel_level": null,
  "distance_km": null,
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
```

---

#### Get All Trips
```
GET /trips?page=1&per_page=10
```

**Response (200)**:
```json
{
  "data": [
    {
      "id": 50,
      "vehicle_id": 1,
      "start_time": "2026-01-12T06:30:00Z",
      "end_time": "2026-01-12T18:00:00Z",
      "distance_km": 125.5,
      "is_active": false
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total": 45
  }
}
```

---

#### Create Trip (NUEVO ENDPOINT REQUERIDO)
```
POST /trips
```

**Request**:
```json
{
  "vehicle_id": 1,
  "start_mileage": 45000.50,
  "start_fuel_level": 85
}
```

**Response (201)**:
```json
{
  "id": 50,
  "vehicle_id": 1,
  "user_id": 1,
  "start_time": "2026-01-12T06:30:00Z",
  "start_mileage": 45000.50,
  "start_fuel_level": 85,
  "is_active": true
}
```

---

#### Store Trip Locations
```
POST /trips/{trip_id}/locations
```

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

**Response (201)**:
```json
{
  "message": "2 locations stored successfully",
  "count": 2
}
```

---

#### End Trip (NUEVO ENDPOINT REQUERIDO)
```
POST /trips/{trip_id}/end
```

**Request**:
```json
{
  "end_mileage": 45125.75,
  "end_fuel_level": 65,
  "notes": "Viaje completado sin incidentes"
}
```

**Response (200)**:
```json
{
  "id": 50,
  "vehicle_id": 1,
  "start_time": "2026-01-12T06:30:00Z",
  "end_time": "2026-01-12T18:00:00Z",
  "start_mileage": 45000.50,
  "end_mileage": 45125.75,
  "distance_km": 125.25,
  "fuel_consumed": 20,
  "is_active": false
}
```

---

### ⚠️ INCIDENTES

#### Report Incident (IMPLEMENTACIÓN EXISTENTE)
```
POST /vehicle-logs/{log_id}/incidents
```

**Request**:
```json
{
  "description": "Fuga de aceite",
  "severity": "alta"
}
```

**Response (201)**:
```json
{
  "id": 5,
  "vehicle_log_id": 101,
  "description": "Fuga de aceite",
  "severity": "alta",
  "is_resolved": false,
  "created_at": "2026-01-12T10:45:00Z"
}
```

---

### ⛽ COMBUSTIBLE (NUEVO ENDPOINT REQUERIDO)

#### Register Fuel Load
```
POST /vehicle-logs/{log_id}/fuel-load
```

**Request**:
```json
{
  "amount_liters": 45.5,
  "cost": 125000,
  "currency": "COP",
  "latitude": 10.3932,
  "longitude": -75.4830,
  "notes": "Gasolinera Shell"
}
```

**Response (201)**:
```json
{
  "id": 1,
  "vehicle_log_id": 101,
  "amount_liters": 45.5,
  "cost": 125000,
  "created_at": "2026-01-12T12:00:00Z"
}
```

---

## 📊 ESTRUCTURA DE DATOS

### Modelos TypeScript

```typescript
// User
interface User {
  id: number;
  name: string;
  email: string;
  phone?: string;
  role: 'operador' | 'supervisor' | 'admin';
  created_at: string;
}

// Vehicle
interface Vehicle {
  id: number;
  brand: string;
  model: string;
  year: number;
  license_plate: string;
  vin?: string;
  color: string;
  type: 'pickup' | 'sedan' | 'suv' | 'van' | 'camion';
  mileage: number;
  fuel_capacity: number;
  status: 'activo' | 'mantenimiento' | 'inactivo';
}

// Checklist
interface Checklist {
  id: number;
  name: string;
  description?: string;
  is_active: boolean;
  items: ChecklistItem[];
}

interface ChecklistItem {
  id: number;
  checklist_id: number;
  label: string;
  description?: string;
  type: 'boolean' | 'text' | 'number' | 'photo' | 'signature';
  order: number;
  required: boolean;
}

// VehicleLog (Entrada/Salida)
interface VehicleLog {
  id: number;
  vehicle_id: number;
  user_id: number;
  checklist_id?: number;
  type: 'entrada' | 'salida';
  mileage: number;
  fuel_level?: number;
  notes?: string;
  created_at: string;
}

// Trip (Viaje)
interface Trip {
  id: number;
  vehicle_id: number;
  user_id: number;
  start_time: string;
  end_time?: string;
  start_mileage: number;
  end_mileage?: number;
  start_fuel_level?: number;
  end_fuel_level?: number;
  distance_km?: number;
  is_active: boolean;
  locations?: TripLocation[];
}

interface TripLocation {
  id: number;
  trip_id: number;
  latitude: number;
  longitude: number;
  accuracy?: number;
  speed?: number;
  recorded_at: string;
}

// Incident
interface Incident {
  id: number;
  vehicle_log_id: number;
  description: string;
  severity: 'baja' | 'media' | 'alta' | 'critica';
  is_resolved: boolean;
  resolution_notes?: string;
  created_at: string;
}

// AuthToken
interface AuthToken {
  user: User;
  token: string;
}
```

---

## 🏗️ ARQUITECTURA DE LA APP

### Estructura de Carpetas Recomendada

```
BITCAR-Mobile/
├── app.json                          # Config Expo
├── app.tsx                          # Entry point
├── package.json
├── tsconfig.json
├── .env.example
├── .gitignore
├── 
├── src/
│   ├── api/                         # API calls al backend
│   │   ├── client.ts               # Axios/fetch setup
│   │   ├── auth.ts                 # POST /login, /logout, /me
│   │   ├── checklists.ts           # GET /checklists/active, POST /submit
│   │   ├── vehicle-logs.ts         # POST /entry, /exit, /photos, /incidents
│   │   ├── trips.ts                # GET /trips, POST /trips, POST /end
│   │   └── fuel.ts                 # POST /fuel-load
│   │
│   ├── context/                    # State management
│   │   ├── AuthContext.tsx         # User + token
│   │   ├── VehicleContext.tsx      # Vehicle actual
│   │   ├── TripContext.tsx         # Trip en progreso
│   │   └── OfflineContext.tsx      # Datos pendientes
│   │
│   ├── hooks/                      # Custom hooks
│   │   ├── useAuth.ts              # Auth logic
│   │   ├── useLocation.ts          # GPS tracking
│   │   ├── useCamera.ts            # Photo capture
│   │   ├── useOfflineStorage.ts    # AsyncStorage
│   │   └── useNetworkStatus.ts     # Connection check
│   │
│   ├── screens/                    # Pantallas
│   │   ├── auth/
│   │   │   ├── LoginScreen.tsx
│   │   │   └── SplashScreen.tsx
│   │   │
│   │   ├── home/
│   │   │   ├── HomeScreen.tsx
│   │   │   └── DashboardWidget.tsx
│   │   │
│   │   ├── checklist/
│   │   │   ├── ChecklistListScreen.tsx
│   │   │   ├── ChecklistFormScreen.tsx
│   │   │   └── ChecklistSummaryScreen.tsx
│   │   │
│   │   ├── trips/
│   │   │   ├── TripsListScreen.tsx
│   │   │   ├── TripMapScreen.tsx
│   │   │   ├── TripDetailsScreen.tsx
│   │   │   └── TripsHistoryScreen.tsx
│   │   │
│   │   ├── incidents/
│   │   │   ├── IncidentReportScreen.tsx
│   │   │   └── IncidentsListScreen.tsx
│   │   │
│   │   ├── fuel/
│   │   │   └── FuelLoadScreen.tsx
│   │   │
│   │   └── settings/
│   │       └── SettingsScreen.tsx
│   │
│   ├── components/                 # Componentes reutilizables
│   │   ├── forms/
│   │   │   ├── FormInput.tsx
│   │   │   ├── FormCheckbox.tsx
│   │   │   ├── FormPhoto.tsx
│   │   │   └── FormSignature.tsx
│   │   │
│   │   ├── common/
│   │   │   ├── Header.tsx
│   │   │   ├── LoadingSpinner.tsx
│   │   │   ├── ErrorAlert.tsx
│   │   │   ├── SuccessAlert.tsx
│   │   │   └── BottomTabBar.tsx
│   │   │
│   │   ├── maps/
│   │   │   └── MapView.tsx
│   │   │
│   │   └── cards/
│   │       ├── VehicleCard.tsx
│   │       ├── TripCard.tsx
│   │       └── IncidentCard.tsx
│   │
│   ├── utils/                      # Funciones auxiliares
│   │   ├── constants.ts            # URLs, colores, etc
│   │   ├── validators.ts           # Validaciones
│   │   ├── formatters.ts           # Formatear data
│   │   ├── storage.ts              # AsyncStorage helpers
│   │   ├── locationService.ts      # GPS utils
│   │   └── errorHandler.ts         # Error handling
│   │
│   ├── types/                      # TypeScript types globales
│   │   └── index.ts
│   │
│   └── App.tsx                     # Rutas principales
│
├── assets/
│   ├── images/
│   ├── icons/
│   └── fonts/
│
└── __tests__/                      # Tests
    ├── api/
    ├── hooks/
    └── utils/
```

---

### Flujo de Navegación (React Navigation)

```
Auth Stack (No autenticado)
├── Splash
└── Login

App Stack (Autenticado)
├── Home Tab
│   ├── Dashboard
│   ├── Vehicle Profile
│   └── Quick Actions
│
├── Checklist Tab
│   ├── Checklist List
│   ├── Checklist Form
│   └── History
│
├── Trips Tab
│   ├── Active Trip
│   ├── Trip Map
│   ├── Trip History
│   └── Trip Details
│
├── Incidents Tab
│   ├── Report Incident
│   └── Incidents List
│
├── Fuel Tab
│   └── Fuel Load Form
│
└── Settings Tab
    ├── Profile
    ├── Preferences
    └── Logout
```

---

## 📦 STACK TECNOLÓGICO

### Dependencias Principales

```json
{
  "dependencies": {
    "react": "18.2.x",
    "react-native": "0.73.x",
    "expo": "51.x",
    "expo-location": "^16.x",
    "expo-camera": "^14.x",
    "expo-image-picker": "^14.x",
    "expo-crypto": "^12.x",
    "expo-media-library": "^15.x",
    "expo-constants": "^15.x",
    "@react-navigation/native": "^6.1.x",
    "@react-navigation/bottom-tabs": "^6.5.x",
    "@react-navigation/stack": "^6.3.x",
    "react-native-screens": "~3.26.x",
    "react-native-safe-area-context": "^4.7.x",
    "react-native-gesture-handler": "~2.14.x",
    "@react-native-async-storage/async-storage": "^1.x",
    "axios": "^1.6.x",
    "zustand": "^4.4.x",
    "react-native-maps": "^1.7.x",
    "react-native-svg": "^13.x",
    "react-native-vector-icons": "^9.x",
    "@react-native-clipboard/clipboard": "^1.x",
    "date-fns": "^2.x",
    "lodash": "^4.x",
    "formik": "^2.x",
    "yup": "^1.x"
  },
  "devDependencies": {
    "@types/react": "^18.x",
    "@types/react-native": "^0.73.x",
    "typescript": "^5.x",
    "@testing-library/react-native": "^12.x",
    "jest": "^29.x"
  }
}
```

---

## 🔐 GUÍAS DE SEGURIDAD

### 1. Gestión de Tokens

**NUNCA guardar en localStorage/AsyncStorage sin encriptar**:

```typescript
// ❌ MALO
AsyncStorage.setItem('token', token);

// ✅ BUENO
import * as SecureStore from 'expo-secure-store';

await SecureStore.setItemAsync('auth_token', token);
```

---

### 2. Certificado SSL

```typescript
// En desarrollo con HTTPS auto-firmado
import axios from 'axios';

const api = axios.create({
  baseURL: 'https://tu-backend.local/api/v1',
  httpsAgent: {
    rejectUnauthorized: false // ⚠️ Solo desarrollo
  }
});
```

---

### 3. Credenciales

Nunca guardar en el código:

```typescript
// .env
REACT_APP_API_URL=https://api.bitcar.com/api/v1
REACT_APP_API_VERSION=v1

// Acceso
const API_URL = process.env.REACT_APP_API_URL;
```

---

### 4. Validación de Entrada

```typescript
// Validar todos los inputs
import * as Yup from 'yup';

const schema = Yup.object().shape({
  email: Yup.string().email().required(),
  password: Yup.string().min(6).required()
});

await schema.validate({ email, password });
```

---

### 5. Handling de Errores Sensibles

```typescript
// Nunca mostrar stack traces al usuario
try {
  await loginUser(email, password);
} catch (error) {
  // Log interno
  console.error('Auth error:', error);
  
  // Mensaje genérico al usuario
  showAlert('Error al iniciar sesión');
}
```

---

## 👤 FLUJOS DE USUARIO

### Flujo 1: Inicio de Día (Entrada)

```
1. Abrir app → Login (si no tiene token válido)
2. Home → "Entrada de Vehículo"
3. Seleccionar vehículo (si hay múltiples)
4. Seleccionar checklist de entrada
5. Llenar formulario dinámico
   - Responder preguntas
   - Capturar fotos
   - Ingresar kilometraje/combustible
6. Firmar digitalmente
7. Enviar → Confirmación
8. Disponible para iniciar viajes
```

---

### Flujo 2: Viaje Activo

```
1. Home → "Iniciar Viaje"
2. Confirmar datos iniciales
   - Vehículo correcto
   - Kilometraje inicial
   - Combustible inicial
3. Pantalla de mapa en vivo
   - Mostrar ubicación actual
   - Trazo de ruta
   - Velocidad/Distancia
4. Opciones durante viaje:
   a) "+ Punto": Agregar parada (ubicación + foto)
   b) "Pausa": Pausar rastreo
   c) "Fin Viaje": Terminar
5. Fin Viaje:
   - Ingresar datos finales
   - Confirmar término
   - Ver resumen del viaje
```

---

### Flujo 3: Reporte de Incidente

```
1. Desde Home o durante Viaje → "Reportar Incidente"
2. Formulario:
   - Descripción (textarea)
   - Severidad (selector)
   - Foto (cámara)
   - Ubicación (automática)
3. Enviar → Confirmación
4. Incidente registrado en backend
```

---

### Flujo 4: Carga de Combustible

```
1. Home → "Cargar Combustible"
2. Formulario:
   - Cantidad (litros)
   - Costo
   - Ubicación (automática)
   - Foto recibo (opcional)
3. Enviar → Confirmación
```

---

### Flujo 5: Fin de Día (Salida)

```
1. Finalizar viaje activo (si existe)
2. Home → "Salida de Vehículo"
3. Seleccionar checklist de salida
4. Llenar formulario dinámico
5. Capturar fotos finales
6. Firmar digitalmente
7. Enviar → Confirmación
8. Sesión lista para cerrar o cambiar vehículo
```

---

## 🚀 INSTRUCCIONES DE SETUP

### Instalación Inicial

```bash
# 1. Crear proyecto Expo
npx create-expo-app BITCAR-Mobile --template

# 2. Navegar a carpeta
cd BITCAR-Mobile

# 3. Instalar dependencias
npm install

# 4. Instalar dependencias específicas
npm install expo-location expo-camera expo-image-picker \
  @react-navigation/native @react-navigation/bottom-tabs \
  react-native-maps axios zustand formik yup \
  @react-native-async-storage/async-storage \
  expo-secure-store react-native-vector-icons

# 5. Configurar TypeScript
npx expo init --template typescript

# 6. Instalar dev dependencies
npm install --save-dev typescript @types/react @types/react-native
```

---

### Configuración de Variables de Entorno

```bash
# Crear archivo .env
cp .env.example .env

# Llenar con valores:
# REACT_APP_API_URL=https://api.bitcar.com/api/v1
# REACT_APP_GOOGLE_MAPS_KEY=tu_clave_aqui
```

---

### Ejecución en Desarrollo

```bash
# Terminal 1: Servidor Expo
npx expo start

# Terminal 2 (desde app de Expo):
# - Escanear QR con dispositivo
# - O presionar 'i' para iOS, 'a' para Android

# Desarrollo con TypeScript
npm run type-check
```

---

### Building para Producción

```bash
# iOS
eas build --platform ios

# Android
eas build --platform android

# Ambas plataformas
eas build
```

---

## 📋 CONSIDERACIONES FINALES

### Cosas a Verificar Antes de Iniciar

- [ ] Backend tiene todos los endpoints listados en sección API Endpoints
- [ ] Token refresh está implementado en backend
- [ ] CORS está configurado correctamente para mobile
- [ ] Rate limiting no afecta sincronización legítima
- [ ] Se puede subir imágenes (multipart)
- [ ] Logs de auditoría funcionan correctamente
- [ ] Tests unitarios en backend cubren auth + endpoints críticos

### Mejoras Futuras (No incluidas en MVP)

- [ ] Sincronización automática de datos offline
- [ ] Caché inteligente de trips
- [ ] Notificaciones push (incidentes asignados)
- [ ] Modo dark theme
- [ ] Multidispositivo (múltiples operadores)
- [ ] Exportar reportes a PDF
- [ ] Integración con Bluetooth OBD2 (datos vehículo)
- [ ] Alertas de velocidad excesiva

---

## 📞 CONTACTO Y SOPORTE

**Backend API**: `https://tu-backend.com/api/v1`  
**Documentación Backend**: [Consultar `/docs/SCHEMA_ACTUAL_BITCAR.md`]  
**Environment**: Producción (remoto)  

---

**Última actualización**: 12 de enero de 2026  
**Versión**: 1.0  
**Estado**: Listo para desarrollo
