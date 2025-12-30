<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'user_id',
        'start_vehicle_log_id',
        'end_vehicle_log_id',
        'started_at',
        'ended_at',
        'total_distance_km',
        'estimated_fuel_consumption',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'total_distance_km' => 'decimal:2',
            'estimated_fuel_consumption' => 'decimal:2',
        ];
    }

    // Relaciones
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function startLog(): BelongsTo
    {
        return $this->belongsTo(VehicleLog::class, 'start_vehicle_log_id');
    }

    public function endLog(): BelongsTo
    {
        return $this->belongsTo(VehicleLog::class, 'end_vehicle_log_id');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(TripLocation::class)->orderBy('recorded_at');
    }

    // Helpers
    public function isActive(): bool
    {
        return is_null($this->ended_at);
    }
}
