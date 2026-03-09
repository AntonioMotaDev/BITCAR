<?php

namespace Database\Seeders;

use App\Models\Trip;
use Illuminate\Database\Seeder;

class FixActiveTripsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Arreglar viajes que deberían estar activos pero no tienen is_active = true
        $activeTrips = Trip::whereNull('end_time')->update([
            'is_active' => true,
        ]);

        $this->command->info("Se arreglaron {$activeTrips} viajes activos");

        // Mostrar viajes activos después de la corrección
        $trips = Trip::whereNull('end_time')->get(['id', 'user_id', 'vehicle_id', 'is_active', 'end_time']);
        $this->command->info("Viajes activos actuales:");
        foreach ($trips as $trip) {
            $this->command->info("  - Trip {$trip->id}: user_id={$trip->user_id}, vehicle_id={$trip->vehicle_id}, is_active={$trip->is_active}");
        }
    }
}
