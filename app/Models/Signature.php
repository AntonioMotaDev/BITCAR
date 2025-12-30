<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Signature extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_log_id',
        'path',
    ];

    // Relaciones
    public function vehicleLog(): BelongsTo
    {
        return $this->belongsTo(VehicleLog::class);
    }
}
