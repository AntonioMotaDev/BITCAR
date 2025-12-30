<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class VehicleLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'user_id',
        'checklist_id',
        'type',
        'odometer',
        'fuel_level',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'odometer' => 'integer',
            'fuel_level' => 'decimal:2',
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

    public function checklist(): BelongsTo
    {
        return $this->belongsTo(Checklist::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(VehicleLogItem::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(VehicleLogPhoto::class);
    }

    public function signature(): HasOne
    {
        return $this->hasOne(Signature::class);
    }

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class);
    }

    public function startTrip(): HasOne
    {
        return $this->hasOne(Trip::class, 'start_vehicle_log_id');
    }

    public function endTrip(): HasOne
    {
        return $this->hasOne(Trip::class, 'end_vehicle_log_id');
    }
}
