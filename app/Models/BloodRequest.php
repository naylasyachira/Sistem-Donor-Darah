<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    protected $fillable = [
        'request_code',
        'hospital_id',
        'request_date',
        'blood_type',
        'rhesus',
        'quantity',
        'urgency',
        'purpose',
        'status',
        'notes',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
