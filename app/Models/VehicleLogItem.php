<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleLogItem extends Model
{
    protected $fillable = [
        'vehicle_log_id',
        'checklist_item_id',
        'boolean_answer',
        'text_answer',
        'numeric_answer',
    ];

    protected $casts = [
        'vehicle_log_id' => 'integer',
        'checklist_item_id' => 'integer',
        'boolean_answer' => 'boolean',
        'numeric_answer' => 'decimal:2',
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
