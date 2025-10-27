<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory;

    protected $table = 'kunjungan';

    protected $fillable = [
        'no_rawat',          // nomor rawat otomatis ala SIMRS
        'pasien_id',
        'dokter_id',
        'user_id',
        'rumah_sakit_id',
        'poli',
        'tanggal_kunjungan',
        'waktu_masuk',
        'keluhan_utama',
        'status_pulang',     // 0 = Rawat, 1 = Pulang
        'waktu_pulang',      // kapan dipulangkan
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'waktu_masuk'       => 'datetime',
        'waktu_pulang'      => 'datetime',
        'status_pulang'     => 'boolean',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function soap()
    {
        return $this->hasMany(Soap::class);
    }

    public function rujukan()
    {
        return $this->hasOne(Rujukan::class);
    }

    public function berkasMedis()
    {
        return $this->hasMany(BerkasMedis::class, 'kunjungan_id');
    }
}