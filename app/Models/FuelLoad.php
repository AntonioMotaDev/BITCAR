<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuelLoad extends Model
{
    protected $fillable = [
        'vehicle_log_id',
        'vehicle_id',
        'user_id',
        'amount_liters',
        'cost',
        'currency',
        'latitude',
        'longitude',
        'notes',
    ];

    protected $casts = [
        'vehicle_id' => 'integer',
        'vehicle_log_id' => 'integer',
        'user_id' => 'integer',
        'amount_liters' => 'decimal:2',
        'cost' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relaciones
    public function vehicleLog(): BelongsTo
    {
        return $this->belongsTo(VehicleLog::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
