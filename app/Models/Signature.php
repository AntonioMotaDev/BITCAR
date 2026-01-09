<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Signature extends Model
{
    protected $fillable = [
        'vehicle_log_id',
        'signature_data',
        'signer_name',
        'signed_at',
    ];

    protected $casts = [
        'vehicle_log_id' => 'integer',
        'signed_at' => 'datetime',
    ];

    // Relaciones
    public function vehicleLog(): BelongsTo
    {
        return $this->belongsTo(VehicleLog::class);
    }
}
