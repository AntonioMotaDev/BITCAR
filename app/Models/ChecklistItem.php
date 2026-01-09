<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChecklistItem extends Model
{
    protected $fillable = [
        'checklist_id',
        'label',
        'description',
        'type',
        'order',
        'required',
    ];

    protected $casts = [
        'checklist_id' => 'integer',
        'order' => 'integer',
        'required' => 'boolean',
    ];

    // Relaciones
    public function checklist(): BelongsTo
    {
        return $this->belongsTo(Checklist::class);
    }

    public function vehicleLogItems(): HasMany
    {
        return $this->hasMany(VehicleLogItem::class);
    }
}
