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
                'code' => 'VEH-001',
                'plate' => 'ABC-123',
                'brand' => 'Toyota',
                'model' => 'Hilux',
                'year' => 2022,
                'status' => 'active',
            ],
            [
                'code' => 'VEH-002',
                'plate' => 'DEF-456',
                'brand' => 'Ford',
                'model' => 'Ranger',
                'year' => 2021,
                'status' => 'active',
            ],
            [
                'code' => 'VEH-003',
                'plate' => 'GHI-789',
                'brand' => 'Chevrolet',
                'model' => 'Colorado',
                'year' => 2023,
                'status' => 'active',
            ],
            [
                'code' => 'VEH-004',
                'plate' => 'JKL-012',
                'brand' => 'Nissan',
                'model' => 'Frontier',
                'year' => 2020,
                'status' => 'maintenance',
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::create($vehicle);
        }
    }
}
