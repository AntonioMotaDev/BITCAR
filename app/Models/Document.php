<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'user_id',
        'vehicle_id',
        'file_name',
        'path',
        'expiration_date',
        'status',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'vehicle_id' => 'integer',
        'expiration_date' => 'datetime',
    ];
}