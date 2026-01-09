<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TripLocation extends Model
{
    protected $fillable = [
        'trip_id',
        'latitude',
        'longitude',
        'accuracy',
        'speed',
        'recorded_at',
    ];

    protected $casts = [
        'trip_id' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'accuracy' => 'decimal:2',
        'speed' => 'decimal:2',
        'recorded_at' => 'datetime',
    ];

    // Relaciones
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
}
