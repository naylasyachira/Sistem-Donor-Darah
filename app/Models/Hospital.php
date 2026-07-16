<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    protected $fillable = [
        'hospital_code',
        'hospital_name',
        'director_name',
        'address',
        'phone',
        'email',
        'status',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
