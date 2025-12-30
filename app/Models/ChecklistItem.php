<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChecklistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'checklist_id',
        'label',
        'answer',
        'type',
        'required',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'required' => 'boolean',
            'order' => 'integer',
        ];
    }

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
