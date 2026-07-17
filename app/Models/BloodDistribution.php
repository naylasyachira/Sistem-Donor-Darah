<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodDistribution extends Model
{
    protected $fillable = [
        'distribution_code',
        'blood_request_id',
        'courier_name',
        'status',
        'distribution_date',
    ];

    public function bloodRequest()
    {
        return $this->belongsTo(BloodRequest::class);
    }
}
