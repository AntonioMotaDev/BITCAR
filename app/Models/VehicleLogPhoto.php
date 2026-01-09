<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleLogPhoto extends Model
{
    protected $fillable = [
        'vehicle_log_id',
        'file_path',
        'description',
    ];

    protected $casts = [
        'vehicle_log_id' => 'integer',
    ];

    // Relaciones
    public function vehicleLog(): BelongsTo
    {
        return $this->belongsTo(VehicleLog::class);
    }
}
