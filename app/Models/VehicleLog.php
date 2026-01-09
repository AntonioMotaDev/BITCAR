<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class VehicleLog extends Model
{
    protected $fillable = [
        'vehicle_id',
        'user_id',
        'checklist_id',
        'type',
        'mileage',
        'fuel_level',
        'notes',
    ];

    protected $casts = [
        'vehicle_id' => 'integer',
        'user_id' => 'integer',
        'checklist_id' => 'integer',
        'mileage' => 'decimal:2',
        'fuel_level' => 'decimal:2',
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

    public function checklist(): BelongsTo
    {
        return $this->belongsTo(Checklist::class);
    }

    public function vehicleLogItems(): HasMany
    {
        return $this->hasMany(VehicleLogItem::class);
    }

    public function vehicleLogPhotos(): HasMany
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
}
