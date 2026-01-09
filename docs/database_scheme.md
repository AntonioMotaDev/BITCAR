DISEÑO DETALLADO DE BASE DE DATOS 
Sistema de Bitácora y Control Vehicular

1. Objetivo del documento
Definir de manera precisa el modelo de datos relacional, incluyendo tablas, campos, tipos de datos, claves primarias, claves foráneas, índices y reglas de integridad, para soportar correctamente el Sistema de Bitácora y Control Vehicular.
Este documento sirve como base directa para la creación de migraciones en Laravel y para garantizar consistencia, auditoría y escalabilidad.

2. Consideraciones generales
Motor de base de datos: MySQL 8+ o PostgreSQL 13+
Charset recomendado: utf8mb4
Todas las tablas incluyen:
id (PK)
created_at
updated_at
Uso de soft deletes solo donde aplique
Integridad referencial obligatoria

3. Tablas de seguridad y control
3.1 roles
Campo
Tipo
Descripción
id
bigint PK
Identificador
name
varchar(50)
Nombre del rol
description
varchar(150)
Descripción


3.2 users
Campo
Tipo
id
bigint PK
name
varchar(150)
email
varchar(150) UNIQUE
password
varchar(255)
role_id
bigint FK → roles.id
status
image
enum(inactive, active)
string
last_login_at
timestamp NULL

Índices: - idx_users_role

4. Catálogo de vehículos
4.1 vehicles
Campo
Tipo
id
bigint PK
code
varchar(50) UNIQUE
brand
varchar(100)
model
varchar(100)
plate
max_gas_capacity
varchar(50) UNIQUE
float
year
smallint
status
image
enum(inactive,active,maintenance)
string


4.2 vehicle_assignments
Campo
Tipo
id
bigint PK
vehicle_id
bigint FK → vehicles.id
user_id
bigint FK → users.id
start_date
datetime
end_date
datetime NULL
is_active
boolean




5. Checklists
5.1 checklists
Campo
Tipo
id
bigint PK
name
varchar(100)
version
varchar(20)
is_active
boolean


5.2 checklist_items
Campo
Tipo
id
bigint PK
checklist_id
bigint FK → checklists.id
label
varchar(255)
type
enum(boolean,text,number,photo, signature)
order
int
required
boolean


6. Bitácoras vehiculares
6.1 vehicle_logs
Campo
Tipo
id
bigint PK
vehicle_id
bigint FK → vehicles.id
user_id
bigint FK → users.id
checklist_id
bigint FK → checklists.id
type
enum(entry,exit, service)
odometer
decimal(10,7)
fuel_level
decimal(10,7)
notes
varchar(255)
created_at
timestamp

Índices: - idx_vehicle_logs_vehicle - idx_vehicle_logs_user - idx_vehicle_logs_type

6.2 vehicle_log_items
Campo
Tipo
id
bigint PK
vehicle_log_id
bigint FK → vehicle_logs.id
checklist_item_id
bigint FK → checklist_items.id
boolean_answer
numeric_answer
text_answer
boolean
decimal(10,7)
varchar(255)


6.3 media
Campo
Tipo
id
bigint PK
vehicle_log_item_id
trip_event_id
bigint FK → vehicle_logs_items.id
Bigint FK → trip_events.id
incident_id
Bigint FK →incidents.id
type
section
path
enum( photo, signature, receipt, other)
varchar(100)
varchar(255)


7. Auditoría
7.1 activity_logs
Campo
Tipo
id
bigint PK
user_id
bigint FK → users.id
action
varchar(255)
entity
varchar(255)
entity_id
bigint
ip_address
varchar(45)
created_at
timestamp


8. Viajes
8.1 trips
Campo
Tipo
id
bigint PK
user_id
vehicle_id
start_vehicle_log_id
end_vehicle_log_id
started_at
ended_at
total_distance_km
estimated_fuel_consumption

8.2 trip_locations


bigint FK → users.id
bigint FK → vehicles.id
bigint FK → vehicle_logs.id
bigint FK → vehicle_logs.id
datetime
datetime
decimal(10,7)
decimal(10,7)
Campo
id
trip_id
latitude
longitude
accuracy
speed
recorder_at

8.3 fuel_loads


Tipo
bigint PK
bigint FK → trips.id
decimal(10,7)
decimal(10,7)
int
float
datetime
Campo
id
trip_id
payment_method
total_amount
litres
loaded_at
receipt_path
latitude
longitude

8.4 trip_events


Tipo
bigint PK
bigint FK → trips.id
varchar(255)
decimal(10,7)
decimal(10,7)
datetime
varchar(100)
decimal(10,7)
decimal(10,7)
Campo
id
trip_id
description
ocurred_at
latitude
longitude

8.5 incidents


Tipo
bigint PK
bigint FK → trips.id
varchar(255)
Datetime
decimal(10,7)
decimal(10,7)
Campo
id
vehicle_log_id
description
severity
Is_resolved


9. Mantenimiento
9.1 vehicle_services



Tipo
bigint PK
bigint FK → vehicle_logs.id
varchar(255)
varchar(50)
boolean



Campo
id
vehicle_id
checklist_id
description
recommendations
date
is_external
done_by
price

10.Documentos
10.1 documentos


Tipo
bigint PK
bigint FK → vehicle.id
bigint FK → checklist.id
varchar(255)
varchar(255)
date
boolean
varchar(50)
decimal(10,7)






Campo
id
user_id
vehicle_id
file_name
path
expiration_date
status
Tipo
bigint PK
bigint FK → users.id
bigint FK → vehicle.id
varchar(50)
varchar(100)
date
enum(inactivo, activo, vencido, eliminado)


11. Reglas de integridad y negocio	
Los registros de vehicle_logs son inmutables
No se permite eliminar bitácoras
Evidencia obligatoria en cada bitácora
Las relaciones FK usan ON DELETE RESTRICT

12. Escalabilidad y optimización
Índices en campos de búsqueda frecuente
Separación de storage para evidencias
Posibilidad de particionar vehicle_logs

13. Relación con Laravel
Cada tabla corresponde a un modelo Eloquent
Migraciones versionadas
Factories para pruebas









