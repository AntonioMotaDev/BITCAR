<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            [
                'brand' => 'Toyota',
                'model' => 'Hilux',
                'year' => 2022,
                'license_plate' => 'ABC-123',
                'vin' => '1HGBH41JXMN109186',
                'color' => 'Blanco',
                'type' => 'pickup',
                'mileage' => 15000.50,
                'fuel_capacity' => 80.00,
                'status' => 'activo',
            ],
            [
                'brand' => 'Ford',
                'model' => 'Ranger',
                'year' => 2021,
                'license_plate' => 'DEF-456',
                'vin' => '2HGBH41JXMN109187',
                'color' => 'Negro',
                'type' => 'pickup',
                'mileage' => 22000.00,
                'fuel_capacity' => 80.00,
                'status' => 'activo',
            ],
            [
                'brand' => 'Nissan',
                'model' => 'Frontier',
                'year' => 2023,
                'license_plate' => 'GHI-789',
                'vin' => '3HGBH41JXMN109188',
                'color' => 'Gris',
                'type' => 'pickup',
                'mileage' => 8000.00,
                'fuel_capacity' => 75.00,
                'status' => 'activo',
            ],
            [
                'brand' => 'Chevrolet',
                'model' => 'Silverado',
                'year' => 2020,
                'license_plate' => 'JKL-012',
                'vin' => '4HGBH41JXMN109189',
                'color' => 'Rojo',
                'type' => 'pickup',
                'mileage' => 35000.00,
                'fuel_capacity' => 90.00,
                'status' => 'mantenimiento',
            ],
        ];

        foreach ($vehicles as $vehicleData) {
            Vehicle::create($vehicleData);
        }
    }
}
