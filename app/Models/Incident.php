<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_log_id',
        'description',
        'severity',
        'is_resolved',
    ];

    protected function casts(): array
    {
        return [
            'is_resolved' => 'boolean',
        ];
    }

    // Relaciones
    public function vehicleLog(): BelongsTo
    {
        return $this->belongsTo(VehicleLog::class);
    }
}
