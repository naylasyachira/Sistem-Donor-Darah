<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'donation_code',
        'donor_id',
        'screening_id',
        'user_id',
        'donation_date',
        'blood_type',
        'rhesus',
        'blood_volume',
        'status',
        'notes',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function screening()
    {
        return $this->belongsTo(Screening::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
