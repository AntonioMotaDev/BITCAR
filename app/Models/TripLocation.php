<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TripLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'latitude',
        'longitude',
        'accuracy',
        'speed',
        'recorded_at',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'accuracy' => 'float',
            'speed' => 'float',
            'recorded_at' => 'datetime',
        ];
    }

    // Relaciones
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
}
