<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodStock extends Model
{
    protected $fillable = [
        'blood_type',
        'rhesus',
        'quantity_bag',
        'total_volume_ml',
        'last_update',
    ];

    protected $casts = [
        'last_update' => 'datetime',
    ];
}
