<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    protected $fillable = [
        'vehicle_id',
        'user_id',
        'start_time',
        'end_time',
        'start_mileage',
        'end_mileage',
        'start_fuel_level',
        'end_fuel_level',
        'distance_km',
        'estimated_fuel_consumption',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'vehicle_id' => 'integer',
        'user_id' => 'integer',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'start_mileage' => 'decimal:2',
        'end_mileage' => 'decimal:2',
        'start_fuel_level' => 'decimal:2',
        'end_fuel_level' => 'decimal:2',
        'distance_km' => 'decimal:2',
        'estimated_fuel_consumption' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relaciones
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tripLocations(): HasMany
    {
        return $this->hasMany(TripLocation::class)->orderBy('recorded_at');
    }
}
