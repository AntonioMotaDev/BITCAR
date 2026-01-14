<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $fillable = [
        'brand',
        'model',
        'year',
        'license_plate',
        'vin',
        'color',
        'type',
        'mileage',
        'fuel_capacity',
        'status',
        'image',
    ];

    protected $casts = [
        'year' => 'integer',
        'mileage' => 'decimal:2',
        'fuel_capacity' => 'decimal:2',
    ];

    // Relaciones
    public function vehicleAssignments(): HasMany
    {
        return $this->hasMany(VehicleAssignment::class);
    }

    public function vehicleLogs(): HasMany
    {
        return $this->hasMany(VehicleLog::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }
}
