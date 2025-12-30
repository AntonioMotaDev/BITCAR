<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Checklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'version',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // Relaciones
    public function items(): HasMany
    {
        return $this->hasMany(ChecklistItem::class)->orderBy('order');
    }

    public function vehicleLogs(): HasMany
    {
        return $this->hasMany(VehicleLog::class);
    }
}
