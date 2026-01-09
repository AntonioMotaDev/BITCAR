<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Checklist extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relaciones
    public function checklistItems(): HasMany
    {
        return $this->hasMany(ChecklistItem::class)->orderBy('order');
    }

    public function vehicleLogs(): HasMany
    {
        return $this->hasMany(VehicleLog::class);
    }
}
