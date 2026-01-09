<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incident extends Model
{
    protected $fillable = [
        'vehicle_log_id',
        'description',
        'severity',
        'is_resolved',
        'resolution_notes',
    ];

    protected $casts = [
        'vehicle_log_id' => 'integer',
        'is_resolved' => 'boolean',
    ];

    // Relaciones
    public function vehicleLog(): BelongsTo
    {
        return $this->belongsTo(VehicleLog::class);
    }
}
