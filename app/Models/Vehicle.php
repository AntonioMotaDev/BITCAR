<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'plate',
        'brand',
        'model',
        'year',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
        ];
    }

    // Relaciones
    public function assignments(): HasMany
    {
        return $this->hasMany(VehicleAssignment::class);
    }

    public function activeAssignment(): HasOne
    {
        return $this->hasOne(VehicleAssignment::class)
            ->where('is_active', true)
            ->latest();
    }

    public function logs(): HasMany
    {
        return $this->hasMany(VehicleLog::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    // Helpers
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isInMaintenance(): bool
    {
        return $this->status === 'maintenance';
    }
}
