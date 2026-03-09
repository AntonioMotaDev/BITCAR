# BITCAR

Sistema de gestión de bitácoras vehiculares con panel web y API REST para app móvil.

## Vista previa

![Panel principal](docs/images/dashboard.png)

## Características

- Gestión de vehículos, usuarios y checklists
- Registro de entrada/salida de vehículo
- Gestión de incidencias
- Tracking GPS por viaje
- API autenticada con Sanctum

## Stack

- Laravel 12
- Blade + Bootstrap 5
- MySQL
- Laravel Breeze
- Laravel Sanctum

## Requisitos

- PHP 8.2+
- Composer
- Node.js y npm
- MySQL 8+

## Instalación rápida

1. Clonar repositorio e instalar dependencias:
    - `composer install`
    - `npm install`
2. Configurar `.env` y base de datos.
3. Ejecutar migraciones:
    - `php artisan migrate --seed`
4. Generar enlace de storage:
    - `php artisan storage:link`
5. Iniciar proyecto:
    - `php artisan serve`
    - `npm run dev`

## Endpoints principales

- `POST /api/v1/login`
- `POST /api/v1/logout`
- `GET /api/v1/checklists/active`
- `POST /api/v1/vehicle-logs/exit`
- `POST /api/v1/vehicle-logs/entry`
- `GET /api/v1/trips/active`

## Capturas 

![Listado de bitácoras](docs/images/checklists.png)
![Detalle de viaje](docs/images/trip-detail.png)

## Licencia

Uso interno / privado.
