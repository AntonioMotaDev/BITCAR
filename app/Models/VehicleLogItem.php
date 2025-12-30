<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleLogItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_log_id',
        'checklist_item_id',
        'value',
    ];

    // Relaciones
    public function vehicleLog(): BelongsTo
    {
        return $this->belongsTo(VehicleLog::class);
    }

    public function checklistItem(): BelongsTo
    {
        return $this->belongsTo(ChecklistItem::class);
    }
}
