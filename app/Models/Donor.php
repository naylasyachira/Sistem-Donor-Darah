<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'donor_code', 'nik', 'nama_lengkap', 'jenis_kelamin',
    'golongan_darah', 'rhesus', 'tanggal_lahir', 'alamat',
    'no_hp', 'status'
])]
class Donor extends Model
{
    public function screenings()
    {
        return $this->hasMany(Screening::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}
