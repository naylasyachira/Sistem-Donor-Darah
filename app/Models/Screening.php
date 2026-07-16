<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Screening extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'user_id',
        'screening_code',
        'screening_date',
        'tekanan_darah',
        'berat_badan',
        'tinggi_badan',
        'hemoglobin',
        'suhu_tubuh',
        'denyut_nadi',
        'status',
        'notes',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
