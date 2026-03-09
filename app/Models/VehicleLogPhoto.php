<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleLogPhoto extends Model
{
    protected $fillable = [
        'checklist_item_id',
        'vehicle_log_id',
        'file_path',
        'description',
    ];

    protected $casts = [
        'checklist_item_id' => 'integer',
        'vehicle_log_id' => 'integer',
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
