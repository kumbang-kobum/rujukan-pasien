<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;

    protected $table = 'pasien';

    protected $fillable = [
        'no_rkm_medis',
        'nik',
        'patient_ihs_number',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'telepon',
    ];

    public function kunjungan()
    {
        return $this->hasMany(Kunjungan::class, 'pasien_id');
    }
}
